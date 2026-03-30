<?php
require_once '../../includes/db.php';
require_once '../../includes/functions.php';

requireAuth();

$user = isset($_SESSION['user']) ? $_SESSION['user'] : ['username' => 'Utilisateur'];

$stmt = $pdo->query("SELECT * FROM categories ORDER BY id DESC");
$categories = $stmt->fetchAll();
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Catégories — Admin</title>
    <link rel="preload" href="/assets/css/admin.min.css" as="style" onload="this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="/assets/css/admin.min.css"></noscript>
</head>
<body>
    <div class="admin-shell">
        <?php require_once __DIR__ . '/../_nav.php'; ?>

        <div class="main">
            <header class="topbar">
                <div class="left"><div class="page-title">Catégories</div></div>
                <div class="actions">
                    <div class="user">Bonjour, <?php echo e($user['username']); ?></div>
                    <a class="logout" href="../dashboard.php">Retour</a>
                </div>
            </header>

            <main class="container">
                <section class="card">
                    <h2>Catégories</h2>
                    <a class="quick-link" href="create.php">+ Ajouter une catégorie</a>

                    <?php if (empty($categories)): ?>
                        <p class="small">Aucune catégorie pour le moment.</p>
                    <?php else: ?>
                        <ul style="margin-top:12px;list-style:none;padding:0;">
                            <?php foreach ($categories as $cat): ?>
                                <li style="padding:8px 0;border-bottom:1px solid var(--input-border);display:flex;justify-content:space-between;align-items:center">
                                    <div>
                                        <strong><?php echo e($cat['name']); ?></strong>
                                        <div style="color:var(--muted);font-size:.9rem"><?php echo e($cat['slug']); ?></div>
                                    </div>
                                    <div style="display:flex;gap:8px;align-items:center">
                                        <a class="btn btn-ghost btn-sm" href="edit.php?id=<?php echo $cat['id']; ?>">Éditer</a>
                                        <form method="post" action="delete.php" onsubmit="return confirm('Supprimer cette catégorie ?');" style="display:inline">
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="id" value="<?php echo $cat['id']; ?>">
                                            <button type="submit" class="btn btn-ghost btn-danger">Supprimer</button>
                                        </form>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </section>
            </main>
        </div>
    </div>
</body>
</html>
