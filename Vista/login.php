<?php
session_start();
// Si el usuario ya está logueado, lo mandamos directo al inicio
if (isset($_SESSION['usuario_id'])) {
    header("Location: index.php"); // Asegúrate que tu home se llame index.php
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingresar | Jr Games</title>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700&family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../CSS/login.css">
    <link rel="stylesheet" href="../CSS/global.css">
</head>
<body>

    <header>
        <div class="logo"><a href="index.php">Jr Games</a></div>
        <nav>
            <ul>
                <li><a href="index.php">Inicio</a></li>
                <li><a href="aprender.php">Aprende a Jugar</a></li>
                <li><a href="mercado.php">Mercado</a></li>
            </ul>
        </nav>
    </header>

    <div class="login-container">
        <div class="login-box">
            <h2 style="margin-bottom: 2rem; color: var(--accent-gold);">Bienvenido Planeswalker</h2>
            
            <form action="login_process.php" method="POST">
                <div class="form-group">
                    <label>Correo Electrónico</label>
                    <input type="email" name="email" placeholder="ejemplo@correo.com" required>
                </div>
                <div class="form-group">
                    <label>Contraseña</label>
                    <input type="password" name="password" placeholder="********" required>
                </div>
                <button type="submit" class="btn-submit">Ingresar</button>
                <div style="margin-top: 20px; text-align: center; color: #94a3b8; font-size: 0.9rem;">
    ¿Aún no tienes cuenta? <a href="registro.php" style="color: #d4af37; text-decoration: none; font-weight: bold;">Regístrate aquí</a>
</div>
            </form>
            
            </div>
    </div>

    <footer>
        <p>&copy; 2025 Jr Games. Magic: The Gathering es propiedad de Wizards of the Coast.</p>
    </footer>
</body>
</html>