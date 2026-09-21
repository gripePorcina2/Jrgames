<?php
session_start();
if (isset($_SESSION['usuario_id'])) {
    header("Location: index.php"); // Si ya está logueado, lo mandamos al inicio
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Cuenta | Jr Games</title>
    <link rel="stylesheet" href="../CSS/global.css">
    <style>
        body { 
            background-color: #0f172a; 
            display: flex; 
            flex-direction: column; 
            min-height: 100vh;
        }
        .auth-container {
            flex-grow: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        .auth-box {
            background: #1e293b;
            padding: 3rem;
            border-radius: 12px;
            border: 1px solid #334155;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 10px 30px -5px rgba(0,0,0,0.5);
            text-align: center;
        }
        .auth-title {
            color: #d4af37;
            font-family: 'Cinzel', serif;
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        .auth-subtitle {
            color: #94a3b8;
            font-size: 0.95rem;
            margin-bottom: 2rem;
        }
        .form-group {
            text-align: left;
            margin-bottom: 1.5rem;
        }
        .form-label {
            display: block;
            color: #94a3b8;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }
        .form-input {
            width: 100%;
            padding: 14px 16px;
            background: #0f172a;
            border: 1px solid #475569;
            color: #f8fafc;
            border-radius: 8px;
            font-family: 'Inter', sans-serif;
            font-size: 0.95rem;
            transition: all 0.2s ease;
            box-sizing: border-box;
        }
        .form-input:focus {
            outline: none;
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
        }
        .btn-submit {
            width: 100%;
            padding: 15px;
            background: #10b981;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 10px;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        }
        .btn-submit:hover {
            background: #059669;
            transform: translateY(-2px);
        }
        .auth-links {
            margin-top: 2rem;
            font-size: 0.9rem;
            color: #94a3b8;
        }
        .auth-links a {
            color: #d4af37;
            text-decoration: none;
            font-weight: bold;
            transition: 0.2s;
        }
        .auth-links a:hover {
            color: #fde047;
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <header>
        <div class="logo"><a href="index.php">Jr Games</a></div>
        <nav>
            <ul>
                <li><a href="index.php">Inicio</a></li>
                <li><a href="mercado.php">Mercado</a></li>
            </ul>
        </nav>
    </header>

    <div class="auth-container fade-in">
        <div class="auth-box">
            <h2 class="auth-title">Únete al Multiverso</h2>
            <p class="auth-subtitle">Crea tu cuenta para comprar y vender cartas.</p>

            <form action="../Controlador/procesar_registro.php" method="POST" onsubmit="return validarPasswords()">
                
                <div class="form-group">
                    <label class="form-label">Nombre Completo</label>
                    <input type="text" name="nombre" class="form-input" placeholder="Ej: Jace Beleren" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Correo Electrónico</label>
                    <input type="email" name="correo" class="form-input" placeholder="jace@ravnica.com" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Contraseña</label>
                    <input type="password" id="pass1" name="password" class="form-input" placeholder="Mínimo 6 caracteres" required minlength="6">
                </div>

                <div class="form-group">
                    <label class="form-label">Confirmar Contraseña</label>
                    <input type="password" id="pass2" class="form-input" placeholder="Repite tu contraseña" required minlength="6">
                </div>

                <button type="submit" class="btn-submit">Crear Cuenta</button>

            </form>

            <div class="auth-links">
                ¿Ya tienes una cuenta? <a href="login.php">Inicia Sesión aquí</a>
            </div>
        </div>
    </div>

    <script>
        function validarPasswords() {
            const p1 = document.getElementById('pass1').value;
            const p2 = document.getElementById('pass2').value;
            
            if (p1 !== p2) {
                alert("Las contraseñas no coinciden. Por favor, verifícalas.");
                return false;
            }
            return true;
        }
    </script>

</body>
</html>