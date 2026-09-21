<?php
session_start();
include '../Modelo/db.php';

// Seguridad: Solo Admin
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] != 'admin') {
    header("Location: index.php");
    exit();
}

// Consulta Maestra: Unimos movimientos + publicaciones + usuarios
$sql = "SELECT m.*, p.nombre_carta, u.nombre as nombre_usuario 
        FROM movimientos m
        JOIN publicaciones p ON m.id_producto = p.id
        JOIN usuarios u ON m.id_usuario = u.id
        ORDER BY m.fecha_movimiento DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial de Movimientos | Jr Games</title>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700&family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../CSS/global.css">
    <style>
        .history-container { padding: 3rem 5%; }
        h1 { text-align: center; color: var(--accent-gold); margin-bottom: 2rem; font-family: 'Cinzel', serif; }
        
        .history-table { width: 100%; border-collapse: collapse; background: #1a1a1a; border-radius: 10px; overflow: hidden; }
        .history-table th { background: #333; color: var(--accent-gold); padding: 15px; text-align: left; }
        .history-table td { padding: 12px 15px; border-bottom: 1px solid #333; color: #ddd; }
        
        .badge { padding: 5px 10px; border-radius: 4px; font-weight: bold; font-size: 0.8rem; }
        .badge.entrada { background: rgba(0, 115, 62, 0.3); color: #00ff7f; border: 1px solid #00733e; }
        .badge.salida { background: rgba(211, 32, 42, 0.3); color: #ff6b6b; border: 1px solid #d3202a; }
    </style>
</head>
<body>

    <header>
        <div class="logo"><a href="index.php">Jr Games (Admin)</a></div>
        <nav>
            <ul>
                <li><a href="index.php">Inicio</a></li>
                <li><a href="perfil.php">Inventario</a></li>
                <li><a href="historial.php" style="color: var(--accent-gold);">Historial</a></li>
            </ul>
        </nav>
        <a href="logout.php" style="color: #d3202a; border: 1px solid #d3202a; padding: 5px 10px;">Salir</a>
    </header>

    <div class="history-container">
        <h1>Bitácora de Movimientos</h1>
        
        <div style="overflow-x: auto;">
            <table class="history-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Fecha y Hora</th>
                        <th>Producto</th>
                        <th>Tipo</th>
                        <th>Cantidad</th>
                        <th>Usuario / Responsable</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['fecha_movimiento']; ?></td>
                        <td><?php echo $row['nombre_carta']; ?></td>
                        <td>
                            <span class="badge <?php echo strtolower($row['tipo_movimiento']); ?>">
                                <?php echo $row['tipo_movimiento']; ?>
                            </span>
                        </td>
                        <td><?php echo $row['cantidad']; ?></td>
                        <td><?php echo $row['nombre_usuario']; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>