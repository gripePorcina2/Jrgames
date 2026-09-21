<?php
session_start();
include '../Modelo/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id_producto = $conn->real_escape_string($_POST['id_producto']);
    $nombre = $conn->real_escape_string($_POST['nombre_carta']);
    $edicion = $conn->real_escape_string($_POST['edicion']);
    $estado = $conn->real_escape_string($_POST['estado']);
    $precio = floatval($_POST['precio']);
    $detalles = $conn->real_escape_string($_POST['detalles']);

    $tipo_producto = isset($_POST['tipo_producto']) ? $conn->real_escape_string($_POST['tipo_producto']) : 'carta';
    $rareza = isset($_POST['rareza']) ? $conn->real_escape_string($_POST['rareza']) : '';
    $tipo_carta_mtg = isset($_POST['tipo_carta_mtg']) ? $conn->real_escape_string($_POST['tipo_carta_mtg']) : 'No aplica';
    
    $formatos_array = isset($_POST['formatos']) ? $_POST['formatos'] : [];
    $formatos_string = $conn->real_escape_string(implode(", ", $formatos_array));

    $sql = "UPDATE publicaciones SET 
            nombre_carta = '$nombre',
            tipo_producto = '$tipo_producto',
            edicion = '$edicion',
            estado = '$estado',
            rareza = '$rareza',
            formatos = '$formatos_string',
            tipo_carta_mtg = '$tipo_carta_mtg',
            precio = '$precio',
            detalles = '$detalles'
            WHERE id = '$id_producto'";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('¡Datos de la carta actualizados!'); window.location.href='../Vista/perfil.php';</script>";
    } else {
        echo "<script>alert('Error al actualizar: " . $conn->error . "'); window.history.back();</script>";
    }
} else {
    header("Location: ../Vista/perfil.php");
}
$conn->close();
?>