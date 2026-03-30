<?php
require_once '../../includes/db.php';
require_once '../../includes/functions.php';

requireAuth();

$stmt = $pdo->query("SELECT * FROM tags ORDER BY id DESC");
$tags = $stmt->fetchAll();
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tags — Admin</title>
    <link rel="stylesheet" href="../../assets/css/admin.css">
    <link rel="preload" href="../../assets/css/admin.css" as="style">
</head>
<body>
    <header class="header">
        <div class="brand">
            <div class="logo" aria-hidden="true"></div>
            <div>
                <strong>Administration</strong>
                <div class="subtitle" style="color:var(--muted);font-size:.9rem">Gestion des tags</div>
            </div>
        </div>
        <div class="actions">
            <a class="logout" href="../dashboard.php">Retour</a>
        </div>
    </header>

    <main class="container">
        <section class="card">
            <h2>Tags</h2>
            <a class="quick-link" href="create.php">+ Ajouter un tag</a>

            <?php if (empty($tags)): ?>
                <p class="small">Aucun tag pour le moment.</p>
            <?php else: ?>
                <ul style="margin-top:12px;list-style:none;padding:0;">
                    <?php foreach ($tags as $tag): ?>
                        <li style="padding:8px 0;border-bottom:1px solid var(--input-border);display:flex;justify-content:space-between;align-items:center">
                            <div>
                                <strong><?php echo e($tag['name']); ?></strong>
                                <div style="color:var(--muted);font-size:.9rem"><?php echo e($tag['slug']); ?></div>
                            </div>
                            <div style="display:flex;gap:8px;align-items:center">
                                <a href="edit.php?id=<?php echo $tag['id']; ?>">Éditer</a>
                                <a href="delete.php?id=<?php echo $tag['id']; ?>" onclick="return confirm('Supprimer ce tag ?');" style="color:#7a1f1f">Supprimer</a>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
