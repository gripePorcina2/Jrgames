<?php
session_start();

// 1. Datos de conexión a la Base de Datos
$servername = "localhost";
$username = "root";       // Cambia esto si tienes otro usuario en XAMPP
$password = "";           // Cambia esto si tienes contraseña
$dbname = "JrGamesStore"; // Tu base de datos creada anteriormente

// 2. Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// 3. Procesar el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Recibimos lo que pusiste en los inputs (usando los 'name')
    $email = $_POST['email']; 
    $password = $_POST['password'];

    // 4. Consulta SQL segura
    // Buscamos si existe un usuario con ese email y esa contraseña
    $sql = "SELECT * FROM usuarios WHERE email = '$email' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // --- ¡LOGIN EXITOSO! ---
        $row = $result->fetch_assoc();
        
        // Guardamos los datos en la SESIÓN
        $_SESSION['usuario_id'] = $row['id'];
        $_SESSION['nombre'] = $row['nombre']; // Ej: "Administrador"
        $_SESSION['rol'] = $row['rol'];       // Ej: "admin" o "cliente"

        // Redirigimos al Home (que ahora mostrará el nombre en vez de "Ingresar")
        header("Location: index.php");
        exit();
        
    } else {
        // --- LOGIN FALLIDO ---
        echo "<script>
                alert('Correo o contraseña incorrectos. Intenta de nuevo.');
                window.location.href='login.php';
              </script>";
    }
}

$conn->close();
?>