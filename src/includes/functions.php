<?php

// fonctions reutilisables partout

// Transformer un texte en URL propre (slug)
function slugify($text) {
    $text = iconv('UTF-8', 'ASCII//TRANSLIT', $text);
    $text = strtolower($text);
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    return trim($text, '-');
}

// Rediriger l’utilisateur vers une autre page
function redirect($url) {
    header("Location: $url");
    exit;
}

function base_url($path = '') {
    return $path;
}

// Empêcher les attaques XSS
// Toujours utiliser e() quand on affiche du contenu utilisateur
function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

//Debug rapide (comme console.log en JS)
function dd($data) {
    echo "<pre>";
    print_r($data);
    echo "</pre>";
    die();
}

function requireAuth() {

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if ($_SESSION['user']['role'] !== 'admin') {
    redirect('/admin/login.php');
}
}