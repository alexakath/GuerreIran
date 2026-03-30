<?php
require_once '../../includes/db.php';
require_once '../../includes/functions.php';

requireAuth();

$user = isset($_SESSION['user']) ? $_SESSION['user'] : ['username' => 'Utilisateur'];
send_security_headers();

// fetch categories & tags for selects
$catsStmt = $pdo->query("SELECT id, name FROM categories ORDER BY name ASC");
$categories = $catsStmt->fetchAll();
$tagsStmt = $pdo->query("SELECT id, name FROM tags ORDER BY name ASC");
$allTags = $tagsStmt->fetchAll();

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
        // slug unique
        $baseSlug = slugify($title);
        $slug = $baseSlug;
        $i = 1;
        $existsStmt = $pdo->prepare("SELECT COUNT(*) FROM articles WHERE slug = ?");
        while (true) {
            $existsStmt->execute([$slug]);
            if ($existsStmt->fetchColumn() == 0) break;
            $slug = $baseSlug . '-' . $i;
            $i++;
        }

        // handle image upload
        $imageUrl = null;
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
                $insert = $pdo->prepare("INSERT INTO articles (title, slug, content, image_url, category_id, author_id) VALUES (?, ?, ?, ?, ?, ?)");
                $authorId = isset($_SESSION['user']['id']) ? (int)$_SESSION['user']['id'] : 1;
                $insert->execute([$title, $slug, $content, $imageUrl, $category_id, $authorId]);
                $articleId = (int) $pdo->lastInsertId();

                if (!empty($tag_ids)) {
                    $insertTag = $pdo->prepare("INSERT INTO article_tags (article_id, tag_id) VALUES (?, ?)");
                    foreach ($tag_ids as $t) {
                        $insertTag->execute([$articleId, (int)$t]);
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
    <title>Créer article — Admin</title>
    <link rel="preload" href="/assets/css/admin.min.css" as="style" onload="this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="/assets/css/admin.min.css"></noscript>
</head>
<body>
    <div class="admin-shell">
        <?php require_once __DIR__ . '/../_nav.php'; ?>

        <div class="main">
            <?php $pageTitle = 'Créer un article'; require_once __DIR__ . '/../_header.php'; ?>

            <main class="container">
                <section class="card">
                    <h2>Créer un article</h2>
                    <form method="post" enctype="multipart/form-data" novalidate>
                        <?php echo csrf_field(); ?>
                        <label for="title">Titre</label>
                        <input id="title" name="title" type="text" required value="<?php echo isset($title) ? e($title) : ''; ?>">

                        <label for="content">Contenu</label>
                        <textarea id="content" name="content" rows="8" required><?php echo isset($content) ? e($content) : ''; ?></textarea>

                        <label for="category">Catégorie</label>
                        <select id="category" name="category_id">
                            <option value="0">-- Aucune --</option>
                            <?php foreach ($categories as $c): ?>
                                <option value="<?php echo $c['id']; ?>"><?php echo e($c['name']); ?></option>
                            <?php endforeach; ?>
                        </select>

                        <label for="tags">Tags (Ctrl / Cmd pour multi)</label>
                        <select id="tags" name="tags[]" multiple size="6">
                            <?php foreach ($allTags as $t): ?>
                                <option value="<?php echo $t['id']; ?>"><?php echo e($t['name']); ?></option>
                            <?php endforeach; ?>
                        </select>

                        <label for="image">Image (jpg, png, webp — max 2MB)</label>
                        <input id="image" name="image" type="file" accept="image/*">

                        <button class="btn" type="submit">Enregistrer</button>
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
