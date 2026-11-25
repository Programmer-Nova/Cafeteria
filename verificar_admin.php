<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['usuario_id'])) {
    header('Location: login.html');
    exit;
}

if (($_SESSION['rol'] ?? '') !== 'admin') {
    header('Location: lobby.html');
    exit;
}
