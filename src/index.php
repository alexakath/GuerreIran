<?php

require_once 'includes/db.php';

$stmt = $pdo->query("SELECT 1");
$result = $stmt->fetch();

echo "Connexion OK";