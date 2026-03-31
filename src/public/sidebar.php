<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/db.php';

// categories
try {
    $stmt = $pdo->query("SELECT id,name,slug FROM categories ORDER BY name ASC");
    $sidebarCategories = $stmt->fetchAll();
} catch (Exception $e) {
    $sidebarCategories = [];
}
?>
<aside id="sidebar" class="sidebar open" role="navigation" aria-hidden="false">
    <div class="sidebar-inner">
        <button id="sidebarClose" class="close-btn" aria-label="Fermer le menu">×</button>
        <div class="brand">
            <h2 class="site-title-small"><a href="/">NewsIran</a></h2>
        </div>

        <nav class="sidebar-nav">
            <a class="nav-item" href="/">Home</a>
            <a class="nav-item" href="/public/article.php">Articles</a>
            <?php foreach ($sidebarCategories as $sc): ?>
                <a class="nav-item" href="/public/categorie.php?slug=<?php echo e($sc['slug']); ?>"><?php echo e($sc['name']); ?></a>
            <?php endforeach; ?>
        </nav>

        <div class="sidebar-search">
            <form action="/public/article.php" method="get">
                <input id="s" name="q" placeholder="Rechercher par tag ou titre..." autocomplete="off">
                <button type="submit">🔍</button>
            </form>
        </div>
    </div>
</aside>

<div id="sidebarOverlay" class="sidebar-overlay show" aria-hidden="false"></div>
