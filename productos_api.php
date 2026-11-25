<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/conexion.php';

try {
    $stmt = $conexion->query('SELECT id, nombre, precio, disponible FROM productos ORDER BY disponible DESC, id DESC');
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['ok' => true, 'productos' => $productos]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'productos' => []]);
}
