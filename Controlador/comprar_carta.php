<?php
session_start();
include '../Modelo/db.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../Vista/login.php");
    exit();
}

if (isset($_GET['id'])) {
    
    $id_publicacion = $conn->real_escape_string($_GET['id']);
    $id_comprador = $_SESSION['usuario_id'];

    // Obtener datos actuales
    $sql_check = "SELECT * FROM publicaciones WHERE id = '$id_publicacion'";
    $result = $conn->query($sql_check);

    if ($result->num_rows > 0) {
        $producto = $result->fetch_assoc();

        // Validaciones (Dueño y Stock)
        if ($producto['id_usuario'] == $id_comprador) {
            echo "<script>alert('No puedes comprar tu propio producto.'); window.history.back();</script>";
            exit();
        }
        if ($producto['stock'] <= 0 || $producto['vendido'] == 1) {
            echo "<script>alert('¡Producto agotado!'); window.location.href='../Vista/mercado.php';</script>";
            exit();
        }

        // --- LÓGICA DE STOCK Y MOVIMIENTOS ---
        
        $nuevo_stock = $producto['stock'] - 1;
        $sql_vendido = ($nuevo_stock == 0) ? ", vendido = 1" : "";

        // 1. Actualizar Stock
        $sql_update = "UPDATE publicaciones SET stock = $nuevo_stock $sql_vendido WHERE id = '$id_publicacion'";
        
        // 2. Registrar Venta (Ticket financiero)
        $id_vendedor = $producto['id_usuario'];
        $precio = $producto['precio'];
        $sql_venta = "INSERT INTO ventas (id_publicacion, id_comprador, id_vendedor, precio_final) 
                      VALUES ('$id_publicacion', '$id_comprador', '$id_vendedor', '$precio')";

        // 3. Registrar MOVIMIENTO DE SALIDA (Kardex de Inventario) <--- NUEVO
        // Guardamos que salió 1 unidad
        $sql_mov = "INSERT INTO movimientos (id_producto, id_usuario, tipo_movimiento, cantidad) 
                    VALUES ('$id_publicacion', '$id_comprador', 'SALIDA', 1)";

        // Ejecutar las 3 consultas
        if ($conn->query($sql_update) === TRUE && $conn->query($sql_venta) === TRUE && $conn->query($sql_mov) === TRUE) {
            echo "<script>
                    alert('¡Compra realizada! Quedan: $nuevo_stock unidades.');
                    window.location.href='../Vista/mercado.php';
                  </script>";
        } else {
            echo "Error: " . $conn->error;
        }

    } else {
        echo "Producto no encontrado.";
    }
} else {
    header("Location: ../Vista/mercado.php");
}
$conn->close();
?>