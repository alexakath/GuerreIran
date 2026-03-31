<?php
require_once '../../includes/db.php';
require_once '../../includes/functions.php';

requireAuth();

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
    $stmt = $pdo->prepare("DELETE FROM tags WHERE id = ?");
    $stmt->execute([$id]);
}

redirect('list.php');
