<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comunidad | Jr Games</title>
    <link rel="stylesheet" href="../CSS/global.css">
    <style>
        .community-layout {
            display: grid;
            grid-template-columns: 250px 1fr 300px;
            gap: 20px;
            padding: 2rem 5%;
            max-width: 1400px;
            margin: 0 auto;
            flex: 1;
        }

        /* Sidebar Izquierda (Navegación) */
        .side-nav {
            background: var(--surface-200); padding: 1.5rem; border-radius: var(--radius-lg);
            border: 1px solid var(--surface-300); height: fit-content; position: sticky; top: 90px;
        }
        .nav-item {
            padding: 12px 15px; margin-bottom: 5px; border-radius: var(--radius-md);
            cursor: pointer; transition: 0.3s; color: var(--text-secondary); font-weight: 600;
        }
        .nav-item:hover, .nav-item.active { background: rgba(212, 175, 55, 0.1); color: var(--accent-gold); }

        /* Feed Central (Publicaciones) */
        .feed-container { display: flex; flex-direction: column; gap: 20px; }
        
        .create-post {
            background: var(--surface-200); padding: 1.5rem; border-radius: var(--radius-lg);
            border: 1px solid var(--primary); box-shadow: 0 4px 15px rgba(16, 185, 129, 0.1);
        }
        .create-post input {
            width: 100%; padding: 15px; background: var(--surface-100); border: 1px solid var(--surface-300);
            border-radius: var(--radius-md); color: white; margin-bottom: 15px; font-family: inherit;
        }
        .create-post input:focus { outline:none; border-color: var(--primary); }

        /* Estilo de un Post tipo Amino */
        .post-card {
            background: var(--surface-200); border-radius: var(--radius-lg);
            border: 1px solid var(--surface-300); overflow: hidden; transition: var(--transition-fast);
        }
        .post-card:hover { border-color: var(--accent-gold); }
        .post-header {
            padding: 15px 20px; display: flex; align-items: center; gap: 15px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        .avatar {
            width: 45px; height: 45px; border-radius: 50%; background: var(--accent-gold);
            display: flex; align-items: center; justify-content: center; font-weight: bold; color: #000;
        }
        .user-info h4 { font-size: 1rem; color: var(--text-primary); margin:0;}
        .user-info span { font-size: 0.8rem; color: var(--text-secondary); }
        
        .post-content { padding: 20px; }
        .post-content h3 { color: var(--accent-gold); margin-bottom: 10px; font-family: 'Cinzel', serif;}
        .post-content p { color: var(--text-primary); }
        .post-image { width: 100%; max-height: 400px; object-fit: cover; border-radius: var(--radius-md); margin-top: 15px; border: 1px solid var(--surface-300);}
        
        .post-actions {
            padding: 12px 20px; background: var(--surface-100);
            display: flex; gap: 20px; border-top: 1px solid var(--surface-300);
        }
        .action-btn { background: none; border: none; color: var(--text-secondary); cursor: pointer; display: flex; align-items: center; gap: 8px; font-weight: 600; font-size: 0.9rem; transition: color 0.2s;}
        .action-btn:hover { color: var(--primary); }

        /* Sidebar Derecha (Tendencias/Gremios) */
        .trending-sidebar {
            background: var(--surface-200); padding: 1.5rem; border-radius: var(--radius-lg);
            border: 1px solid var(--surface-300); height: fit-content; position: sticky; top: 90px;
        }
        .tag-pill {
            display: inline-block; padding: 6px 12px; background: var(--surface-100);
            border: 1px solid var(--surface-300); border-radius: 20px; font-size: 0.8rem; margin: 0 4px 8px 0;
            color: var(--text-secondary); cursor: pointer; transition: 0.2s;
        }
        .tag-pill:hover { border-color: var(--primary); color: white; background: var(--surface-300);}

        @media (max-width: 1024px) { .community-layout { grid-template-columns: 1fr 300px; } .side-nav { display: none; } }
        @media (max-width: 768px) { .community-layout { grid-template-columns: 1fr; padding: 1rem; } .trending-sidebar { display: none; } }
    </style>
</head>
<body>

    <header>
        <div class="logo"><a href="index.php">Jr Games</a></div>
        <nav>
            <ul>
                <li><a href="index.php">Inicio</a></li>
                <li><a href="aprender.php">Aprende</a></li>
                <li><a href="mercado.php">Mercado</a></li>
                <li><a href="comunidad.php" style="color: var(--accent-gold);">Comunidad</a></li>
                <?php if (isset($_SESSION['usuario_id'])): ?><li><a href="perfil.php">Mi Perfil</a></li><?php endif; ?>
            </ul>
        </nav>
        <div class="user-actions">
            <?php if (isset($_SESSION['usuario_id'])): ?>
                <div style="display: flex; align-items: center; gap: 15px;">
                    <span style="color: var(--accent-gold); font-weight: 600;">
                        <?php echo htmlspecialchars($_SESSION['nombre']); ?>
                    </span>
                    <a href="logout.php" class="btn-danger-outline">Salir</a>
                </div>
            <?php else: ?>
                <a href="login.php" class="btn-modern btn-outline">Ingresar</a>
            <?php endif; ?>
        </div>
    </header>

    <div class="community-layout fade-in">
        
        <aside class="side-nav">
            <h3 style="color: var(--text-primary); margin-bottom: 20px;">Explorar</h3>
            <div class="nav-item active">🔥 Muro Principal</div>
            <div class="nav-item">💬 Salas de Chat</div>
            <div class="nav-item">📖 Análisis de Mazos</div>
            <div class="nav-item">📜 Lore del Multiverso</div>
            <div class="nav-item">🏆 Torneos Locales</div>
        </aside>

        <main class="feed-container">
            
            <div class="create-post">
                <h3 style="color: var(--text-primary); margin-bottom: 15px;">Nueva Discusión</h3>
                <input type="text" placeholder="¿Qué estrategia tienes en mente, Planeswalker?">
                <div style="display: flex; justify-content: flex-end; gap: 10px;">
                    <button class="btn-modern btn-outline">📷 Adjuntar Carta</button>
                    <button class="btn-modern btn-primary">Publicar</button>
                </div>
            </div>

            <article class="post-card">
                <div class="post-header">
                    <div class="avatar">OB</div>
                    <div class="user-info">
                        <h4>Osvaldo B.</h4>
                        <span>Hace 2 horas • <strong style="color: #10b981;">Golgari Swarm</strong></span>
                    </div>
                </div>
                <div class="post-content">
                    <h3>Ayuda para mejorar mi mazo de Sheoldred</h3>
                    <p>Estoy armando un mazo enfocado en robar cartas y castigar al oponente por hacerlo. ¿Qué opinan de meterle más removal negro o debería salpicar algo de verde para acelerar mi maná en los primeros turnos?</p>
                </div>
                <div class="post-actions">
                    <button class="action-btn">❤️ 24 Likes</button>
                    <button class="action-btn">💬 12 Respuestas</button>
                    <button class="action-btn">🔄 Compartir</button>
                </div>
            </article>

            <article class="post-card">
                <div class="post-header">
                    <div class="avatar" style="background: #0e68ab; color: white;">AL</div>
                    <div class="user-info">
                        <h4>Alberto</h4>
                        <span>Hace 5 horas • <strong style="color: #3b82f6;">Azorius Senate</strong></span>
                    </div>
                </div>
                <div class="post-content">
                    <h3>Review: La nueva expansión</h3>
                    <p>Las nuevas mecánicas están rompiendo el formato Commander. Miren esta belleza, perfecta para lidiar con cementerios molestos. ¿Ustedes la están jugando?</p>
                    <img src="../IMG/cartas/scavenging_ooze.png" alt="Card" class="post-image">
                </div>
                <div class="post-actions">
                    <button class="action-btn">❤️ 89 Likes</button>
                    <button class="action-btn">💬 45 Respuestas</button>
                </div>
            </article>

        </main>

        <aside class="trending-sidebar">
            <h3 style="color: var(--accent-gold); margin-bottom: 20px;">Temas del Día</h3>
            <div style="margin-bottom: 30px;">
                <span class="tag-pill">#CommanderEDH</span>
                <span class="tag-pill">#DeckTech</span>
                <span class="tag-pill">#SpoilersNuevos</span>
                <span class="tag-pill">#CombosInfinitos</span>
                <span class="tag-pill">#Bant</span>
            </div>
            
            <h3 style="color: var(--text-primary); margin-bottom: 15px; font-size: 1rem;">Miembros Activos</h3>
            <div style="display: flex; gap: 10px;">
                <div class="avatar" style="width: 40px; height: 40px; font-size: 0.9rem;">D</div>
                <div class="avatar" style="width: 40px; height: 40px; font-size: 0.9rem; background:#d3202a; color:white;">T</div>
                <div class="avatar" style="width: 40px; height: 40px; font-size: 0.9rem; background:#00733e; color:white;">A</div>
            </div>
        </aside>

    </div>

    <footer><p>&copy; <?php echo date("Y"); ?> Jr Games. Comunidad Planeswalker.</p></footer>
</body>
</html>