<?php
session_start();
include '../Modelo/db.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 1. Recibir y limpiar datos
    $nombre = isset($_POST['nombre']) ? $conn->real_escape_string(trim($_POST['nombre'])) : '';
    $email = isset($_POST['correo']) ? $conn->real_escape_string(trim($_POST['correo'])) : ''; 
    $password = isset($_POST['password']) ? $conn->real_escape_string($_POST['password']) : ''; 

    // 2. Verificar si el email ya existe
    $check_sql = "SELECT id FROM usuarios WHERE email = '$email'";
    $check_result = $conn->query($check_sql);

    // --- DETECTOR DE ERRORES AÑADIDO AQUÍ ---
    if (!$check_result) {
        die("Error fatal de MySQL al buscar usuario: " . $conn->error . "<br>La consulta que falló fue: " . $check_sql);
    }
    // ----------------------------------------

    if ($check_result->num_rows > 0) {
        echo "<script>
                alert('Ese correo ya está registrado en el Multiverso. Intenta iniciar sesión.');
                window.history.back();
              </script>";
        exit();
    }

    // 3. Insertar con tus columnas exactas
    $sql_insert = "INSERT INTO usuarios (email, password, nombre, rol, activo) 
                   VALUES ('$email', '$password', '$nombre', 'cliente', 1)";

    if ($conn->query($sql_insert) === TRUE) {
        echo "<script>
                alert('¡Cuenta creada con éxito! Bienvenido a Jr Games.');
                window.location.href = '../Vista/login.php';
              </script>";
    } else {
        echo "<script>
                alert('Hubo un error al guardar en la BD: " . $conn->error . "');
                window.history.back();
              </script>";
    }

} else {
    header("Location: ../Vista/registro.php");
    exit();
}

$conn->close();
?>