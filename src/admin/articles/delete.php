<?php
require_once '../../includes/db.php';
require_once '../../includes/functions.php';

requireAuth();

// Only allow POST deletes with CSRF
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('list.php');
}

send_security_headers();

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$token = $_POST['csrf_token'] ?? '';
if (!verify_csrf($token)) {
    redirect('list.php');
}

if ($id > 0) {
    // fetch image to remove
    $stmt = $pdo->prepare("SELECT image_url FROM articles WHERE id = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    if ($row && !empty($row['image_url'])) {
        $path = __DIR__ . '/../../' . $row['image_url'];
        if (file_exists($path)) @unlink($path);
    }

    $del = $pdo->prepare("DELETE FROM articles WHERE id = ?");
    $del->execute([$id]);
}

redirect('list.php');
