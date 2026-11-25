<?php

$host = '127.0.0.1';
$port = 3306;
$dbname = 'cafeteria_escolar';
$usuario = 'root';
$contrasena = '';

try {
    $conexion = new PDO(
        "mysql:host={$host};port={$port};charset=utf8mb4",
        $usuario,
        $contrasena,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );

    $conexion->exec("CREATE DATABASE IF NOT EXISTS `{$dbname}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $conexion->exec("USE `{$dbname}`");

    $conexion->exec("CREATE TABLE IF NOT EXISTS productos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(255) NOT NULL,
        precio DECIMAL(10, 2) NOT NULL,
        disponible TINYINT(1) NOT NULL DEFAULT 1
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    $conexion->exec("CREATE TABLE IF NOT EXISTS usuarios (
        id INT AUTO_INCREMENT PRIMARY KEY,
        usuario VARCHAR(50) NOT NULL UNIQUE,
        nombre VARCHAR(100) NOT NULL,
        contrasena VARCHAR(255) NOT NULL,
        rol ENUM('alumno', 'admin') NOT NULL DEFAULT 'alumno'
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    $totalUsuarios = (int)$conexion->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
    if ($totalUsuarios === 0) {
        $stmtSeed = $conexion->prepare(
            "INSERT INTO usuarios (usuario, nombre, contrasena, rol) VALUES (:usuario, :nombre, :contrasena, :rol)"
        );
        $usuariosIniciales = [
            ['admin', 'Administrador', password_hash('admin123', PASSWORD_DEFAULT), 'admin'],
            ['alumno', 'Alumno', password_hash('alumno123', PASSWORD_DEFAULT), 'alumno'],
        ];
        foreach ($usuariosIniciales as [$usr, $nom, $hash, $rol]) {
            $stmtSeed->execute([
                ':usuario' => $usr,
                ':nombre' => $nom,
                ':contrasena' => $hash,
                ':rol' => $rol,
            ]);
        }
    }
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
