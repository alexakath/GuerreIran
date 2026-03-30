<?php
require_once '../../includes/db.php';
require_once '../../includes/functions.php';

requireAuth();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id > 0) {
    $stmt = $pdo->prepare("DELETE FROM tags WHERE id = ?");
    $stmt->execute([$id]);
}

redirect('list.php');
