<?php
session_start();
include '../Modelo/db.php'; 

// 1. Verificar si hay sesión activa
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../Vista/login.php");
    exit();
}

if (isset($_GET['id'])) {
    
    $id_carta = $conn->real_escape_string($_GET['id']);
    $id_usuario_actual = $_SESSION['usuario_id'];
    
    // Verificamos si el usuario actual tiene poderes de administrador
    $es_admin = (isset($_SESSION['rol']) && $_SESSION['rol'] == 'admin');

    // 2. Buscar el producto en la BD para ver a quién le pertenece y obtener su imagen
    $sql_check = "SELECT id_usuario, imagen FROM publicaciones WHERE id = '$id_carta'";
    $res_check = $conn->query($sql_check);

    if ($res_check->num_rows > 0) {
        $row = $res_check->fetch_assoc();

        // 3. LA MAGIA: ¿Es el dueño de la carta O es el Administrador?
        if ($row['id_usuario'] == $id_usuario_actual || $es_admin) {
            
            // --- SOLUCIÓN AL ERROR DE LLAVE FORÁNEA ---
            // Limpiamos los "Hijos" (historial) antes de borrar al "Padre" (la publicación)
            $conn->query("DELETE FROM ventas WHERE id_publicacion = '$id_carta'");
            $conn->query("DELETE FROM movimientos WHERE id_producto = '$id_carta'");

            // Paso extra: Borramos la foto del servidor para no saturar tu disco duro
            $ruta_fisica = "../" . $row['imagen'];
            if (!empty($row['imagen']) && file_exists($ruta_fisica) && is_file($ruta_fisica)) {
                unlink($ruta_fisica); 
            }

            // Ejecutar el hechizo de exilio (DELETE de la publicación)
            $sql = "DELETE FROM publicaciones WHERE id = '$id_carta'";

            if ($conn->query($sql) === TRUE) {
                echo "<script>
                        alert('¡Publicación exiliada correctamente!');
                        window.history.back();
                      </script>";
            } else {
                echo "<script>alert('Error en BD: " . $conn->error . "'); window.history.back();</script>";
            }

        } else {
            // Intento de borrar la carta de alguien más
            echo "<script>alert('Hechizo denegado: No tienes permiso para borrar los permanentes de otro jugador.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('El producto ya no existe.'); window.history.back();</script>";
    }

} else {
    header("Location: ../Vista/mercado.php");
}

$conn->close();
?>