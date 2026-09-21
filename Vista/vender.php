<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Publicar Producto | Jr Games</title>
    <link rel="stylesheet" href="../CSS/global.css">
    <style>
        body { background-color: #0f172a; }
        .sell-container { padding: 3rem 5%; min-height: 80vh; display: flex; justify-content: center; }
        .sell-main-form { width: 100%; max-width: 800px; background: transparent; }
        .sell-main-title { color: #d4af37; font-size: 2.2rem; margin-bottom: 2rem; font-family: 'Cinzel', serif; border-bottom: 1px solid #334155; padding-bottom: 15px; text-align: center; }
        .form-section { background: #1e293b; padding: 2rem; border-radius: 12px; border: 1px solid #334155; margin-bottom: 25px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .section-header { display: flex; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #334155; padding-bottom: 15px; }
        .step-number { background: #10b981; color: #000; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-weight: bold; margin-right: 15px; font-size: 0.9rem; }
        .section-title-text { color: #f8fafc; font-size: 1.1rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
        .form-group { display: flex; flex-direction: column; gap: 8px; margin-bottom: 20px;}
        .form-group.full-width { grid-column: 1 / -1; }
        .form-label { color: #94a3b8; font-size: 0.85rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
        .form-input { width: 100%; padding: 14px 16px; background: #0f172a; border: 1px solid #475569; color: #f8fafc; border-radius: 8px; font-family: 'Inter', sans-serif; font-size: 0.95rem; transition: all 0.2s ease; box-sizing: border-box; }
        .form-input:focus { outline: none; border-color: #10b981; box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2); }
        select.form-input { cursor: pointer; appearance: auto; }
        .checkbox-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); gap: 12px; }
        .check-card { background: #0f172a; border: 1px solid #475569; padding: 12px; border-radius: 8px; display: flex; align-items: center; gap: 10px; cursor: pointer; transition: 0.2s; }
        .check-card:hover { border-color: #10b981; }
        .check-card input[type="checkbox"] { width: 16px; height: 16px; accent-color: #10b981; cursor: pointer; }
        .check-card span { color: #f8fafc; font-size: 0.9rem; font-weight: 500; }
        
        /* Modificación para que la zona de fotos quepa doble */
        .upload-zone { border: 2px dashed #475569; padding: 2rem 1rem; text-align: center; border-radius: 12px; background: #0f172a; cursor: pointer; transition: all 0.3s ease; height: 100%; display: flex; flex-direction: column; justify-content: center; align-items: center;}
        .upload-zone:hover { border-color: #10b981; background: rgba(16, 185, 129, 0.05); }
        .upload-zone span { display: block; color: #94a3b8; font-size: 0.9rem; margin-top: 10px; }
        .upload-icon { font-size: 2.5rem; color: #d4af37; }
        
        .action-buttons { display: flex; gap: 20px; margin-top: 30px; }
        .btn-submit, .btn-cancel { flex: 1; padding: 15px; border-radius: 8px; font-weight: bold; text-align: center; text-transform: uppercase; letter-spacing: 1px; cursor: pointer; transition: 0.3s; font-size: 1rem; }
        .btn-submit { background: #10b981; color: #fff; border: none; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3); }
        .btn-submit:hover { background: #059669; transform: translateY(-2px); }
        .btn-cancel { background: transparent; border: 2px solid #475569; color: #94a3b8; display: block; line-height: 1.5;}
        .btn-cancel:hover { border-color: #ef4444; color: #ef4444; }
        @media (max-width: 600px) { .form-row { grid-template-columns: 1fr; gap: 0;} .action-buttons { flex-direction: column; } }
    </style>
</head>
<body>

    <header>
        <div class="logo"><a href="index.php">Jr Games</a></div>
        <nav>
            <ul>
                <li><a href="index.php">Inicio</a></li>
                <li><a href="mercado.php">Mercado</a></li>
                <li><a href="comunidad.php">Comunidad</a></li>
            </ul>
        </nav>
        <div class="user-actions">
            <span style="color: var(--accent-gold); font-weight: 600;">Hola, <?php echo htmlspecialchars($_SESSION['nombre']); ?></span>
        </div>
    </header>

    <div class="sell-container">
        <main class="sell-main-form fade-in">
            <h2 class="sell-main-title">Publicar en el Multiverso</h2>
            
            <form action="../Controlador/procesar_venta.php" method="POST" enctype="multipart/form-data">
                
                <div class="form-section">
                    <div class="section-header"><div class="step-number">01</div><div class="section-title-text">Identidad de la Carta</div></div>
                    <div class="form-group full-width">
                        <label class="form-label">Nombre Completo</label>
                        <input type="text" name="nombre_carta" class="form-input" placeholder="Ej: Delver of Secrets" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Categoría de Tienda</label>
                            <select name="tipo_producto" class="form-input">
                                <option value="carta">Carta Suelta (Single)</option>
                                <option value="mazo">Mazo Completo</option>
                                <option value="sobre">Caja Sellada / Sobre</option>
                                <option value="accesorio">Accesorio</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Edición / Set</label>
                            <input type="text" name="edicion" class="form-input" placeholder="Ej: Innistrad">
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="section-header"><div class="step-number">02</div><div class="section-title-text">Especificaciones Técnicas</div></div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Condición (Estado)</label>
                            <select name="estado" class="form-input">
                                <option value="NM">Near Mint (Como nueva)</option>
                                <option value="LP">Lightly Played (Poco uso)</option>
                                <option value="MP">Moderately Played (Desgastada)</option>
                                <option value="HP">Heavily Played (Dañada)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Rareza</label>
                            <select name="rareza" class="form-input">
                                <option value="Common">Común</option>
                                <option value="Uncommon">Infrecuente</option>
                                <option value="Rare">Rara</option>
                                <option value="Mythic">Mítica</option>
                                <option value="Special">Especial / Promo</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group full-width">
                        <label class="form-label">Línea de Tipo (Magic: The Gathering)</label>
                        <select name="tipo_carta_mtg" class="form-input">
                            <option value="No aplica">No aplica (Es un mazo, sobre o accesorio)</option>
                            <optgroup label="Criaturas">
                                <option value="Creature">Creature</option>
                                <option value="Legendary Creature">Legendary Creature</option>
                                <option value="Artifact Creature">Artifact Creature</option>
                                <option value="Legendary Artifact Creature">Legendary Artifact Creature</option>
                                <option value="Enchantment Creature">Enchantment Creature</option>
                            </optgroup>
                            <optgroup label="Hechizos">
                                <option value="Instant">Instant</option>
                                <option value="Sorcery">Sorcery</option>
                            </optgroup>
                            <optgroup label="Permanentes">
                                <option value="Artifact">Artifact</option>
                                <option value="Legendary Artifact">Legendary Artifact</option>
                                <option value="Enchantment">Enchantment</option>
                                <option value="Legendary Enchantment">Legendary Enchantment</option>
                                <option value="Planeswalker">Planeswalker</option>
                                <option value="Legendary Planeswalker">Legendary Planeswalker</option>
                                <option value="Battle">Battle</option>
                            </optgroup>
                            <optgroup label="Tierras">
                                <option value="Basic Land">Basic Land</option>
                                <option value="Land">Land</option>
                                <option value="Legendary Land">Legendary Land</option>
                                <option value="Artifact Land">Artifact Land</option>
                            </optgroup>
                        </select>
                    </div>

                    <div class="form-group full-width" style="margin-top: 15px;">
                        <label class="form-label" style="margin-bottom: 10px;">Formatos Legales Permitidos</label>
                        <div class="checkbox-grid">
                            <label class="check-card"><input type="checkbox" name="formatos[]" value="Standard"> <span>Standard</span></label>
                            <label class="check-card"><input type="checkbox" name="formatos[]" value="Commander"> <span>Commander</span></label>
                            <label class="check-card"><input type="checkbox" name="formatos[]" value="Modern"> <span>Modern</span></label>
                            <label class="check-card"><input type="checkbox" name="formatos[]" value="Pioneer"> <span>Pioneer</span></label>
                            <label class="check-card"><input type="checkbox" name="formatos[]" value="Legacy"> <span>Legacy</span></label>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="section-header"><div class="step-number">03</div><div class="section-title-text">Datos de Venta</div></div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Precio Unitario (USD)</label>
                            <input type="number" name="precio" class="form-input" step="0.01" placeholder="0.00" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Cantidad (Stock)</label>
                            <input type="number" name="stock" class="form-input" value="1" min="1" required>
                        </div>
                    </div>
                    <div class="form-group full-width">
                        <label class="form-label">Notas del Vendedor (Detalles)</label>
                        <textarea name="detalles" class="form-input" rows="3" style="resize: vertical;" placeholder="Ej: Foil, carta firmada, idioma japonés..."></textarea>
                    </div>
                </div>

                <div class="form-section">
                    <div class="section-header"><div class="step-number">04</div><div class="section-title-text">Apariencia Visual</div></div>
                    
                    <div class="form-group full-width" style="margin-bottom: 25px;">
                        <label class="form-label">¿Es una carta transformable (Doble Cara)?</label>
                        <select name="es_doble_cara" id="es_doble_cara" class="form-input" onchange="toggleDobleCara()" style="border-color: #d4af37;">
                            <option value="0">No, tiene el reverso clásico de Magic</option>
                            <option value="1">Sí, es de doble cara (Innistrad, Ixalan, etc.)</option>
                        </select>
                    </div>

                    <div class="form-row" id="zonas-fotos">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" style="text-align: center;">Cara Frontal</label>
                            <div class="upload-zone" onclick="document.getElementById('file-frente').click()">
                                <div class="upload-icon">📷</div>
                                <span id="name-frente">Sube el frente</span>
                                <input type="file" name="imagen" id="file-frente" accept="image/*" style="display:none;" required onchange="actualizarNombre(this, 'name-frente')">
                            </div>
                        </div>

                        <div class="form-group" id="zona-reverso" style="display:none; margin-bottom: 0;">
                            <label class="form-label" style="text-align: center; color: #d4af37;">Cara Trasera (Transformación)</label>
                            <div class="upload-zone" onclick="document.getElementById('file-reverso').click()">
                                <div class="upload-icon">🔄</div>
                                <span id="name-reverso">Sube la transformación</span>
                                <input type="file" name="imagen_reverso" id="file-reverso" accept="image/*" style="display:none;" onchange="actualizarNombre(this, 'name-reverso')">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="action-buttons">
                    <a href="mercado.php" class="btn-cancel" style="display:flex; align-items:center; justify-content:center;">Cancelar</a>
                    <button type="submit" class="btn-submit">Publicar Ahora</button>
                </div>
            </form>
        </main>
    </div>

    <script>
        function toggleDobleCara() {
            const select = document.getElementById('es_doble_cara').value;
            const zonaReverso = document.getElementById('zona-reverso');
            const fileReverso = document.getElementById('file-reverso');
            
            if(select === "1") {
                zonaReverso.style.display = 'flex';
                fileReverso.setAttribute('required', 'required'); // Obliga a subirla
            } else {
                zonaReverso.style.display = 'none';
                fileReverso.removeAttribute('required');
                fileReverso.value = ''; // Limpia el archivo si se arrepiente
                document.getElementById('name-reverso').innerHTML = "Sube la transformación";
            }
        }

        function actualizarNombre(input, spanId) {
            const fileNameSpan = document.getElementById(spanId);
            if (input.files && input.files.length > 0) {
                fileNameSpan.innerHTML = `<strong style="color:#10b981;">Archivo:</strong><br>${input.files[0].name}`;
            } else {
                fileNameSpan.innerHTML = "Sube la imagen";
            }
        }
    </script>
</body>
</html>
