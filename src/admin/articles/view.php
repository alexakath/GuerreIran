<?php
require_once '../../includes/db.php';
require_once '../../includes/functions.php';

requireAuth();
send_security_headers();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) redirect('list.php');

$stmt = $pdo->prepare("SELECT a.*, c.name AS category_name FROM articles a LEFT JOIN categories c ON a.category_id = c.id WHERE a.id = ?");
$stmt->execute([$id]);
$article = $stmt->fetch();
if (!$article) redirect('list.php');

$tagStmt = $pdo->prepare("SELECT t.* FROM tags t JOIN article_tags at ON at.tag_id = t.id WHERE at.article_id = ? ORDER BY t.name ASC");
$tagStmt->execute([$id]);
$tags = $tagStmt->fetchAll();

?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Détail article — Admin</title>
    <link rel="preload" href="/assets/css/admin.min.css" as="style" onload="this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="/assets/css/admin.min.css"></noscript>
</head>
<body>
    <div class="admin-shell">
        <?php require_once __DIR__ . '/../_nav.php'; ?>

        <div class="main">
            <?php $pageTitle = 'Détail article'; require_once __DIR__ . '/../_header.php'; ?>

            <main class="container">
                <article class="article-detail">
                    <header class="detail-header">
                        <div class="detail-main">
                            <h1><?php echo e($article['title']); ?></h1>
                            <div class="slug"><?php echo e($article['slug']); ?></div>
                            <div style="margin-top:8px;color:var(--muted)">Catégorie: <strong><?php echo e($article['category_name'] ?? '—'); ?></strong></div>
                            <div class="chips" style="margin-top:10px">
                                <?php foreach ($tags as $t): ?>
                                    <span class="chip"><?php echo e($t['name']); ?></span>
                                <?php endforeach; ?>
                            </div>
                            <div style="color:var(--muted);margin-top:12px">Publié: <?php echo e($article['created_at']); ?></div>

                            <div style="margin-top:14px;display:flex;gap:12px;align-items:center">
                                <a class="btn" href="edit.php?id=<?php echo $article['id']; ?>">Éditer</a>
                                <form method="post" action="delete.php" onsubmit="return confirm('Supprimer cet article ?');" style="display:inline">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="id" value="<?php echo $article['id']; ?>">
                                    <button type="submit" class="btn btn-ghost btn-danger">Supprimer</button>
                                </form>
                                <a class="quick-link" href="list.php">← Retour à la liste</a>
                            </div>
                        </div>

                        <aside class="detail-aside">
                            <?php if (!empty($article['image_url'])): ?>
                                <img src="../../<?php echo e($article['image_url']); ?>" alt="">
                            <?php else: ?>
                                <div style="padding:40px;text-align:center;color:var(--muted)">Pas d'image</div>
                            <?php endif; ?>
                        </aside>
                    </div>

                    <div class="article-content">
                        <?php echo $article['content']; ?>
                    </div>
                </section>

                <?php require_once __DIR__ . '/../_footer.php'; ?>
            </main>
        </div>
    </div>
</body>
</html>
