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
				<h1 class="visually-hidden">Tableau de bord administrateur</h1>

				<section class="card latest-card" aria-labelledby="latest-title" <?php if ($latestArticle): ?> onclick="window.location.href='articles/view.php?id=<?php echo (int)$latestArticle['id']; ?>';" style="cursor:pointer" <?php endif; ?>>
					<div class="latest-body">
						<h2 id="latest-title">Dernier article</h2>
						<?php if (!$latestArticle): ?>
							<p class="small">Aucun article publié pour le moment.</p>
						<?php else: ?>
							<h3 style="margin-top:6px"><?php echo e($latestArticle['title']); ?></h3>
							<div class="slug"><?php echo e($latestArticle['slug']); ?></div>
							<div class="excerpt" style="margin-top:10px">
								<?php
									$text = strip_tags($latestArticle['content']);
									$excerpt = mb_strlen($text) > 380 ? mb_substr($text,0,380) . '…' : $text;
									echo e($excerpt);
								?>
							</div>

							<div class="meta">
								<div>Catégorie: <strong><?php echo e($latestArticle['category_name'] ?? '—'); ?></strong></div>
								<div class="chips">
									<?php foreach ($latestArticleTags as $t): ?>
										<span class="chip"><?php echo e($t['name']); ?></span>
									<?php endforeach; ?>
								</div>
							</div>

							<div style="margin-top:14px">
								<a class="btn btn-ghost btn-sm" href="articles/edit.php?id=<?php echo (int)$latestArticle['id']; ?>" onclick="event.stopPropagation();">Éditer l'article</a>
						
							</div>
						<?php endif; ?>
					</div>
					<div class="latest-thumb">
						<?php if ($latestArticle && !empty($latestArticle['image_url'])): ?>
							<img src="../<?php echo e($latestArticle['image_url']); ?>" alt="">
						<?php else: ?>
							<div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;color:var(--muted)">Aucune image</div>
						<?php endif; ?>
					</div>
				</section>

				<section class="stat-grid" aria-label="Statistiques rapides">
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

				<?php if (!empty($recentArticles)): ?>
					<section class="card" aria-label="Derniers articles">
						<h3>Derniers articles</h3>
						<div class="article-grid">
							<?php foreach ($recentArticles as $ra): ?>
								<article class="article-card" onclick="window.location.href='articles/view.php?id=<?php echo (int)$ra['id']; ?>';" style="cursor:pointer">
									<?php if (!empty($ra['image_url'])): ?>
										<div class="thumb"><img src="../<?php echo e($ra['image_url']); ?>" alt=""></div>
									<?php else: ?>
										<div class="thumb" style="display:flex;align-items:center;justify-content:center;color:var(--muted)">Pas d'image</div>
									<?php endif; ?>
									<div class="body">
										<h4 style="margin:0"><?php echo e($ra['title']); ?></h4>
										<div class="slug"><?php echo e($ra['slug']); ?></div>
										<div style="margin-top:8px;color:var(--muted);font-size:.95rem"><?php echo e($ra['category_name'] ?? '—'); ?></div>
										<div style="margin-top:10px;color:var(--muted);font-size:.9rem"><?php echo e($ra['created_at']); ?></div>
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