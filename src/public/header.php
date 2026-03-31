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
<nav class="navbar" role="navigation" aria-label="Navigation principale">
    <div class="nav-content container">
        <a class="brand" href="/">NewsIran</a>
        <div class="nav-actions">
            <button id="searchToggle" class="btn btn-ghost" aria-label="Recherche par tag">🔍</button>
            <a class="btn btn-outline" href="/admin/login.php">Se connecter</a>
        </div>
        <button id="sidebarToggle" class="hamburger" aria-label="Basculer la navigation" aria-expanded="false" aria-controls="sidebar">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>
    <div class="nav-categories container" role="navigation" aria-label="Catégories">
        <ul class="nav-list">
            <li class="nav-item nav-menu">
                <button id="navMenuToggle" class="btn btn-ghost" aria-label="Ouvrir le menu" onclick="document.getElementById('sidebarToggle').click()">☰</button>
            </li>
            <li class="nav-item"><a href="/">Home</a></li>
            <li class="nav-item"><a href="/article/">Articles |</a></li>
            <?php foreach ($headerCategories as $hc): ?>
                <li class="nav-item"><a href="/categorie/<?php echo e($hc['slug']); ?>"><?php echo e($hc['name']); ?></a></li>
            <?php endforeach; ?>
        </ul>
    </div>
</nav>
