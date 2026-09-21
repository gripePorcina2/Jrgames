<?php
session_start();
include '../Modelo/db.php'; 

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../Vista/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id_usuario = $_SESSION['usuario_id'];
    
    // RECIBIR DATOS 
    $nombre = $conn->real_escape_string($_POST['nombre_carta']);
    $tipo_producto = $_POST['tipo_producto'];
    $edicion = $conn->real_escape_string($_POST['edicion']);
    $estado = $_POST['estado'];
    $rareza = $_POST['rareza'];
    $tipo_carta_mtg = isset($_POST['tipo_carta_mtg']) ? $conn->real_escape_string($_POST['tipo_carta_mtg']) : 'No aplica';
    $precio = floatval($_POST['precio']);
    $stock = intval($_POST['stock']);
    $detalles = $conn->real_escape_string($_POST['detalles']);
    $es_doble_cara = isset($_POST['es_doble_cara']) ? intval($_POST['es_doble_cara']) : 0;

    $formatos_array = isset($_POST['formatos']) ? $_POST['formatos'] : [];
    $formatos_string = implode(", ", $formatos_array);

    // MANEJO DE IMÁGENES
    $directorio_fisico = "../IMG/cartas/";
    if (!file_exists($directorio_fisico)) { mkdir($directorio_fisico, 0777, true); }

    // 1. Imagen Frontal
    $tipo_archivo = strtolower(pathinfo($_FILES["imagen"]["name"], PATHINFO_EXTENSION));
    $nombre_final = time() . "_front_" . $id_usuario . "." . $tipo_archivo;
    $ruta_para_mover = $directorio_fisico . $nombre_final;
    $ruta_para_bd    = "IMG/cartas/" . $nombre_final;

    $ruta_reverso_bd = "NULL"; // Por defecto no hay reverso

    if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $ruta_para_mover)) {
        
        // 2. Imagen Reverso (Solo si aplica)
        if ($es_doble_cara == 1 && isset($_FILES["imagen_reverso"]) && $_FILES["imagen_reverso"]["error"] == 0) {
            $tipo_archivo_rev = strtolower(pathinfo($_FILES["imagen_reverso"]["name"], PATHINFO_EXTENSION));
            $nombre_final_rev = time() . "_back_" . $id_usuario . "." . $tipo_archivo_rev;
            $ruta_para_mover_rev = $directorio_fisico . $nombre_final_rev;
            
            if (move_uploaded_file($_FILES["imagen_reverso"]["tmp_name"], $ruta_para_mover_rev)) {
                $ruta_reverso_bd = "'IMG/cartas/" . $nombre_final_rev . "'";
            }
        }

        // INSERTAR EN BASE DE DATOS
        $sql = "INSERT INTO publicaciones (id_usuario, tipo_producto, nombre_carta, edicion, estado, rareza, formatos, tipo_carta_mtg, precio, stock, detalles, imagen, es_doble_cara, imagen_reverso) 
                VALUES ('$id_usuario', '$tipo_producto', '$nombre', '$edicion', '$estado', '$rareza', '$formatos_string', '$tipo_carta_mtg', '$precio', '$stock', '$detalles', '$ruta_para_bd', '$es_doble_cara', $ruta_reverso_bd)";

        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('¡Publicado con éxito en el Multiverso!'); window.location.href='../Vista/mercado.php';</script>";
        } else {
            echo "Error en BD: " . $conn->error;
        }

    } else {
        echo "<script>alert('Error al subir la imagen principal.'); window.history.back();</script>";
    }
}
$conn->close();
?>
