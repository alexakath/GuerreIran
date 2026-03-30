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

// CSRF helpers
function csrf_token() {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        $_SESSION['csrf_token_time'] = time();
    }
    return $_SESSION['csrf_token'];
}

function csrf_field() {
    $t = csrf_token();
    return '<input type="hidden" name="csrf_token" value="' . $t . '">';
}

function verify_csrf($token) {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (empty($_SESSION['csrf_token']) || empty($token)) return false;
    return hash_equals($_SESSION['csrf_token'], $token);
}

// Security & cache headers for PHP responses (admin pages should still set no-cache)
function send_security_headers() {
    header('X-Frame-Options: SAMEORIGIN');
    header('X-Content-Type-Options: nosniff');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Permissions-Policy: interest-cohort=()');
    // Admin pages: prevent caching by default
    header('Cache-Control: no-store, no-cache, must-revalidate');
}

// Asset helper (supports optional CDN via config)
function asset_url($path) {
    if (defined('CDN_URL') && !empty(CDN_URL)) {
        return rtrim(CDN_URL, '/') . '/' . ltrim($path, '/');
    }
    // root-relative by default
    return '/' . ltrim($path, '/');
}