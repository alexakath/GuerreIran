<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/db.php';

$slug = isset($_GET['slug']) ? $_GET['slug'] : null;

// Récupérer l'article par slug via helper
$article = null;
if ($slug) {
    $article = find_article_by_slug($pdo, $slug);
}

?><!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?php echo e(SITE_NAME); ?> — Article</title>
    <link rel="stylesheet" href="<?php echo asset_url('assets/css/home.css'); ?>">
</head>
<body>
    <?php include __DIR__ . '/header.php'; ?>

    <main class="container articles-page">
        <?php if (!$slug):
            // Afficher la liste des derniers articles (styled like articletsizy)
            try {
                $listStmt = $pdo->query("SELECT a.slug,a.title,a.created_at,c.name as category_name,LEFT(a.content,250) as excerpt FROM articles a JOIN categories c ON a.category_id = c.id ORDER BY a.created_at DESC LIMIT 12");
                $articlesList = $listStmt->fetchAll();
            } catch (Exception $e) {
                $articlesList = [];
            }
        ?>
            <h1>Articles</h1>
            <div class="articles-list">
                <?php foreach ($articlesList as $it): ?>
                    <article class="article-card">
                        <div class="article-thumb"></div>
                        <div class="article-body">
                            <h3><a href="<?php echo article_url($it['slug']); ?>"><?php echo e($it['title']); ?></a></h3>
                            <div class="meta"><?php echo e($it['category_name']); ?> • <?php echo date('d/m/Y', strtotime($it['created_at'])); ?></div>
                            <p><?php echo e(mb_substr(strip_tags($it['excerpt']), 0, 250)); ?>…</p>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <?php if (!$article): ?>
                <h2>Article non trouvé</h2>
                <p>Paramètre manquant ou article introuvable. Utilisez <code>?slug=mon-article</code>.</p>
            <?php else: ?>
                <article class="article-detail">
                    <h1><?php echo e($article['title']); ?></h1>
                    <div class="meta"><small class="muted"><?php echo e($article['category_name']); ?> — <?php echo date('d/m/Y', strtotime($article['created_at'])); ?></small></div>
                    <div class="content">
                        <?php echo $article['content']; ?>
                    </div>
                </article>
            <?php endif; ?>
        <?php endif; ?>
    </main>

    <?php include __DIR__ . '/footer.php'; ?>
</body>
</html>
