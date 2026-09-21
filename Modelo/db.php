<?php
// db.php
$servername = "localhost";
$username = "root";  
$password = "";      
$dbname = "JrGamesStore";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Fallo en la conexión: " . $conn->connect_error);
}
?>
