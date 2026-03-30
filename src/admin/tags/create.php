<?php
require_once '../../includes/db.php';
require_once '../../includes/functions.php';

requireAuth();

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');

    if ($name === '') {
        $error = 'Le nom est requis.';
    } else {
        $slug = slugify($name);

        $stmt = $pdo->prepare("INSERT INTO tags (name, slug) VALUES (?, ?)");
        $stmt->execute([$name, $slug]);

        redirect('list.php');
    }
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Créer tag — Admin</title>
    <link rel="stylesheet" href="../../assets/css/admin.css">
</head>
<body>
    <main class="container">
        <section class="card">
            <h2>Créer un tag</h2>
            <form method="post" novalidate>
                <label for="name">Nom</label>
                <input id="name" name="name" type="text" required placeholder="Nom du tag">
                <button class="btn" type="submit">Enregistrer</button>
            </form>
            <?php if ($error): ?>
                <div class="error"><?php echo e($error); ?></div>
            <?php endif; ?>
            <p class="small"><a href="list.php">← Retour à la liste</a></p>
        </section>
    </main>
</body>
</html>
