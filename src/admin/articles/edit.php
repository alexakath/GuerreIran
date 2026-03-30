<?php
require_once '../../includes/db.php';
require_once '../../includes/functions.php';

requireAuth();

$user = isset($_SESSION['user']) ? $_SESSION['user'] : ['username' => 'Utilisateur'];

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
send_security_headers();
if ($id <= 0) redirect('list.php');

$stmt = $pdo->prepare("SELECT * FROM articles WHERE id = ?");
$stmt->execute([$id]);
$article = $stmt->fetch();
if (!$article) redirect('list.php');

$catsStmt = $pdo->query("SELECT id, name FROM categories ORDER BY name ASC");
$categories = $catsStmt->fetchAll();
$tagsStmt = $pdo->query("SELECT id, name FROM tags ORDER BY name ASC");
$allTags = $tagsStmt->fetchAll();

// existing tag ids
$selTagsStmt = $pdo->prepare("SELECT tag_id FROM article_tags WHERE article_id = ?");
$selTagsStmt->execute([$id]);
$existingTagRows = $selTagsStmt->fetchAll();
$existingTagIds = array_map(function($r){ return (int)$r['tag_id']; }, $existingTagRows);

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF check
    $token = $_POST['csrf_token'] ?? '';
    if (!verify_csrf($token)) {
        $error = 'Requête invalide (CSRF).';
    } else {
        $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $category_id = isset($_POST['category_id']) ? (int)$_POST['category_id'] : 0;
    $tag_ids = isset($_POST['tags']) && is_array($_POST['tags']) ? array_map('intval', $_POST['tags']) : [];

    if ($title === '' || $content === '') {
        $error = 'Titre et contenu requis.';
    } else {
        // slug unique excluding current
        $baseSlug = slugify($title);
        $slug = $baseSlug;
        $i = 1;
        $existsStmt = $pdo->prepare("SELECT COUNT(*) FROM articles WHERE slug = ? AND id != ?");
        while (true) {
            $existsStmt->execute([$slug, $id]);
            if ($existsStmt->fetchColumn() == 0) break;
            $slug = $baseSlug . '-' . $i;
            $i++;
        }

        // handle image upload (optional)
        $imageUrl = $article['image_url'];
        if (!empty($_FILES['image']['name'])) {
            $file = $_FILES['image'];
            if ($file['error'] === UPLOAD_ERR_OK) {
                $allowed = ['image/jpeg' => '.jpg', 'image/png' => '.png', 'image/webp' => '.webp'];
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime = finfo_file($finfo, $file['tmp_name']);
                finfo_close($finfo);
                if (!array_key_exists($mime, $allowed)) {
                    $error = 'Type d\'image non supporté (jpg, png, webp).';
                } elseif ($file['size'] > 2 * 1024 * 1024) {
                    $error = 'Image trop lourde (max 2MB).';
                } else {
                    $ext = $allowed[$mime];
                    $filename = time() . '_' . bin2hex(random_bytes(6)) . $ext;
                    $destDir = __DIR__ . '/../../assets/images/articles/';
                    if (!is_dir($destDir)) mkdir($destDir, 0755, true);
                    $dest = $destDir . $filename;
                    if (move_uploaded_file($file['tmp_name'], $dest)) {
                        // remove old image
                        if (!empty($imageUrl) && file_exists(__DIR__ . '/../../' . $imageUrl)) {
                            @unlink(__DIR__ . '/../../' . $imageUrl);
                        }
                        $imageUrl = 'assets/images/articles/' . $filename;
                    } else {
                        $error = 'Impossible d\'enregistrer l\'image.';
                    }
                }
            } else {
                $error = 'Erreur lors de l\'upload image.';
            }
        }

        if ($error === '') {
            try {
                $pdo->beginTransaction();

                $update = $pdo->prepare("UPDATE articles SET title = ?, slug = ?, content = ?, image_url = ?, category_id = ? WHERE id = ?");
                $update->execute([$title, $slug, $content, $imageUrl, $category_id, $id]);

                // replace tags
                $del = $pdo->prepare("DELETE FROM article_tags WHERE article_id = ?");
                $del->execute([$id]);
                if (!empty($tag_ids)) {
                    $ins = $pdo->prepare("INSERT INTO article_tags (article_id, tag_id) VALUES (?, ?)");
                    foreach ($tag_ids as $t) {
                        $ins->execute([$id, (int)$t]);
                    }
                }

                $pdo->commit();
                redirect('list.php');
            } catch (Exception $e) {
                $pdo->rollBack();
                $error = 'Erreur base de données : ' . $e->getMessage();
            }
        }
    }
    }
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Éditer article — Admin</title>
    <link rel="preload" href="/assets/css/admin.min.css" as="style" onload="this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="/assets/css/admin.min.css"></noscript>
</head>
<body>
    <div class="admin-shell">
        <?php require_once __DIR__ . '/../_nav.php'; ?>

        <div class="main">
            <?php $pageTitle = "Éditer l'article"; require_once __DIR__ . '/../_header.php'; ?>

            <main class="container">
                <section class="card">
                    <h2>Éditer l'article</h2>
                    <form method="post" enctype="multipart/form-data" novalidate>
                        <?php echo csrf_field(); ?>
                        <label for="title">Titre</label>
                        <input id="title" name="title" type="text" required value="<?php echo e(isset($title) ? $title : $article['title']); ?>">

                        <label for="content">Contenu</label>
                        <textarea id="content" name="content" rows="8" required><?php echo e(isset($content) ? $content : $article['content']); ?></textarea>

                        <label for="category">Catégorie</label>
                        <select id="category" name="category_id">
                            <option value="0">-- Aucune --</option>
                            <?php foreach ($categories as $c): ?>
                                <option value="<?php echo $c['id']; ?>" <?php echo ($c['id'] == ($category_id ?? $article['category_id'])) ? 'selected' : ''; ?>><?php echo e($c['name']); ?></option>
                            <?php endforeach; ?>
                        </select>

                        <label for="tags">Tags (Ctrl / Cmd pour multi)</label>
                        <select id="tags" name="tags[]" multiple size="6">
                            <?php foreach ($allTags as $t): ?>
                                <option value="<?php echo $t['id']; ?>" <?php echo in_array($t['id'], $existingTagIds) ? 'selected' : ''; ?>><?php echo e($t['name']); ?></option>
                            <?php endforeach; ?>
                        </select>

                        <label for="image">Image (laisser vide pour conserver)</label>
                        <input id="image" name="image" type="file" accept="image/*">
                        <?php if (!empty($article['image_url'])): ?>
                            <div style="margin-top:8px"><img src="../../<?php echo e($article['image_url']); ?>" alt="" style="max-width:160px;border-radius:6px"></div>
                        <?php endif; ?>

                        <button class="btn" type="submit">Mettre à jour</button>
                    </form>

                    <?php if ($error): ?>
                        <div class="error"><?php echo e($error); ?></div>
                    <?php endif; ?>

                    <p class="small"><a href="list.php">← Retour à la liste</a></p>
                </section>
                <?php require_once __DIR__ . '/../_footer.php'; ?>
            </main>
        </div>
    </div>
</body>
</html>
