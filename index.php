<?php
require_once __DIR__ . '/verificar_admin.php';
require_once __DIR__ . '/conexion.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cafetería Escolar</title>
    <link rel="stylesheet" href="estilos/estilos.css?v=3">
</head>
<body>
    <div class="contenedor">
        <div class="barra-sesion">
            <span>Sesión: <strong><?= htmlspecialchars($_SESSION['nombre'] ?? $_SESSION['usuario'], ENT_QUOTES, 'UTF-8') ?></strong> (Admin)</span>
            <a href="logout.php" class="btn-salir">Cerrar sesión</a>
        </div>
        <div class="encabezado">
            <img src="img/cafe.png" alt="Logo Cafetería Escolar" class="logo">
            <div class="textos">
                <h1>Cafetería Escolar</h1>
                <p>Control de productos</p>
            </div>
        </div>

        <form action="insertar.php" method="POST" class="formulario-producto">
            <h2>Agregar producto</h2>
            <input type="text" name="nombre" placeholder="Nombre del producto" required>
            <input type="number" step="0.01" min="0" name="precio" placeholder="Precio ($)" required>
            <select name="disponible">
                <option value="1">Sí</option>
                <option value="0">No</option>
            </select>
            <button type="submit">Guardar producto</button>
        </form>

        <h2 class="titulo-catalogo">Catálogo de productos</h2>
        <table class="tabla-catalogo">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Disponible</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $stmt = $conexion->query("SELECT * FROM productos ORDER BY id DESC");
            $hayFilas = false;

            while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $hayFilas = true;
                $id      = (int)$fila['id'];
                $nombre  = htmlspecialchars($fila['nombre'], ENT_QUOTES, 'UTF-8');
                $precio  = number_format((float)$fila['precio'], 2);
                $estado  = $fila['disponible'] ? 'Sí' : 'No';
                $clase   = $fila['disponible'] ? 'si' : 'no';

                echo "<tr>
                        <td>{$id}</td>
                        <td>{$nombre}</td>
                        <td>\${$precio}</td>
                        <td><span class='estado {$clase}'>{$estado}</span></td>
                        <td class='acciones'>
                            <a href='editar.php?id={$id}' class='btn-accion btn-editar' title='Editar'>✎</a>
                            <button type='button' class='btn-accion btn-eliminar' title='Eliminar'
                                    data-id='{$id}' data-nombre='{$nombre}'>🗑</button>
                        </td>
                      </tr>";
            }

            if (!$hayFilas) {
                echo "<tr><td colspan='5' class='vacio'>No hay productos registrados todavía.</td></tr>";
            }
            ?>
            </tbody>
        </table>
    </div>

    <!-- MODAL ELIMINAR -->
    <div id="modal-eliminar" class="modal-fondo" hidden>
        <div class="modal">
            <div class="modal-icono">!</div>
            <h3>¿Eliminar este producto?</h3>
            <p id="modal-texto">Esta acción no se puede deshacer.</p>
            <form action="eliminar.php" method="POST" class="modal-botones">
                <input type="hidden" name="id" id="modal-id">
                <button type="button" id="modal-cancelar" class="btn-modal btn-cancelar">Cancelar</button>
                <button type="submit" class="btn-modal btn-confirmar">Eliminar</button>
            </form>
        </div>
    </div>
    <script src="js/script.js?v=2"></script>
</body>
</html>