<?php
session_start();
include '../Modelo/db.php';

// Seguridad: Solo Admin
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] != 'admin') {
    header("Location: index.php");
    exit();
}

$sql = "SELECT * FROM usuarios ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Usuarios | Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700&family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../CSS/global.css">
    <style>
        .admin-container { padding: 3rem 5%; display: grid; grid-template-columns: 1fr 2fr; gap: 30px; }
        
        /* Formulario */
        .admin-form { background: #1a1a1a; padding: 25px; border-radius: 10px; border: 1px solid var(--accent-gold); height: fit-content; }
        .admin-form h3 { color: var(--accent-gold); margin-bottom: 20px; border-bottom: 1px solid #333; padding-bottom: 10px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; color: #aaa; margin-bottom: 5px; font-size: 0.9rem; }
        .form-group input, .form-group select { width: 100%; padding: 10px; background: #333; border: 1px solid #555; color: white; border-radius: 4px; box-sizing: border-box;}
        .btn-save { width: 100%; background: var(--accent-gold); color: black; padding: 10px; border: none; font-weight: bold; cursor: pointer; border-radius: 4px; margin-top: 10px; }
        .btn-clear { width: 100%; background: #444; color: white; padding: 10px; border: none; cursor: pointer; border-radius: 4px; margin-top: 5px; }

        /* Tabla */
        .user-table { width: 100%; border-collapse: collapse; background: #1a1a1a; border-radius: 10px; overflow: hidden; }
        .user-table th { background: #333; color: var(--accent-gold); padding: 15px; text-align: left; }
        .user-table td { padding: 12px 15px; border-bottom: 1px solid #333; color: #ddd; }
        .status-active { color: #00ff00; font-weight: bold; }
        .status-inactive { color: #ff0000; font-weight: bold; }
        
        .btn-action { padding: 5px 10px; text-decoration: none; border-radius: 4px; font-size: 0.8rem; margin-right: 5px; display: inline-block; cursor: pointer; border: none;}
        .btn-edit { background: #0e68ab; color: white; }
        .btn-toggle { background: #d3202a; color: white; }
        .btn-toggle.activate { background: #00733e; }
    </style>
</head>
<body>

    <header>
        <div class="logo"><a href="index.php">Jr Games (Admin)</a></div>
        <nav>
            <ul>
                <li><a href="index.php">Inicio</a></li>
                <li><a href="perfil.php">Mi Perfil</a></li>
                <li><a href="admin_usuarios.php" style="color: var(--accent-gold);">Usuarios</a></li>
            </ul>
        </nav>
        <a href="logout.php" style="color: #d3202a; border: 1px solid #d3202a; padding: 5px 10px;">Salir</a>
    </header>

    <h1 style="text-align: center; margin-top: 2rem; color: #fff;">Administración de Usuarios</h1>

    <div class="admin-container">
        
        <div class="admin-form">
            <h3 id="form-title">Nuevo Usuario</h3>
            <form action="../Controlador/gestion_usuarios.php" method="POST">
                <input type="hidden" name="id_usuario" id="id_usuario">
                
                <div class="form-group">
                    <label>Nombre Completo</label>
                    <input type="text" name="nombre" id="nombre" required>
                </div>
                <div class="form-group">
                    <label>Correo Electrónico</label>
                    <input type="email" name="email" id="email" required>
                </div>
                <div class="form-group">
                    <label>Contraseña</label>
                    <input type="password" name="password" id="password" placeholder="Dejar vacío para no cambiar">
                </div>
                <div class="form-group">
                    <label>Rol</label>
                    <select name="rol" id="rol">
                        <option value="cliente">Cliente</option>
                        <option value="admin">Administrador</option>
                    </select>
                </div>

                <button type="submit" class="btn-save">Guardar Usuario</button>
                <button type="button" class="btn-clear" onclick="limpiarForm()">Limpiar / Nuevo</button>
            </form>
        </div>

        <div style="overflow-x: auto;">
            <table class="user-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Estatus</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr style="opacity: <?php echo ($row['activo'] == 0) ? '0.5' : '1'; ?>">
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['nombre']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo strtoupper($row['rol']); ?></td>
                        <td>
                            <?php if($row['activo'] == 1): ?>
                                <span class="status-active">ACTIVO</span>
                            <?php else: ?>
                                <span class="status-inactive">INACTIVO</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <button class="btn-action btn-edit" 
                                onclick="editarUsuario('<?php echo $row['id']; ?>', '<?php echo $row['nombre']; ?>', '<?php echo $row['email']; ?>', '<?php echo $row['rol']; ?>')">
                                ✏️
                            </button>

                            <a href="../Controlador/gestion_usuarios.php?action=toggle&id=<?php echo $row['id']; ?>" 
                               class="btn-action btn-toggle <?php echo ($row['activo'] == 0) ? 'activate' : ''; ?>">
                                <?php echo ($row['activo'] == 1) ? '🚫 Baja' : '✅ Alta'; ?>
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

    </div>

    <script>
        function editarUsuario(id, nombre, email, rol) {
            document.getElementById('form-title').innerText = "Editar Usuario (ID: " + id + ")";
            document.getElementById('id_usuario').value = id;
            document.getElementById('nombre').value = nombre;
            document.getElementById('email').value = email;
            document.getElementById('rol').value = rol;
            document.getElementById('password').required = false; // No obligatoria al editar
            document.getElementById('password').placeholder = "Escribe nueva para cambiar";
        }

        function limpiarForm() {
            document.getElementById('form-title').innerText = "Nuevo Usuario";
            document.getElementById('id_usuario').value = "";
            document.getElementById('nombre').value = "";
            document.getElementById('email').value = "";
            document.getElementById('rol').value = "cliente";
            document.getElementById('password').required = true; // Obligatoria al crear
            document.getElementById('password').placeholder = "";
        }
    </script>

</body>
</html>