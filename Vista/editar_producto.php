<?php
session_start();
include '../Modelo/db.php';

if (!isset($_SESSION['usuario_id'])) { header("Location: login.php"); exit(); }
if (!isset($_GET['id'])) { header("Location: perfil.php"); exit(); }

$id_producto = $conn->real_escape_string($_GET['id']);
$id_usuario = $_SESSION['usuario_id'];
$es_admin = (isset($_SESSION['rol']) && $_SESSION['rol'] == 'admin');

if ($es_admin) {
    $sql = "SELECT * FROM publicaciones WHERE id = '$id_producto'";
} else {
    $sql = "SELECT * FROM publicaciones WHERE id = '$id_producto' AND id_usuario = '$id_usuario'";
}

$result = $conn->query($sql);
if ($result->num_rows == 0) {
    echo "<script>alert('Producto no encontrado.'); window.location.href='perfil.php';</script>"; exit();
}

$producto = $result->fetch_assoc();
$formatos_guardados = isset($producto['formatos']) ? explode(", ", $producto['formatos']) : [];
$imagen_ruta = !empty($producto['imagen']) ? "../".$producto['imagen'] : '../IMG/cartas/scavenging_ooze.png';
$t_mtg = isset($producto['tipo_carta_mtg']) ? $producto['tipo_carta_mtg'] : 'No aplica';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto | Jr Games</title>
    <link rel="stylesheet" href="../CSS/global.css">
    <style>
        body { background-color: #0f172a; }
        .sell-container { padding: 3rem 5%; min-height: 80vh; }
        .edit-layout { display: grid; grid-template-columns: 320px 1fr; gap: 40px; width: 100%; max-width: 1200px; margin: 0 auto; align-items: start; }
        .edit-sidebar { background: #1e293b; padding: 2rem; border-radius: 16px; border: 1px solid #334155; text-align: center; position: sticky; top: 100px; box-shadow: 0 10px 30px -5px rgba(0,0,0,0.5); }
        .edit-sidebar img { width: 100%; max-width: 260px; border-radius: 4.75% / 3.5%; box-shadow: 0 15px 35px rgba(0,0,0,0.6); margin-bottom: 1.5rem; }
        .card-preview-title { color: #f8fafc; font-size: 1.2rem; margin-bottom: 10px; font-family: 'Inter', sans-serif; font-weight: 700; }
        .card-preview-price { color: #d4af37; font-size: 1.8rem; font-weight: 700; margin-bottom: 20px;}
        .card-preview-price span { font-size: 1rem; color: #94a3b8; }
        .edit-stats { display: flex; justify-content: space-around; padding-top: 1.5rem; border-top: 1px solid #334155; }
        .stat-box { display: flex; flex-direction: column; gap: 5px; }
        .stat-label { color: #94a3b8; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; }
        .stat-value { color: #fff; font-size: 1.1rem; font-weight: bold; }
        .edit-main-form { background: transparent; }
        .edit-main-title { color: #d4af37; font-size: 2.2rem; margin-bottom: 2rem; font-family: 'Cinzel', serif; border-bottom: 1px solid #334155; padding-bottom: 15px; }
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
        .action-buttons { display: flex; gap: 20px; margin-top: 30px; }
        .btn-submit, .btn-cancel { flex: 1; padding: 15px; border-radius: 8px; font-weight: bold; text-align: center; text-transform: uppercase; letter-spacing: 1px; cursor: pointer; transition: 0.3s; font-size: 1rem; }
        .btn-submit { background: #10b981; color: #fff; border: none; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3); }
        .btn-submit:hover { background: #059669; transform: translateY(-2px); }
        .btn-cancel { background: transparent; border: 2px solid #475569; color: #94a3b8; display: block; line-height: 1.5; }
        .btn-cancel:hover { border-color: #ef4444; color: #ef4444; }
        @media (max-width: 900px) { .edit-layout { grid-template-columns: 1fr; } .edit-sidebar { position: static; margin-bottom: 2rem; } .form-row { grid-template-columns: 1fr; gap: 0;} .action-buttons { flex-direction: column; } }
    </style>
</head>
<body>

    <header>
        <div class="logo"><a href="index.php">Jr Games</a></div>
        <nav><ul><li><a href="perfil.php" style="color: var(--accent-gold);">Volver al Perfil</a></li></ul></nav>
        <div class="user-actions">
            <span style="color: var(--accent-gold); font-weight: 600;">Hola, <?php echo htmlspecialchars($_SESSION['nombre']); ?></span>
        </div>
    </header>

    <div class="sell-container">
        <div class="edit-layout fade-in">
            
            <aside class="edit-sidebar">
                <img src="<?php echo $imagen_ruta; ?>" alt="Preview">
                <h3 class="card-preview-title"><?php echo htmlspecialchars($producto['nombre_carta']); ?></h3>
                <div class="card-preview-price">$<?php echo number_format($producto['precio'], 2); ?> <span>USD</span></div>
                <div class="edit-stats">
                    <div class="stat-box"><span class="stat-label">Stock</span> <span class="stat-value"><?php echo $producto['stock']; ?></span></div>
                    <div class="stat-box"><span class="stat-label">Estado</span> <span class="stat-value"><?php echo $producto['estado']; ?></span></div>
                </div>
            </aside>

            <main class="edit-main-form">
                <h2 class="edit-main-title">Ajustes del Producto</h2>
                
                <form action="../Controlador/guardar_edicion.php" method="POST">
                    <input type="hidden" name="id_producto" value="<?php echo $producto['id']; ?>">

                    <div class="form-section">
                        <div class="section-header"><div class="step-number">01</div><div class="section-title-text">Identidad de la Carta</div></div>
                        <div class="form-group full-width">
                            <label class="form-label">Nombre Completo</label>
                            <input type="text" name="nombre_carta" class="form-input" value="<?php echo htmlspecialchars($producto['nombre_carta']); ?>" required>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Categoría de Tienda</label>
                                <select name="tipo_producto" class="form-input">
                                    <option value="carta" <?php if($producto['tipo_producto']=='carta') echo 'selected'; ?>>Carta Suelta (Single)</option>
                                    <option value="mazo" <?php if($producto['tipo_producto']=='mazo') echo 'selected'; ?>>Mazo Completo</option>
                                    <option value="sobre" <?php if($producto['tipo_producto']=='sobre') echo 'selected'; ?>>Caja Sellada / Sobre</option>
                                    <option value="accesorio" <?php if($producto['tipo_producto']=='accesorio') echo 'selected'; ?>>Accesorio</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Edición / Set</label>
                                <input type="text" name="edicion" class="form-input" value="<?php echo htmlspecialchars($producto['edicion']); ?>">
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <div class="section-header"><div class="step-number">02</div><div class="section-title-text">Especificaciones Técnicas</div></div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Condición (Estado)</label>
                                <select name="estado" class="form-input">
                                    <option value="NM" <?php if($producto['estado']=='NM') echo 'selected'; ?>>Near Mint (Como nueva)</option>
                                    <option value="LP" <?php if($producto['estado']=='LP') echo 'selected'; ?>>Lightly Played (Poco uso)</option>
                                    <option value="MP" <?php if($producto['estado']=='MP') echo 'selected'; ?>>Moderately Played (Desgastada)</option>
                                    <option value="HP" <?php if($producto['estado']=='HP') echo 'selected'; ?>>Heavily Played (Dañada)</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Rareza</label>
                                <select name="rareza" class="form-input">
                                    <option value="Common" <?php if(isset($producto['rareza']) && $producto['rareza']=='Common') echo 'selected'; ?>>Común</option>
                                    <option value="Uncommon" <?php if(isset($producto['rareza']) && $producto['rareza']=='Uncommon') echo 'selected'; ?>>Infrecuente</option>
                                    <option value="Rare" <?php if(isset($producto['rareza']) && $producto['rareza']=='Rare') echo 'selected'; ?>>Rara</option>
                                    <option value="Mythic" <?php if(isset($producto['rareza']) && $producto['rareza']=='Mythic') echo 'selected'; ?>>Mítica</option>
                                    <option value="Special" <?php if(isset($producto['rareza']) && $producto['rareza']=='Special') echo 'selected'; ?>>Especial / Promo</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group full-width">
                            <label class="form-label">Línea de Tipo (Magic: The Gathering)</label>
                            <select name="tipo_carta_mtg" class="form-input">
                                <option value="No aplica" <?php if($t_mtg == 'No aplica') echo 'selected'; ?>>No aplica (Mazo/Sobre/Accesorio)</option>
                                <optgroup label="Criaturas">
                                    <option value="Creature" <?php if($t_mtg == 'Creature') echo 'selected'; ?>>Creature</option>
                                    <option value="Legendary Creature" <?php if($t_mtg == 'Legendary Creature') echo 'selected'; ?>>Legendary Creature</option>
                                    <option value="Artifact Creature" <?php if($t_mtg == 'Artifact Creature') echo 'selected'; ?>>Artifact Creature</option>
                                    <option value="Legendary Artifact Creature" <?php if($t_mtg == 'Legendary Artifact Creature') echo 'selected'; ?>>Legendary Artifact Creature</option>
                                    <option value="Enchantment Creature" <?php if($t_mtg == 'Enchantment Creature') echo 'selected'; ?>>Enchantment Creature</option>
                                </optgroup>
                                <optgroup label="Hechizos">
                                    <option value="Instant" <?php if($t_mtg == 'Instant') echo 'selected'; ?>>Instant</option>
                                    <option value="Sorcery" <?php if($t_mtg == 'Sorcery') echo 'selected'; ?>>Sorcery</option>
                                </optgroup>
                                <optgroup label="Permanentes">
                                    <option value="Artifact" <?php if($t_mtg == 'Artifact') echo 'selected'; ?>>Artifact</option>
                                    <option value="Legendary Artifact" <?php if($t_mtg == 'Legendary Artifact') echo 'selected'; ?>>Legendary Artifact</option>
                                    <option value="Enchantment" <?php if($t_mtg == 'Enchantment') echo 'selected'; ?>>Enchantment</option>
                                    <option value="Legendary Enchantment" <?php if($t_mtg == 'Legendary Enchantment') echo 'selected'; ?>>Legendary Enchantment</option>
                                    <option value="Planeswalker" <?php if($t_mtg == 'Planeswalker') echo 'selected'; ?>>Planeswalker</option>
                                    <option value="Legendary Planeswalker" <?php if($t_mtg == 'Legendary Planeswalker') echo 'selected'; ?>>Legendary Planeswalker</option>
                                    <option value="Battle" <?php if($t_mtg == 'Battle') echo 'selected'; ?>>Battle</option>
                                </optgroup>
                                <optgroup label="Tierras">
                                    <option value="Basic Land" <?php if($t_mtg == 'Basic Land') echo 'selected'; ?>>Basic Land</option>
                                    <option value="Land" <?php if($t_mtg == 'Land') echo 'selected'; ?>>Land</option>
                                    <option value="Legendary Land" <?php if($t_mtg == 'Legendary Land') echo 'selected'; ?>>Legendary Land</option>
                                    <option value="Artifact Land" <?php if($t_mtg == 'Artifact Land') echo 'selected'; ?>>Artifact Land</option>
                                </optgroup>
                            </select>
                        </div>

                        <div class="form-group full-width" style="margin-top: 15px;">
                            <label class="form-label" style="margin-bottom: 10px;">Formatos Legales Permitidos</label>
                            <div class="checkbox-grid">
                                <label class="check-card"><input type="checkbox" name="formatos[]" value="Standard" <?php if(in_array("Standard", $formatos_guardados)) echo "checked"; ?>> <span>Standard</span></label>
                                <label class="check-card"><input type="checkbox" name="formatos[]" value="Commander" <?php if(in_array("Commander", $formatos_guardados)) echo "checked"; ?>> <span>Commander</span></label>
                                <label class="check-card"><input type="checkbox" name="formatos[]" value="Modern" <?php if(in_array("Modern", $formatos_guardados)) echo "checked"; ?>> <span>Modern</span></label>
                                <label class="check-card"><input type="checkbox" name="formatos[]" value="Pioneer" <?php if(in_array("Pioneer", $formatos_guardados)) echo "checked"; ?>> <span>Pioneer</span></label>
                                <label class="check-card"><input type="checkbox" name="formatos[]" value="Legacy" <?php if(in_array("Legacy", $formatos_guardados)) echo "checked"; ?>> <span>Legacy</span></label>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <div class="section-header"><div class="step-number">03</div><div class="section-title-text">Datos de Venta</div></div>
                        <div class="form-group">
                            <label class="form-label">Precio Unitario (USD)</label>
                            <input type="number" name="precio" class="form-input" step="0.01" value="<?php echo $producto['precio']; ?>" required>
                        </div>
                        <div class="form-group full-width">
                            <label class="form-label">Notas del Vendedor</label>
                            <textarea name="detalles" class="form-input" rows="4" style="resize: vertical;"><?php echo htmlspecialchars($producto['detalles']); ?></textarea>
                        </div>
                    </div>

                    <div class="action-buttons">
                        <a href="perfil.php" class="btn-cancel" style="display:flex; align-items:center; justify-content:center;">Descartar Cambios</a>
                        <button type="submit" class="btn-submit">Actualizar Producto</button>
                    </div>
                </form>
            </main>
        </div>
    </div>
</body>
</html>