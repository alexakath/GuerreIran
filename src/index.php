<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/db.php';

?><!doctype html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title><?php echo e(SITE_NAME); ?> — Accueil</title>
	<link rel="stylesheet" href="<?php echo asset_url('assets/css/style.css'); ?>">
</head>
<body>
	<?php include __DIR__ . '/public/_header.php'; ?>
	<?php include __DIR__ . '/public/_sidebar.php'; ?>

	<main class="wrap">
		<h2>Derniers articles</h2>
		<?php
		try {
			$stmt = $pdo->prepare("SELECT a.id,a.title,a.slug,a.content,a.image_url,a.created_at,c.name as category_name FROM articles a JOIN categories c ON a.category_id = c.id ORDER BY a.created_at DESC LIMIT 10");
			$stmt->execute();
			$articles = $stmt->fetchAll();
		} catch (Exception $e) {
			$articles = [];
		}
		?>

		<section class="articles-list">
			<?php if (empty($articles)): ?>
				<p class="muted">Aucun article disponible pour le moment.</p>
			<?php else: ?>
				<?php foreach ($articles as $a): ?>
					<article class="teaser">
						<h3><a href="/public/article.php?slug=<?php echo e($a['slug']); ?>"><?php echo e($a['title']); ?></a></h3>
						<div class="meta"><small class="muted"><?php echo e($a['category_name']); ?> — <?php echo date('d/m/Y', strtotime($a['created_at'])); ?></small></div>
						<p class="excerpt"><?php echo e(mb_strimwidth(strip_tags($a['content']), 0, 220, '...')); ?></p>
					</article>
				<?php endforeach; ?>
			<?php endif; ?>
		</section>
	</main>

	<?php include __DIR__ . '/public/_footer.php'; ?>
</body>
</html>
