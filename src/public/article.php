<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/db.php';

$slug = isset($_GET['slug']) ? $_GET['slug'] : null;
// search query
$q = isset($_GET['q']) ? trim($_GET['q']) : null;

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
    <link rel="stylesheet" href="<?php echo asset_url('assets/css/home.css'); ?>">
</head>
<body>
    <?php include __DIR__ . '/header.php'; ?>
    <?php include __DIR__ . '/sidebar.php'; ?>

    <main class="container articles-page">
        <?php if (!$slug):
            // If a search query is provided, filter by title/content/tags
            if ($q) {
                $searchParam = '%' . $q . '%';
                try {
                    $stmt = $pdo->prepare("SELECT DISTINCT a.slug,a.title,a.created_at,c.name as category_name,LEFT(a.content,250) as excerpt
                        FROM articles a
                        JOIN categories c ON a.category_id = c.id
                        LEFT JOIN article_tags at ON a.id = at.article_id
                        LEFT JOIN tags t ON at.tag_id = t.id
                        WHERE a.title LIKE :q OR a.content LIKE :q OR t.name LIKE :q
                        ORDER BY a.created_at DESC
                        LIMIT 50");
                    $stmt->execute(['q' => $searchParam]);
                    $articlesList = $stmt->fetchAll();
                } catch (Exception $e) {
                    $articlesList = [];
                }
            } else {
                // Afficher la liste des derniers articles (styled like articletsizy)
                try {
                    $listStmt = $pdo->query("SELECT a.slug,a.title,a.created_at,c.name as category_name,LEFT(a.content,250) as excerpt FROM articles a JOIN categories c ON a.category_id = c.id ORDER BY a.created_at DESC LIMIT 12");
                    $articlesList = $listStmt->fetchAll();
                } catch (Exception $e) {
                    $articlesList = [];
                }
            }
        ?>
            <h1><?php echo $q ? 'Résultats pour: ' . e($q) : 'Articles'; ?></h1>
            <div class="articles-list">
                <?php foreach ($articlesList as $it): ?>
                    <article class="article-card">
                        <div class="article-thumb"></div>
                        <div class="article-body">
                            <h3><a href="<?php echo '/public/article.php?slug=' . e($it['slug']); ?>"><?php echo e($it['title']); ?></a></h3>
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
