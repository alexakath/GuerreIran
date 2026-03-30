<?php
require_once '../../includes/db.php';
require_once '../../includes/functions.php';

requireAuth();

$stmt = $pdo->query("SELECT * FROM categories ORDER BY id DESC");
$categories = $stmt->fetchAll();
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Catégories — Admin</title>
    <link rel="stylesheet" href="../../assets/css/admin.css">
    <link rel="preload" href="../../assets/css/admin.css" as="style">
</head>
<body>
    <header class="header">
        <div class="brand">
            <div class="logo" aria-hidden="true"></div>
            <div>
                <strong>Administration</strong>
                <div class="subtitle" style="color:var(--muted);font-size:.9rem">Gestion des catégories</div>
            </div>
        </div>
        <div class="actions">
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
                                <a href="edit.php?id=<?php echo $cat['id']; ?>">Éditer</a>
                                <a href="delete.php?id=<?php echo $cat['id']; ?>" onclick="return confirm('Supprimer cette catégorie ?');" style="color:#7a1f1f">Supprimer</a>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
