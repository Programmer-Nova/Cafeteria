<?php
require_once __DIR__ . '/verificar_admin.php';
require_once __DIR__ . '/conexion.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id         = (int)($_POST['id'] ?? 0);
    $nombre     = trim($_POST['nombre'] ?? '');
    $precio     = $_POST['precio'] ?? '';
    $disponible = ($_POST['disponible'] ?? '1') === '0' ? 0 : 1;

    if ($id > 0 && $nombre !== '' && is_numeric($precio) && $precio >= 0) {
        try {
            $stmt = $conexion->prepare(
                "UPDATE productos SET nombre = :nombre, precio = :precio, disponible = :disponible WHERE id = :id"
            );
            $stmt->bindValue(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindValue(':precio', (float)$precio);
            $stmt->bindValue(':disponible', $disponible, PDO::PARAM_INT);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            header('Location: index.php');
            exit;
        } catch (PDOException $e) {
            die("Error al actualizar: " . htmlspecialchars($e->getMessage()));
        }
    } else {
        echo "<script>alert('Datos inválidos.'); history.back();</script>";
        exit;
    }
}

$id = (int)($_GET['id'] ?? 0);
$stmt = $conexion->prepare("SELECT * FROM productos WHERE id = :id");
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$producto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$producto) {
    header('Location: index.php');
    exit;
}

$nombreVal = htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8');
$precioVal = number_format((float)$producto['precio'], 2, '.', '');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar producto</title>
    <link rel="stylesheet" href="estilos/estilos.css?v=2">
</head>
<body>
    <div class="contenedor">
        <form action="editar.php" method="POST" class="formulario-producto">
            <h2>Editar producto</h2>
            <input type="hidden" name="id" value="<?= $id ?>">
            <input type="text" name="nombre" value="<?= $nombreVal ?>" required>
            <input type="number" step="0.01" min="0" name="precio" value="<?= $precioVal ?>" required>
            <select name="disponible">
                <option value="1" <?= $producto['disponible'] ? 'selected' : '' ?>>Sí</option>
                <option value="0" <?= !$producto['disponible'] ? 'selected' : '' ?>>No</option>
            </select>
            <button type="submit">Actualizar producto</button>
            <a href="index.php" class="volver">← Volver al catálogo</a>
        </form>
    </div>
</body>
</html>