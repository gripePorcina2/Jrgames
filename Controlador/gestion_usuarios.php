<?php
session_start();
include '../Modelo/db.php';

// 1. SEGURIDAD: Solo Admin puede entrar aquí
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] != 'admin') {
    header("Location: ../Vista/index.php");
    exit();
}

// --- CASO A: GUARDAR O EDITAR USUARIO (Recibido del formulario) ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $id = isset($_POST['id_usuario']) ? $_POST['id_usuario'] : '';
    
    // Limpiamos los datos para evitar caracteres raros
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $email = $conn->real_escape_string($_POST['email']);
    $rol = $conn->real_escape_string($_POST['rol']);
    $password = $conn->real_escape_string($_POST['password']);

    if (empty($id)) {
        // ============================
        // 1. CREAR NUEVO USUARIO
        // ============================
        
        // Verificar que el correo no exista ya
        $check = $conn->query("SELECT id FROM usuarios WHERE email = '$email'");
        if ($check->num_rows > 0) {
            echo "<script>alert('Error: Ese correo ya está registrado.'); window.history.back();</script>";
            exit();
        }

        // Insertamos con estatus activo (1) por defecto
        $sql = "INSERT INTO usuarios (nombre, email, password, rol, activo) 
                VALUES ('$nombre', '$email', '$password', '$rol', 1)";
        $msg = "Usuario creado correctamente.";

    } else {
        // ============================
        // 2. EDITAR USUARIO EXISTENTE
        // ============================
        
        // Si el campo contraseña está vacío, NO la tocamos en la BD
        if (empty($password)) {
            $sql = "UPDATE usuarios SET nombre='$nombre', email='$email', rol='$rol' WHERE id='$id'";
        } else {
            // Si escribieron algo, actualizamos también la contraseña
            $sql = "UPDATE usuarios SET nombre='$nombre', email='$email', rol='$rol', password='$password' WHERE id='$id'";
        }
        $msg = "Usuario actualizado correctamente.";
    }

    // Ejecutar la consulta
    if ($conn->query($sql) === TRUE) {
        echo "<script>
                alert('$msg'); 
                window.location.href='../Vista/admin_usuarios.php';
              </script>";
    } else {
        echo "<script>alert('Error en base de datos: " . $conn->error . "'); window.history.back();</script>";
    }
}

// --- CASO B: CAMBIAR ESTATUS / BAJA LÓGICA (Recibido del botón) ---
if (isset($_GET['action']) && $_GET['action'] == 'toggle' && isset($_GET['id'])) {
    
    $id = intval($_GET['id']); // Aseguramos que sea número
    
    // 1. Obtenemos estado actual del usuario
    $res = $conn->query("SELECT activo FROM usuarios WHERE id='$id'");
    
    if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();
        
        // 2. Invertimos el valor (Si es 1 pasa a 0, si es 0 pasa a 1)
        $nuevo_estado = ($row['activo'] == 1) ? 0 : 1;
        
        // 3. Guardamos el cambio
        $conn->query("UPDATE usuarios SET activo='$nuevo_estado' WHERE id='$id'");
        
        // Regresamos a la tabla automáticamente
        header("Location: ../Vista/admin_usuarios.php");
        exit();
    }
}

$conn->close();
?>