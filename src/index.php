<!doctype html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>NewsIran - Accueil</title>
	<link rel="stylesheet" href="assets/css/home.css">
</head>
<body class="no-hamburger">

	<?php include __DIR__ . '/public/header.php'; ?>
	<?php include __DIR__ . '/public/sidebar.php'; ?>

	<main class="hero">
		<div class="container hero-inner">
			<div class="hero-grid">
				<div class="hero-copy">
					<h1>Bienvenue sur NewsIran</h1>
					<p class="lead">Analyses, reportages et dossiers sur l'actualité iranienne — clairs, vérifiés et indépendants.</p>
					<p class="about">Nous publions des articles, enquêtes et analyses pour aider nos lecteurs à comprendre les enjeux régionaux et internationaux.</p>
					<div class="hero-cta">
						<a class="btn btn-primary" href="/public/article.php">Voir les articles</a>
						<a class="btn btn-ghost" href="#about">En savoir plus</a>
					</div>
				</div>
				<div class="hero-visual" aria-hidden="true">
					<div class="sample-card">
						<div class="sample-thumb"></div>
						<div class="sample-content">
							<div class="sample-title">Dernières analyses sur la région</div>
							<div class="sample-meta">NewsIran • 2 mars 2026</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</main>

	<section class="features container">
		<div class="feature">
			<div class="icon">📰</div>
			<h3>Reportages</h3>
			<p>Enquêtes et récits du terrain.</p>
		</div>
		<div class="feature">
			<div class="icon">🔍</div>
			<h3>Analyses</h3>
			<p>Contexte et décryptage d'experts.</p>
		</div>
		<div class="feature">
			<div class="icon">📚</div>
			<h3>Dossiers</h3>
			<p>Collections d'articles thématiques.</p>
		</div>
	</section>

	<section id="about" class="about-section container">
		<h2>À propos</h2>
		<p>NewsIran a pour mission de fournir des articles de qualité, des analyses approfondies et des ressources fiables sur l'actualité iranienne et la région. Notre rédaction privilégie l'indépendance et la vérification des sources.</p>
	</section>

	<?php include __DIR__ . '/public/footer.php'; ?>

</body>
</html>
