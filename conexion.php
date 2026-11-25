<?php

try {
    $conexion = new PDO('sqlite:' . __DIR__ . '/cafeteria.sqlite');
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $conexion->exec("CREATE TABLE IF NOT EXISTS productos (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nombre TEXT NOT NULL,
        precio REAL NOT NULL,
        disponible INTEGER NOT NULL DEFAULT 1
    )");
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
