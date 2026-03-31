<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/db.php';

$slug = isset($_GET['slug']) ? $_GET['slug'] : null;

// Récupérer l'article par slug
$article = null;
if ($slug) {
    try {
        $stmt = $pdo->prepare("SELECT a.*, c.name as category_name FROM articles a JOIN categories c ON a.category_id = c.id WHERE a.slug = :slug LIMIT 1");
        $stmt->execute(['slug' => $slug]);
        $article = $stmt->fetch();
    } catch (Exception $e) {
        $article = null;
    }
}

?><!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?php echo e(SITE_NAME); ?> — Article</title>
    <link rel="stylesheet" href="<?php echo asset_url('assets/css/style.css'); ?>">
</head>
<body>
    <?php include __DIR__ . '/_header.php'; ?>
    <?php include __DIR__ . '/_sidebar.php'; ?>
    <main class="wrap">
        <?php if (!$slug || !$article): ?>
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
    </main>
    <?php include __DIR__ . '/_footer.php'; ?>
</body>
</html>
