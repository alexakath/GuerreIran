<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/db.php';

$slug = isset($_GET['slug']) ? $_GET['slug'] : null;

// Si slug fourni, lister les articles de la catégorie
$category = null;
$articles = [];
if ($slug) {
    try {
        $stmt = $pdo->prepare("SELECT id,name,slug FROM categories WHERE slug = :slug LIMIT 1");
        $stmt->execute(['slug' => $slug]);
        $category = $stmt->fetch();
        if ($category) {
            $stmt2 = $pdo->prepare("SELECT a.title,a.slug,a.created_at, LEFT(a.content,250) as excerpt FROM articles a WHERE a.category_id = :cat ORDER BY a.created_at DESC");
            $stmt2->execute(['cat' => $category['id']]);
            $articles = $stmt2->fetchAll();
        }
    } catch (Exception $e) {
        $category = null;
        $articles = [];
    }
}
else {
    // liste des catégories
    try {
        $stmt = $pdo->query("SELECT id,name,slug FROM categories ORDER BY name ASC");
        $categories = $stmt->fetchAll();
    } catch (Exception $e) {
        $categories = [];
    }
}

?><!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?php echo e(SITE_NAME); ?> — Catégorie</title>
    <link rel="stylesheet" href="<?php echo asset_url('assets/css/home.css'); ?>">
</head>
<body>
    <?php include __DIR__ . '/header.php'; ?>
    <?php include __DIR__ . '/sidebar.php'; ?>
    <main class="container articles-page">
        <?php if ($slug): ?>
            <?php if (!$category): ?>
                <h2>Catégorie introuvable</h2>
            <?php else: ?>
                <h2>Catégorie: <?php echo e($category['name']); ?></h2>
                <?php if (empty($articles)): ?>
                    <p class="muted">Aucun article dans cette catégorie.</p>
                <?php else: ?>
                    <div class="articles-list">
                        <?php foreach ($articles as $it): ?>
                            <article class="article-card">
                                <div class="article-thumb"></div>
                                <div class="article-body">
                                    <h3><a href="<?php echo '/public/article.php?slug=' . e($it['slug']); ?>"><?php echo e($it['title']); ?></a></h3>
                                    <div class="meta"><?php echo e($category['name']); ?> • <?php echo date('d/m/Y', strtotime($it['created_at'])); ?></div>
                                    <p><?php echo e(mb_substr(strip_tags($it['excerpt'] ?? ''), 0, 250)); ?>…</p>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        <?php else: ?>
            <h2>Toutes les catégories</h2>
            <?php if (empty($categories)): ?>
                <p class="muted">Aucune catégorie définie.</p>
            <?php else: ?>
                <ul>
                    <?php foreach ($categories as $cat): ?>
                        <li><a href="/public/categorie.php?slug=<?php echo e($cat['slug']); ?>"><?php echo e($cat['name']); ?></a></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        <?php endif; ?>
    </main>
    <?php include __DIR__ . '/footer.php'; ?>
</body>
</html>
