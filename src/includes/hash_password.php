<?php
// ============================================
//  Générateur de hash de mot de passe
// ============================================

$password = "admin123"; // Changez ce mot de passe

$hash = password_hash($password, PASSWORD_DEFAULT);

echo "Mot de passe : " . $password . PHP_EOL;
echo "Hash         : " . $hash . PHP_EOL;

// Vérification (optionnel)
if (password_verify($password, $hash)) {
    echo "Vérification : ✅ Hash valide" . PHP_EOL;
} else {
    echo "Vérification : ❌ Hash invalide" . PHP_EOL;
}
