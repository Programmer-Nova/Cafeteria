<?php
session_start();
require_once __DIR__ . '/conexion.php';

$esperaJson = isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false;

$responderJson = static function (int $status, array $payload): void {
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($payload);
    exit;
};

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.html');
    exit;
}

$usuario = trim($_POST['usuario'] ?? '');
$contrasena = $_POST['contrasena'] ?? '';

if ($usuario === '' || $contrasena === '') {
    if ($esperaJson) {
        $responderJson(400, ['ok' => false, 'mensaje' => 'Completa tu usuario y contraseña.']);
    }
    header('Location: login.html?error=campos');
    exit;
}

try {
    $stmt = $conexion->prepare(
        'SELECT id, usuario, nombre, contrasena, rol FROM usuarios WHERE usuario = :usuario LIMIT 1'
    );
    $stmt->bindValue(':usuario', $usuario, PDO::PARAM_STR);
    $stmt->execute();
    $registro = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$registro || !password_verify($contrasena, $registro['contrasena'])) {
        if ($esperaJson) {
            $responderJson(401, ['ok' => false, 'mensaje' => 'Usuario o contraseña incorrectos.']);
        }
        header('Location: login.html?error=1');
        exit;
    }

    session_regenerate_id(true);
    $_SESSION['usuario_id'] = (int)$registro['id'];
    $_SESSION['usuario']    = $registro['usuario'];
    $_SESSION['nombre']     = $registro['nombre'];
    $_SESSION['rol']        = $registro['rol'];

    $destino = ($registro['rol'] === 'admin') ? 'index.php' : 'lobby.html';

    if ($esperaJson) {
        $responderJson(200, [
            'ok'       => true,
            'rol'      => $registro['rol'],
            'redirect' => $destino,
        ]);
    }

    header('Location: ' . $destino);
    exit;
} catch (PDOException $e) {
    if ($esperaJson) {
        $responderJson(500, ['ok' => false, 'mensaje' => 'Error interno al iniciar sesión.']);
    }
    die('Error al iniciar sesión: ' . htmlspecialchars($e->getMessage()));
}
