<?php
session_start();

require_once '../includes/db.php';
require_once '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);

    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {

    session_regenerate_id(true);

        $_SESSION['user'] = [
            'id' => $user['id'],
            'username' => $user['username'],
            'role' => $user['role']
        ];

        redirect('dashboard.php');

    } else {
        $error = "Identifiants incorrects";
    }
}

// require '../index.php';

?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion - Admin</title>
    <link rel="preload" href="../assets/css/login.css" as="style">
    <link rel="stylesheet" href="../assets/css/login.css">
</head>
<body>
<main class="login-container" role="main">
    <div class="login-card">
        <div class="brand">
            <div class="logo" aria-hidden="true"></div>
            <div>
                <h1>Connexion Admin</h1>
                <p class="subtitle">Accédez au tableau de bord sécurisé</p>
            </div>
        </div>
        <form method="post" novalidate>
            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" name="email" type="email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required autofocus>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input id="password" name="password" type="password" required>
            </div>
            <button type="submit" class="btn">Se connecter</button>
            <?php if (!empty($error)): ?>
                <div class="error" role="alert"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
        </form>
        <p class="small">© <?php echo date('Y'); ?> Mon site</p>
    </div>
</main>
</body>
</html>