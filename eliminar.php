<?php
require_once __DIR__ . '/verificar_admin.php';
require_once __DIR__ . '/conexion.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header('Location: index.php');
    exit;
}

$id = (int)($_POST['id'] ?? 0);

if ($id > 0) {
    try {
        $stmt = $conexion->prepare("DELETE FROM productos WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        die("Error al eliminar: " . htmlspecialchars($e->getMessage()));
    }
}
header('Location: index.php');
exit;