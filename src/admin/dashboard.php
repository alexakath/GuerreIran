<?php
require_once '../includes/functions.php';

requireAuth();

$user = isset($_SESSION['user']) ? $_SESSION['user'] : ['username' => 'Utilisateur'];
?>
<!doctype html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Tableau de bord — Admin</title>
	<link rel="preload" href="../assets/css/admin.css" as="style">
	<link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
	<header class="header" role="banner">
		<div class="brand">
			<div class="logo" aria-hidden="true"></div>
			<div>
				<strong>Administration</strong>
				<div class="subtitle" style="color:var(--muted);font-size:.9rem">Espace sécurisé</div>
			</div>
		</div>
		<div class="actions">
			<div class="user">Bonjour, <?php echo e($user['username']); ?></div>
			<a class="logout" href="logout.php">Se déconnecter</a>
		</div>
	</header>

	<main class="container" role="main">
		<h1 class="visually-hidden">Tableau de bord administrateur</h1>

		<section class="welcome card" aria-labelledby="welcome-title">
			<div>
				<h2 id="welcome-title">Bienvenue, <?php echo e($user['username']); ?></h2>
				<p>Seuls les administrateurs ont accès à cet espace.</p>
			</div>
			<div style="text-align:right;color:var(--muted)">Rôle: <?php echo e(isset($user['role']) ? $user['role'] : '—'); ?></div>
		</section>

		<section class="grid" aria-label="Actions rapides">
			<article class="card">
				<h3>Utilisateurs</h3>
				<p>Gérer les comptes et permissions.</p>
				<a class="quick-link" href="users.php">Gérer</a>
			</article>

			<article class="card">
				<h3>Contenu</h3>
				<p>Articles, pages et médias.</p>
				<a class="quick-link" href="../index.php">Voir le site</a>
			</article>

			<article class="card">
				<h3>Paramètres</h3>
				<p>Réglages du site et options.</p>
				<a class="quick-link" href="#">Ouvrir</a>
			</article>
		</section>

		<p class="small">© <?php echo date('Y'); ?> Mon site — Zone admin</p>
	</main>
</body>
</html>