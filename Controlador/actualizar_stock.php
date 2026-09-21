<?php
session_start();
include '../Modelo/db.php';

// Seguridad: Solo Admin
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] != 'admin') {
    header("Location: ../Vista/perfil.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $id_producto = $_POST['id_producto'];
    $nuevo_stock = intval($_POST['nuevo_stock']);
    $id_admin = $_SESSION['usuario_id'];

    if ($nuevo_stock < 0) $nuevo_stock = 0;

    // 1. Obtener el stock ANTERIOR para calcular la diferencia
    $res = $conn->query("SELECT stock FROM publicaciones WHERE id = '$id_producto'");
    $prod = $res->fetch_assoc();
    $stock_anterior = $prod['stock'];

    // Calcular diferencia
    $diferencia = $nuevo_stock - $stock_anterior;

    if ($diferencia != 0) {
        // Determinar tipo de movimiento
        if ($diferencia > 0) {
            $tipo = 'ENTRADA';
            $cantidad = $diferencia;
        } else {
            $tipo = 'SALIDA';
            $cantidad = abs($diferencia); // Convertir a positivo
        }

        // 2. Actualizar la tabla principal
        $sql_vendido = ($nuevo_stock > 0) ? ", vendido = 0" : ", vendido = 1";
        $sql_update = "UPDATE publicaciones SET stock = '$nuevo_stock' $sql_vendido WHERE id = '$id_producto'";

        // 3. Insertar en el HISTORIAL (MOVIMIENTOS)
        $sql_mov = "INSERT INTO movimientos (id_producto, id_usuario, tipo_movimiento, cantidad) 
                    VALUES ('$id_producto', '$id_admin', '$tipo', '$cantidad')";

        if ($conn->query($sql_update) === TRUE && $conn->query($sql_mov) === TRUE) {
            header("Location: ../Vista/perfil.php");
        } else {
            echo "<script>alert('Error al actualizar: " . $conn->error . "'); window.history.back();</script>";
        }
    } else {
        // Si no hubo cambio en la cantidad, solo redirigimos
        header("Location: ../Vista/perfil.php");
    }
}
$conn->close();
?>