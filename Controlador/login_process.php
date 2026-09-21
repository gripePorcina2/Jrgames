<?php
session_start();
include 'db.php'; // Incluimos la conexión

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Recibimos datos del formulario (login.html)
    $email = $_POST['email']; 
    $password = $_POST['password'];

    // Consulta segura para evitar inyecciones básicas
    // Buscamos el usuario por su EMAIL
    $sql = "SELECT * FROM usuarios WHERE email = '$email' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // ¡Login Exitoso!
        $row = $result->fetch_assoc();
        
        // Guardamos los datos importantes en la SESIÓN
        $_SESSION['usuario_id'] = $row['id'];
        $_SESSION['nombre'] = $row['nombre']; 
        $_SESSION['rol'] = $row['rol'];        
        $_SESSION['email'] = $row['email'];

        // Redireccionar al Home
        header("Location: index.php");
        exit();
    } else {
        // Login Fallido
        echo "<script>
                alert('Correo o contraseña incorrectos');
                window.location.href='login.php';
              </script>";
    }
}
$conn->close();
?>