<?php
session_start();
include '../Modelo/db.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$id_usuario = $_SESSION['usuario_id'];
$es_admin = (isset($_SESSION['rol']) && $_SESSION['rol'] == 'admin');

// Datos usuario
$sql_user = "SELECT * FROM usuarios WHERE id = '$id_usuario'";
$res_user = $conn->query($sql_user);
$datos_user = $res_user->fetch_assoc();

// Datos publicaciones
$sql_cartas = "SELECT * FROM publicaciones WHERE id_usuario = '$id_usuario' ORDER BY fecha_publicacion DESC";
$res_cartas = $conn->query($sql_cartas);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Perfil | Jr Games</title>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700&family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../CSS/global.css">
    <link rel="stylesheet" href="../CSS/perfil.css">
</head>
<body>

    <header>
        <div class="logo"><a href="index.php">Jr Games</a></div>
        <nav>
            <ul>
                <li><a href="index.php">Inicio</a></li>
                <li><a href="aprender.php">Aprende a Jugar</a></li>
                <li><a href="mercado.php">Mercado</a></li>
            </ul>
        </nav>
        <div style="display: flex; align-items: center; gap: 15px;">
            <span style="color: var(--accent-gold); font-weight: bold;">Hola, <?php echo htmlspecialchars($_SESSION['nombre']); ?></span>
            <a href="logout.php" class="btn-logout">Salir</a>
        </div>
    </header>

    <div class="profile-container">
        
        <div class="profile-card">
            <div class="avatar-circle"><?php echo strtoupper(substr($datos_user['nombre'], 0, 1)); ?></div>
            <h2><?php echo $datos_user['nombre']; ?></h2>
            <p class="role-badge"><?php echo strtoupper($datos_user['rol']); ?></p>
            <div class="info-group">
                <label>Email:</label><span><?php echo $datos_user['email']; ?></span>
            </div>
            <div class="info-group">
                <label>Publicaciones:</label><span><?php echo $res_cartas->num_rows; ?></span>
            </div>
        </div>

        <div class="my-listings">
            <h3>Gestión de Inventario</h3>
            
            <?php if ($res_cartas->num_rows > 0): ?>
                <table class="listings-table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Stock</th> 
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($carta = $res_cartas->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <strong><?php echo $carta['nombre_carta']; ?></strong><br>
                                <span style="font-size: 0.8rem; color: #aaa;"><?php echo $carta['edicion']; ?></span>
                                <?php if($carta['tipo_producto'] != 'carta'): ?>
                                    <span style="font-size: 0.7rem; background: #333; padding: 2px 4px; border-radius: 3px;"><?php echo strtoupper($carta['tipo_producto']); ?></span>
                                <?php endif; ?>
                            </td>
                            <td>$<?php echo $carta['precio']; ?></td>
                            
                            <td>
                                <?php if ($es_admin): ?>
                                    <form action="../Controlador/actualizar_stock.php" method="POST" style="display: flex; gap: 5px; align-items: center;">
                                        <input type="hidden" name="id_producto" value="<?php echo $carta['id']; ?>">
                                        <input type="number" name="nuevo_stock" value="<?php echo $carta['stock']; ?>" min="0" style="width: 50px; padding: 5px; background: #222; color: white; border: 1px solid #555; border-radius: 4px;">
                                        <button type="submit" title="Guardar Stock" style="background: var(--accent-gold); border: none; cursor: pointer; padding: 5px; border-radius: 4px;">💾</button>
                                    </form>
                                <?php else: ?>
                                    <?php echo $carta['stock']; ?>
                                <?php endif; ?>
                            </td>

                            <td style="display: flex; gap: 5px;">
    <a href="editar_producto.php?id=<?php echo $carta['id']; ?>" 
       style="background: #0e68ab; color: white; border: none; padding: 5px 10px; border-radius: 4px; text-decoration: none; cursor: pointer; font-size: 0.9rem;">
       ✏️ Editar
    </a>

    <a href="../Controlador/eliminar_publicacion.php?id=<?php echo $carta['id']; ?>" 
       class="btn-delete-small" 
       onclick="return confirm('¿Borrar permanentemente?');">
       🗑️
    </a>
</td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="color: #aaa;">No tienes productos en venta.</p>
                <a href="vender.php" class="btn-sell-small">Vender ahora</a>
            <?php endif; ?>
        </div>

    </div>

    <footer><p>&copy; 2025 Jr Games.</p></footer>
</body>
</html>