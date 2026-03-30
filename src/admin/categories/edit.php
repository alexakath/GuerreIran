<?php
require_once '../../includes/db.php';
require_once '../../includes/functions.php';

requireAuth();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    redirect('list.php');
}

$stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
$stmt->execute([$id]);
$category = $stmt->fetch();

if (!$category) {
    redirect('list.php');
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    if ($name === '') {
        $error = 'Le nom est requis.';
    } else {
        $slug = slugify($name);
        $stmt = $pdo->prepare("UPDATE categories SET name = ?, slug = ? WHERE id = ?");
        $stmt->execute([$name, $slug, $id]);
        redirect('list.php');
    }
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Éditer catégorie — Admin</title>
    <link rel="stylesheet" href="../../assets/css/admin.css">
</head>
<body>
    <main class="container">
        <section class="card">
            <h2>Éditer la catégorie</h2>
            <form method="post" novalidate>
                <label for="name">Nom</label>
                <input id="name" name="name" type="text" required value="<?php echo e($category['name']); ?>">
                <button class="btn" type="submit">Mettre à jour</button>
            </form>
            <?php if ($error): ?>
                <div class="error"><?php echo e($error); ?></div>
            <?php endif; ?>
            <p class="small"><a href="list.php">← Retour à la liste</a></p>
        </section>
    </main>
</body>
</html>
