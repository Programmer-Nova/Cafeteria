<?php
require_once __DIR__ . '/verificar_admin.php';
require_once __DIR__ . '/conexion.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header('Location: index.php');
    exit;
}

$nombre     = trim($_POST['nombre'] ?? '');
$precio     = $_POST['precio'] ?? '';
$disponible = ($_POST['disponible'] ?? '1') === '0' ? 0 : 1;

if ($nombre === '' || !is_numeric($precio) || $precio < 0) {
    echo "<script>alert('Datos inválidos. Revisa el nombre y el precio.'); window.location.href='index.php';</script>";
    exit;
}

try {
    $sql = "INSERT INTO productos (nombre, precio, disponible) VALUES (:nombre, :precio, :disponible)";
    $stmt = $conexion->prepare($sql);
    $stmt->bindValue(':nombre', $nombre, PDO::PARAM_STR);
    $stmt->bindValue(':precio', (float)$precio);
    $stmt->bindValue(':disponible', $disponible, PDO::PARAM_INT);
    $stmt->execute();

    header('Location: index.php');
    exit;
} catch (PDOException $e) {
    die("Error al guardar: " . htmlspecialchars($e->getMessage()));
}