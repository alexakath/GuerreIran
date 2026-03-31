<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/db.php';

// Récupérer catégories pour le menu
try {
    $catStmt = $pdo->query("SELECT id,name,slug FROM categories ORDER BY name ASC");
    $headerCategories = $catStmt->fetchAll();
} catch (Exception $e) {
    $headerCategories = [];
}
?>
<header class="site-header" role="banner">
    <div class="wrap header-inner">
        <div class="left">
            <button id="sidebarToggle" class="menu-btn" aria-label="Ouvrir le menu">☰</button>
            <h1 class="site-title"><a href="/"><?php echo e(SITE_NAME); ?></a></h1>
        </div>

        <div class="center nav-cats">
            <a class="nav-link" href="/">Articles</a>
            <?php foreach ($headerCategories as $hc): ?>
                <a class="nav-link" href="/public/categorie.php?slug=<?php echo e($hc['slug']); ?>"><?php echo e($hc['name']); ?></a>
            <?php endforeach; ?>
        </div>

        <div class="right">
            <button id="searchToggle" class="icon-btn" aria-label="Rechercher">🔍</button>
            <a class="icon-btn" href="/admin/login.php" aria-label="Admin">🔐</a>
        </div>
    </div>
</header>
