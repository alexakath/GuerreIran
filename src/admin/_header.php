<?php
// Admin header partial — expects $pageTitle, $user and $base from _nav.php
if (!isset($user)) $user = isset($_SESSION['user']) ? $_SESSION['user'] : ['username' => 'Utilisateur'];
$adminBase = isset($base) ? $base : dirname($_SERVER['SCRIPT_NAME']);
?>
<header class="topbar" role="banner">
    <div class="left"><div class="page-title"><?php echo e($pageTitle ?? 'Administration'); ?></div></div>
    <div class="actions">
        <div class="user">Bonjour, <?php echo e($user['username']); ?></div>
        <a class="logout" href="<?php echo $adminBase; ?>/logout.php">Se déconnecter</a>
    </div>
</header>
