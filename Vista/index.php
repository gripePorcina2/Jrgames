<?php
// 1. INICIAR SESIÓN (Obligatorio al principio de todo archivo PHP)
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jr Games | Magic: The Gathering</title>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700&family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../CSS/home.css">
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
                
                <?php if (isset($_SESSION['usuario_id'])): ?>
                    <li><a href="perfil.php">Mi Perfil</a></li>
                <?php endif; ?>

                <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] == 'admin'): ?>
                    <li>
                        <a href="admin_usuarios.php" style="color: #d3202a;">👥 Usuarios</a>
                    </li>
                    <li>
                        <a href="historial.php" style="color: #00733e; font-weight: bold;">📊 Historial</a>
                    </li>
                <?php endif; ?> 

            </ul>
        </nav>

        <?php if (isset($_SESSION['usuario_id'])): ?>
            
            <div style="display: flex; align-items: center; gap: 15px;">
                <span style="color: var(--accent-gold); font-weight: bold; font-family: 'Lato', sans-serif;">
                    Hola, <?php echo htmlspecialchars($_SESSION['nombre']); ?>
                </span>
                <a href="logout.php" style="color: #d3202a; font-size: 0.9rem; text-decoration: none; border: 1px solid #d3202a; padding: 5px 10px; border-radius: 4px;">
                    Salir
                </a>
            </div>

        <?php else: ?>
            
            <a href="login.php" class="btn-login">Ingresar</a>

        <?php endif; ?>

    </header>

    <div class="mana-bar">
        <div class="mana-w"></div>
        <div class="mana-u"></div>
        <div class="mana-b"></div>
        <div class="mana-r"></div>
        <div class="mana-g"></div>
    </div>

    <section class="hero">
        <div class="hero-content">
            <h1>Tu viaje comienza aquí</h1>
            <p>
                Bienvenido a <strong>Jr Games</strong>. El destino definitivo para aprender las artes de 
                Magic: The Gathering y comerciar con las cartas más buscadas del multiverso.
            </p>
            
            <div class="cta-container">
                <a href="mercado.php" class="btn btn-primary">Ir a la Tienda</a>
                <a href="aprender.php" class="btn btn-secondary">Aprender Magic</a>
            </div>
        </div>
    </section>

    <section class="features">
        <h2 class="section-title">¿Qué ofrecemos?</h2>
        <div class="features-grid">
            
            <div class="feature-card">
                <h3>📜 Guías para Novatos</h3>
                <p>Desde entender los colores de maná hasta dominar la fase de combate.</p>
            </div>

            <div class="feature-card">
                <h3>💎 Marketplace Seguro</h3>
                <p>Compra y vende cartas sueltas (Singles) con total confianza y seguridad.</p>
            </div>

            <div class="feature-card">
                <h3>📦 Envíos Rápidos</h3>
                <p>Tus cartas llegan protegidas y listas para jugar en tu próximo torneo.</p>
            </div>

        </div>
    </section>

    <footer>
        <p>&copy; 2025 Jr Games. Magic: The Gathering es propiedad de Wizards of the Coast.</p>
    </footer>

</body>
</html>