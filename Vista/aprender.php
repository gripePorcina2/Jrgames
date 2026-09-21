<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aprende a Jugar | Jr Games</title>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700&family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../CSS/global.css">
    <link rel="stylesheet" href="../CSS/aprender.css">
</head>
<body>

    <header>
        <div class="logo"><a href="index.php">Jr Games</a></div>
        <nav>
            <ul>
                <li><a href="index.php">Inicio</a></li>
                <li><a href="aprender.php" style="color: var(--accent-gold);">Aprende a Jugar</a></li>
                <li><a href="mercado.php">Mercado</a></li>
                <li><a href="comunidad.php">Comunidad</a></li>
            </ul>
        </nav>

        <?php if (isset($_SESSION['usuario_id'])): ?>
            <div style="display: flex; align-items: center; gap: 15px;">
                <span style="color: var(--accent-gold); font-weight: bold; font-family: 'Lato', sans-serif;">
                    Hola, <?php echo htmlspecialchars($_SESSION['nombre']); ?>
                </span>
                <a href="logout.php" style="color: #d3202a; font-size: 0.9rem; border: 1px solid #d3202a; padding: 5px 10px; border-radius: 4px;">Salir</a>
            </div>
        <?php else: ?>
            <a href="login.php" class="btn-login">Ingresar</a>
        <?php endif; ?>
    </header>

    <div class="mana-bar">
        <div class="mana-w"></div><div class="mana-u"></div><div class="mana-b"></div><div class="mana-r"></div><div class="mana-g"></div>
    </div>

    <section class="learn-section">
        
        <div style="text-align: center; margin-bottom: 3rem;">
            <h1>La Rueda de Colores</h1>
            <p style="color: var(--text-muted);">Pasa el cursor sobre cada paisaje para descubrir su filosofía.</p>
        </div>

        <div class="mana-carousel-container">
            <div class="mana-panel white" style="background-image: url('../IMG/Plains-Ikoria-MtG-Art.jpg');">
                <div class="panel-overlay"></div>
                <div class="panel-content">
                    <div class="mana-icon"><img src="../IMG/Plains.png" alt="Blanco"></div>
                    <h3>Proteccion</h3>
                    <p class="description">Paz, ley, estructura y protección. La fuerza en la unidad.</p>
                </div>
            </div>
            <div class="mana-panel blue" style="background-image: url('../IMG/Island-Ikoria-MtG-Art.jpg');">
                <div class="panel-overlay"></div>
                <div class="panel-content">
                    <div class="mana-icon"><img src="../IMG/Island.png" alt="Azul"></div>
                    <h3>Control</h3>
                    <p class="description">Conocimiento, manipulación y engaño. Controla el flujo del juego.</p>
                </div>
            </div>
            <div class="mana-panel black" style="background-image: url('../IMG/Swamp-1-Ikoria-MtG-Art.jpg');">
                <div class="panel-overlay"></div>
                <div class="panel-content">
                    <div class="mana-icon"><img src="../IMG/Swamp.png" alt="Negro"></div>
                    <h3>Sacrificio</h3>
                    <p class="description">Ambición, muerte y sacrificio. Ganar a cualquier precio.</p>
                </div>
            </div>
            <div class="mana-panel red" style="background-image: url('../IMG/Mountain-Ikoria-MtG-Art.jpg');">
                <div class="panel-overlay"></div>
                <div class="panel-content">
                    <div class="mana-icon"><img src="../IMG/Mountain.png    " alt="Rojo"></div>
                    <h3>Caos</h3>
                    <p class="description">Emoción, fuego y destrucción rápida. Actúa sin pensar.</p>
                </div>
            </div>
            <div class="mana-panel green" style="background-image: url('../IMG/iko-272-forest-danner-1024x752.png');">
                <div class="panel-overlay"></div>
                <div class="panel-content">
                    <div class="mana-icon"><img src="../IMG/Forest.png" alt="Verde"></div>
                    <h3>Poder</h3>
                    <p class="description">Crecimiento, instinto y fuerza bruta. Las criaturas más grandes.</p>
                </div>
            </div>
        </div>

        <div class="rules-wrapper">
            <div class="rule-block">
                
                <div class="cost-explanation-box">
                    
                    <h2 class="section-title-inside">¿Cómo jugar una carta?</h2>
                    <hr class="divider">

                    <div class="layout-horizontal">
                        
                        <div class="left-side">
                            <img src="../IMG/scaveging.png" alt="Scavenging Ooze" class="real-card-img">
                        </div>

                        <div class="right-side">
                            <h3>1. El Costo de Maná</h3>
                            <p>
                                Mira la esquina superior derecha. El costo es: 
                                <span class="circle">1</span><span class="circle green">G</span>
                            </p>
                            <ul class="mana-list">
                                <li>
                                    <span class="circle green">G</span> <strong>Maná Verde:</strong> 
                                    Gira 1 tierra <em>Bosque</em>.
                                </li>
                                <li>
                                    <span class="circle">1</span> <strong>Maná Genérico:</strong> 
                                    Paga con 1 maná de <em>cualquier color</em>.
                                </li>
                            </ul>
                            <div class="total-cost">
                                <strong>Total:</strong> 2 Tierras (Mínimo 1 Bosque).
                            </div>
                            
                            <h3 style="margin-top: 25px;">2. Girar (Tapping)</h3>
                            <p>
                                <strong>"Girar"</strong> tus tierras 90° indica que usaste su energía. 
                                Se enderezan en tu próximo turno.
                            </p>
                        </div>

                    </div> </div> </div>
        </div>

        <style>
    .advanced-learn-section {
        margin: 4rem auto;
        max-width: 1200px;
        padding: 0 5%;
    }
    .learn-header {
        text-align: center;
        margin-bottom: 3rem;
    }
    .learn-header h2 {
        color: #d4af37;
        font-family: 'Cinzel', serif;
        font-size: 2.5rem;
        margin-bottom: 10px;
        border-bottom: 1px solid #334155;
        padding-bottom: 15px;
        display: inline-block;
    }
    .learn-header p {
        color: #94a3b8;
        font-size: 1.1rem;
        max-width: 600px;
        margin: 0 auto;
    }
    
    .strategy-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
    }
    
    .strategy-card {
        background: #1e293b;
        border: 1px solid #334155;
        border-radius: 12px;
        padding: 2rem;
        transition: all 0.3s ease;
        box-shadow: 0 10px 20px rgba(0,0,0,0.2);
    }
    .strategy-card:hover {
        border-color: #10b981;
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(16, 185, 129, 0.1);
    }
    
    .strategy-card.stack-card {
        grid-column: 1 / -1; /* Ocupa todo el ancho al final */
        background: linear-gradient(145deg, #1e293b, #0f172a);
        border-left: 4px solid #d4af37;
    }
    
    .strategy-icon {
        font-size: 2.5rem;
        margin-bottom: 1rem;
    }
    
    .strategy-card h3 {
        color: #f8fafc;
        font-size: 1.3rem;
        margin-bottom: 1.5rem;
        font-weight: 700;
        letter-spacing: 0.5px;
    }
    
    .strategy-card ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .strategy-card li {
        color: #cbd5e1;
        margin-bottom: 1.2rem;
        line-height: 1.5;
        font-size: 0.95rem;
        padding-left: 20px;
        position: relative;
    }
    .strategy-card li::before {
        content: "▶";
        position: absolute;
        left: 0;
        color: #10b981;
        font-size: 0.8rem;
        top: 2px;
    }
    
    .strategy-card li strong {
        color: #d4af37;
        font-weight: 600;
    }

    .stack-content {
        display: flex;
        gap: 30px;
        align-items: center;
    }
    .stack-text { flex: 1; color: #cbd5e1; line-height: 1.6; }
    .stack-highlight {
        background: rgba(212, 175, 55, 0.1);
        padding: 15px;
        border-radius: 8px;
        border: 1px dashed #d4af37;
        color: #f8fafc;
        font-style: italic;
        margin-top: 15px;
    }
    
    @media (max-width: 768px) {
        .stack-content { flex-direction: column; }
    }
</style>

<div class="advanced-learn-section fade-in">
    <div class="learn-header">
        <h2>Sinergias y Estrategia</h2>
        <p>Conocer los colores es solo el principio. El verdadero poder en el Multiverso radica en cómo combinas tus cartas.</p>
    </div>

    <div class="strategy-grid">
        
        <div class="strategy-card">
            <div class="strategy-icon">⚙️</div>
            <h3>Sinergias Populares</h3>
            <ul>
                <li><strong>Tribal / Tipos de Criatura:</strong> Un mazo enfocado en potenciar un solo tipo de criatura. Si juegas un mazo de <em>Elfos</em>, <em>Goblins</em> o <em>Zombies</em>, todas tus cartas se darán bonos entre sí.</li>
                <li><strong>Aristócratas:</strong> Estrategia basada en sacrificar tus propias criaturas a propósito para detonar efectos que drenan vida al oponente o te hacen robar cartas.</li>
                <li><strong>Flicker / Blink:</strong> Exiliar tus propias criaturas y regresarlas al campo de batalla al instante para volver a aprovechar sus efectos de entrada (ETB).</li>
            </ul>
        </div>

        <div class="strategy-card">
            <div class="strategy-icon">⚔️</div>
            <h3>Arquetipos de Mazo</h3>
            <ul>
                <li><strong>Aggro (Agresivo):</strong> Busca ganar lo más rápido posible usando criaturas baratas y veloces antes de que el rival pueda armar su defensa.</li>
                <li><strong>Control:</strong> Su objetivo es sobrevivir. Usa contrahechizos y eliminación de cartas para frustrar los planes del rival y ganar en el juego largo.</li>
                <li><strong>Combo:</strong> Mazos construidos alrededor de 2 o más cartas específicas que, al juntarse en el campo, crean un ciclo infinito que gana el juego al instante.</li>
                <li><strong>Midrange:</strong> El punto medio. Es más lento que Aggro pero más rápido que Control, adaptándose a lo que necesite la partida.</li>
            </ul>
        </div>

        <div class="strategy-card stack-card">
            <h3>⚡ El Concepto Vital: La Pila (The Stack)</h3>
            <div class="stack-content">
                <div class="stack-text">
                    <p>En Magic, cuando juegas una carta o activas una habilidad, <strong>no ocurre inmediatamente</strong>. En su lugar, se coloca en un espacio imaginario llamado "La Pila".</p>
                    <p style="margin-top: 10px;">Antes de que esa carta haga efecto (se resuelva), todos los demás jugadores tienen la oportunidad de "responder" lanzando hechizos instantáneos o activando habilidades, poniéndolos <em>encima</em> del tuyo en La Pila.</p>
                    <div class="stack-highlight">
                        <strong>Regla de Oro (LIFO):</strong> El último hechizo en entrar a La Pila es el primero en salir y hacer efecto.
                    </div>
                </div>
                <div class="stack-text" style="background: rgba(0,0,0,0.3); padding: 20px; border-radius: 8px;">
                    <strong style="color: #10b981;">Ejemplo de combate:</strong><br><br>
                    <strong>1. Tú:</strong> Lanzas un hechizo para darle +3/+3 a tu criatura.<br>
                    <strong>2. Oponente:</strong> Responde lanzando un "Relámpago" que hace 3 de daño a tu criatura.<br>
                    <strong>Resolución:</strong> Como el Relámpago fue el último en jugarse, se resuelve primero. Tu criatura muere antes de que pueda recibir tu bono de +3/+3.
                </div>
            </div>
        </div>

    </div>
</div>

        <div class="rule-block full-width" style="margin-top: 3rem;">
            <h2>Mecánicas de Juego</h2>
            
            <div class="board-and-rules-container">
                
                <div class="board-column">
                    <p style="text-align: center; margin-bottom: 15px; color: #aaa; font-size: 0.9rem;">
                        Distribución táctica: Criaturas al frente, Tierras atrás.
                    </p>
                    
                    <div class="arena-board-layout">
                        <div class="main-field">
                            <div class="digital-zone combat-zone">
                                <span class="zone-label">⚔️ Zona de Combate (Criaturas)</span>
                                <div class="card-container">
                                    <div class="mini-card creature"></div>
                                    <div class="mini-card creature tapped"></div> <div class="mini-card creature"></div>
                                </div>
                            </div>

                            <div class="digital-zone lands-zone">
                                <span class="zone-label">💎 Zona de Maná (Tierras)</span>
                                <div class="card-container">
                                    <div class="mini-card land"></div>
                                    <div class="mini-card land"></div>
                                    <div class="mini-card land tapped"></div> <div class="mini-card land"></div>
                                </div>
                            </div>
                        </div>

                        <div class="side-field">
                            <div class="stack-zone">
                                <span class="zone-label" style="font-size: 0.6rem;">Deck</span>
                                <div class="mini-card deck-back"></div>
                            </div>
                            <div class="stack-zone">
                                <span class="zone-label" style="font-size: 0.6rem;">Grave</span>
                                <div class="mini-card grave"></div>
                            </div>
                        </div>

                        <div class="hand-zone">
                            <div class="hand-cards">
                                <div class="mini-card hand-card c1"></div>
                                <div class="mini-card hand-card c2"></div>
                                <div class="mini-card hand-card c3"></div>
                                <div class="mini-card hand-card c4"></div>
                                <div class="mini-card hand-card c5"></div>
                            </div>
                            <span class="hand-label">Tu Mano</span>
                        </div>
                    </div>
                </div>

                <div class="rules-column">
                    <div class="rules-card">
                        <h3>⏳ Fases del Turno</h3>
                        <ul class="phase-list">
                            <li><strong class="phase-name">1.  Mantenimineto</strong><span class="phase-desc">Endereza tierras y roba carta.</span></li>
                            <li><strong class="phase-name">2. Principal 1</strong><span class="phase-desc">Juega tierras, criaturas o conjuros.</span></li>
                            <li><strong class="phase-name combat">3. Combate</strong><span class="phase-desc">Ataca con tus criaturas enderezadas.</span></li>
                            <li><strong class="phase-name">4. Principal 2</strong><span class="phase-desc">Juega cartas extra antes de terminar.</span></li>
                            <li><strong class="phase-name">5. Final</strong><span class="phase-desc">El daño se cura, pasa el turno.</span></li>
                        </ul>
                    </div>

                    <div class="rules-card">
                        <h3>📜 Reglas de Oro</h3>
                        <ul class="basics-list">
                            <li>❤️ <strong>Vidas:</strong> Empiezas con 20. Si llegas a 0, pierdes.</li>
                            <li>🃏 <strong>Mazo:</strong> Mínimo 60 cartas. Si te quedas sin mazo, Pierdes.</li>
                            <li>🛑 <strong>Bloqueo:</strong> Tú atacas a jugadores, no a criaturas. El rival decide si bloquea.</li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>

    </section>

    <footer>
        <p>&copy; 2025 Jr Games. Magic: The Gathering es propiedad de Wizards of the Coast.</p>
    </footer>

    <script src="../JS/carousel.js"></script>
</body>
</html>