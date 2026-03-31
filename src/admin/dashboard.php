<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

requireAuth();
send_security_headers();

$user = isset($_SESSION['user']) ? $_SESSION['user'] : ['username' => 'Utilisateur'];

// Compteurs pour dashboard (tolérance si les tables n'existent pas encore)
$catCount = 0;
$tagCount = 0;
$articleCount = 0;
$latestArticle = null;
$latestArticleTags = [];
try {
	$catCount = (int) $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
} catch (PDOException $e) {
	$catCount = 0;
}
try {
	$tagCount = (int) $pdo->query("SELECT COUNT(*) FROM tags")->fetchColumn();
} catch (PDOException $e) {
	$tagCount = 0;
}
try {
	$articleCount = (int) $pdo->query("SELECT COUNT(*) FROM articles")->fetchColumn();
} catch (Exception $e) {
	$articleCount = 0;
}

// latest article
try {
	$stmt = $pdo->query("SELECT a.*, c.name AS category_name FROM articles a LEFT JOIN categories c ON a.category_id = c.id ORDER BY a.created_at DESC LIMIT 1");
	$latestArticle = $stmt->fetch();
	if ($latestArticle) {
		$tstmt = $pdo->prepare("SELECT t.id, t.name FROM article_tags at JOIN tags t ON t.id = at.tag_id WHERE at.article_id = ? ORDER BY t.name ASC");
		$tstmt->execute([(int)$latestArticle['id']]);
		$latestArticleTags = $tstmt->fetchAll();
	}
} catch (Exception $e) {
	$latestArticle = null;
	$latestArticleTags = [];
}

// recent articles (skip latest)
$recentArticles = [];
try {
	$rstmt = $pdo->query("SELECT a.*, c.name AS category_name FROM articles a LEFT JOIN categories c ON a.category_id = c.id ORDER BY a.created_at DESC LIMIT 4 OFFSET 1");
	$recentArticles = $rstmt->fetchAll();
} catch (Exception $e) {
	$recentArticles = [];
}
?>
<!doctype html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Tableau de bord — Admin</title>
	<link rel="preload" href="/assets/css/admin.min.css" as="style" onload="this.rel='stylesheet'">
	<noscript><link rel="stylesheet" href="/assets/css/admin.min.css"></noscript>
</head>
<body>
	<div class="admin-shell">
		<?php require_once __DIR__ . '/_nav.php'; ?>

		<div class="main">
			<?php $pageTitle = 'Tableau de bord'; require_once __DIR__ . '/_header.php'; ?>

			<main class="container" role="main">

    <!-- STATS -->
    <section class="stat-grid">
        <div class="stat-card">
            <div class="value"><?php echo (int)$articleCount; ?></div>
            <div class="label">Articles</div>
        </div>

        <div class="stat-card">
            <div class="value"><?php echo (int)$catCount; ?></div>
            <div class="label">Catégories</div>
        </div>

        <div class="stat-card">
            <div class="value"><?php echo (int)$tagCount; ?></div>
            <div class="label">Tags</div>
        </div>

        <div class="stat-card">
            <div class="value"><?php echo date('Y'); ?></div>
            <div class="label">Année</div>
        </div>
    </section>

    <!-- DERNIER ARTICLE -->
    <section style="margin-top:20px;">
        <div class="page-header">
            <div class="title">Dernier article</div>
        </div>

        <?php if (!$latestArticle): ?>
            <p class="small">Aucun article publié.</p>
        <?php else: ?>
            <article class="article-card" onclick="window.location.href='articles/view.php?id=<?php echo (int)$latestArticle['id']; ?>'">

                <div class="thumb">
                    <?php if (!empty($latestArticle['image_url'])): ?>
                        <img src="../<?php echo e($latestArticle['image_url']); ?>" alt="">
                    <?php endif; ?>
                </div>

                <div class="article-body">
                    <h3><?php echo e($latestArticle['title']); ?></h3>
                    <div class="slug"><?php echo e($latestArticle['slug']); ?></div>

                    <div class="meta">
                        <?php echo e($latestArticle['category_name'] ?? '—'); ?>
                    </div>

                    <div class="chips">
                        <?php foreach ($latestArticleTags as $t): ?>
                            <span class="chip"><?php echo e($t['name']); ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="actions">
                    <a class="btn btn-ghost" href="articles/edit.php?id=<?php echo (int)$latestArticle['id']; ?>" onclick="event.stopPropagation()">Éditer</a>
                </div>

            </article>
        <?php endif; ?>
    </section>

    <!-- ARTICLES RÉCENTS -->
    <?php if (!empty($recentArticles)): ?>
        <section style="margin-top:20px;">
            <div class="page-header">
                <div class="title">Articles récents</div>
            </div>

            <div class="article-grid">
                <?php foreach ($recentArticles as $ra): ?>
                    <article class="article-card" onclick="window.location.href='articles/view.php?id=<?php echo (int)$ra['id']; ?>'">

                        <div class="thumb">
                            <?php if (!empty($ra['image_url'])): ?>
                                <img src="../<?php echo e($ra['image_url']); ?>" alt="">
                            <?php endif; ?>
                        </div>

                        <div class="article-body">
                            <h3><?php echo e($ra['title']); ?></h3>
                            <div class="slug"><?php echo e($ra['slug']); ?></div>

                            <div class="meta">
                                <?php echo e($ra['category_name'] ?? '—'); ?>
                            </div>

                            <div class="meta">
                                <?php echo e($ra['created_at']); ?>
                            </div>
                        </div>

                        <div class="actions">
                            <a class="btn btn-ghost" href="articles/edit.php?id=<?php echo (int)$ra['id']; ?>" onclick="event.stopPropagation()">Éditer</a>
                        </div>

                    </article>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>

    <?php require_once __DIR__ . '/_footer.php'; ?>

</main>
		</div>
	</div>
</body>
</html>