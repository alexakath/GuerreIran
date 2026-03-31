<?php
require_once '../../includes/db.php';
require_once '../../includes/functions.php';

requireAuth();

$user = isset($_SESSION['user']) ? $_SESSION['user'] : ['username' => 'Utilisateur'];
send_security_headers();

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (!verify_csrf($token)) {
        $error = 'Requête invalide (CSRF).';
    } else {
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
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Créer tag — Admin</title>
    <link rel="preload" href="/assets/css/admin.min.css" as="style" onload="this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="/assets/css/admin.min.css"></noscript>
</head>
<body>
    <div class="admin-shell">
        <?php require_once __DIR__ . '/../_nav.php'; ?>

        <div class="main">
            <header class="topbar">
                <div class="left"><div class="page-title">Créer un tag</div></div>
                <div class="actions">
                    <div class="user">Bonjour, <?php echo e($user['username']); ?></div>
                    <a class="logout" href="../dashboard.php">Retour</a>
                </div>
            </header>

            <main class="container">
                <section class="card">
                    <h2>Créer un tag</h2>
                    <form method="post" novalidate>
                        <?php echo csrf_field(); ?>
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
        </div>
    </div>
</body>
</html>
