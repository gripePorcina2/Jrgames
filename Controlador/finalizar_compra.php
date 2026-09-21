<?php
session_start();
include '../Modelo/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['success' => false, 'message' => 'Debes iniciar sesión para comprar.']);
    exit();
}

$id_comprador = $_SESSION['usuario_id'];
$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['items']) || count($data['items']) == 0) {
    echo json_encode(['success' => false, 'message' => 'Carrito vacío.']);
    exit();
}

// Inicia la transacción segura (Todo se vende o nada se vende)
$conn->begin_transaction();

try {
    foreach ($data['items'] as $item) {
        $id_prod = $conn->real_escape_string($item['id']);
        $cantidad = intval($item['quantity']);

        // 1. Verificar stock actual en la Base de Datos
        $res = $conn->query("SELECT stock, precio, id_usuario FROM publicaciones WHERE id = '$id_prod'");
        if($res->num_rows == 0) {
            throw new Exception("Producto no encontrado.");
        }
        
        $producto = $res->fetch_assoc();

        if ($producto['stock'] < $cantidad) {
            throw new Exception("Stock insuficiente para: " . $item['nombre']);
        }

        // 2. Actualizar Stock y Estado
        $nuevo_stock = $producto['stock'] - $cantidad;
        $vendido = ($nuevo_stock == 0) ? 1 : 0;
        
        $sql_update = "UPDATE publicaciones SET stock = $nuevo_stock, vendido = $vendido WHERE id = '$id_prod'";
        if(!$conn->query($sql_update)) throw new Exception("Error al actualizar stock.");

        // 3. Registrar la Venta
        $id_vendedor = $producto['id_usuario'];
        $precio_total = $producto['precio'] * $cantidad;
        $sql_venta = "INSERT INTO ventas (id_publicacion, id_comprador, id_vendedor, precio_final) 
                      VALUES ('$id_prod', '$id_comprador', '$id_vendedor', '$precio_total')";
        if(!$conn->query($sql_venta)) throw new Exception("Error al registrar venta.");

        // 4. Registrar Movimiento (Kardex)
        $sql_mov = "INSERT INTO movimientos (id_producto, id_usuario, tipo_movimiento, cantidad) 
                    VALUES ('$id_prod', '$id_comprador', 'SALIDA', '$cantidad')";
        if(!$conn->query($sql_mov)) throw new Exception("Error al registrar movimiento.");
    }

    $conn->commit();
    echo json_encode(['success' => true]);

} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$conn->close();
?>