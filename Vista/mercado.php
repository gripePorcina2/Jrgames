<?php
session_start();
include '../Modelo/db.php';
$sql = "SELECT * FROM publicaciones ORDER BY fecha_publicacion DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mercado | Jr Games</title>
    <link rel="stylesheet" href="../CSS/global.css">
    <link rel="stylesheet" href="../CSS/mercado.css">
</head>
<body>

    <header>
        <div class="logo"><a href="index.php">Jr Games</a></div>
        <nav>
            <ul>
                <li><a href="index.php">Inicio</a></li>
                <li><a href="aprender.php">Aprende</a></li>
                <li><a href="mercado.php" style="color: var(--accent-gold);">Mercado</a></li>
                <li><a href="comunidad.php">Comunidad</a></li>
                <?php if (isset($_SESSION['usuario_id'])): ?><li><a href="perfil.php">Mi Perfil</a></li><?php endif; ?>
            </ul>
        </nav>
        <div class="user-actions" style="display: flex; gap: 15px; align-items: center;">
            <button type="button" class="btn-modern btn-outline" onclick="abrirCarrito()">🛒 Carrito</button>
            <?php if (isset($_SESSION['usuario_id'])): ?>
                <span style="color: var(--accent-gold); font-weight: 600; font-size: 0.95rem;">Hola, <?php echo htmlspecialchars($_SESSION['nombre']); ?></span>
                <a href="logout.php" class="btn-danger-outline">Salir</a>
            <?php else: ?>
                <a href="login.php" class="btn-modern btn-outline">Ingresar</a>
            <?php endif; ?>
        </div>
    </header>

    <div class="market-wrapper fade-in">
        <div class="market-header">
            <h2>Explorar Cartas</h2>
            <?php if (isset($_SESSION['usuario_id'])): ?><a href="vender.php" class="btn-modern btn-primary">+ Publicar Producto</a><?php endif; ?>
        </div>

        <div class="market-container">
            <aside class="filters">
               <div class="filter-group">
                   <h3>Buscar</h3>
                   <input type="text" id="searchInput" class="filter-input" placeholder="Ej: Sheoldred...">
               </div>
               <div class="filter-group">
                   <h3>Categoría</h3>
                   <label><input type="checkbox" class="filter-category" value="carta"> Cartas Sueltas</label>
                   <label><input type="checkbox" class="filter-category" value="mazo"> Mazos</label>
                   <label><input type="checkbox" class="filter-category" value="sobre"> Sobres Sellados</label>
                   <label><input type="checkbox" class="filter-category" value="accesorio"> Accesorios</label>
               </div>
               <div class="filter-group">
                   <h3>Estado</h3>
                   <label><input type="checkbox" class="filter-condition" value="NM"> Near Mint (NM)</label>
                   <label><input type="checkbox" class="filter-condition" value="LP"> Lightly Played (LP)</label>
                   <label><input type="checkbox" class="filter-condition" value="MP"> Moderately Played (MP)</label>
                   <label><input type="checkbox" class="filter-condition" value="HP"> Heavily Played (HP)</label>
               </div>
            </aside>

            <main class="products-grid" id="productsGrid">
                <?php 
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) { 
                        $es_mia = (isset($_SESSION['usuario_id']) && $row['id_usuario'] == $_SESSION['usuario_id']);
                        $agotado = ($row['stock'] <= 0 || $row['vendido'] == 1);
                        $tipo = $row['tipo_producto'];
                        $es_doble = isset($row['es_doble_cara']) ? $row['es_doble_cara'] : 0;
                        
                        $img_frente = !empty($row['imagen']) ? "../".$row['imagen'] : '../IMG/cartas/scavenging_ooze.png';
                        $img_atras = ($es_doble == 1 && !empty($row['imagen_reverso'])) ? "../".$row['imagen_reverso'] : "../IMG/cartas/Reverso.png";

                        $attr_id = htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8');
                        $attr_nombre = htmlspecialchars($row['nombre_carta'], ENT_QUOTES, 'UTF-8');
                        $attr_edicion = htmlspecialchars($row['edicion'], ENT_QUOTES, 'UTF-8');
                        $attr_rareza = htmlspecialchars($row['rareza'] ?? 'No especificada', ENT_QUOTES, 'UTF-8');
                        $attr_tipo = htmlspecialchars($tipo, ENT_QUOTES, 'UTF-8');
                        $attr_tipomtg = htmlspecialchars($row['tipo_carta_mtg'] ?? 'No aplica', ENT_QUOTES, 'UTF-8');
                        $attr_detalles = htmlspecialchars($row['detalles'] ?? 'Sin detalles', ENT_QUOTES, 'UTF-8');
                        $attr_formatos = htmlspecialchars($row['formatos'] ?? '', ENT_QUOTES, 'UTF-8');
                        $attr_imagen = htmlspecialchars($img_frente, ENT_QUOTES, 'UTF-8');
                        $attr_stock = htmlspecialchars($row['stock'], ENT_QUOTES, 'UTF-8');
                        $attr_estado = htmlspecialchars($row['estado'], ENT_QUOTES, 'UTF-8');
                ?>
                    <div class="card-item fade-in <?php echo $agotado ? 'agotado' : ''; ?>" 
                         data-name="<?php echo strtolower($attr_nombre); ?>" 
                         data-category="<?php echo $attr_tipo; ?>" 
                         data-condition="<?php echo $attr_estado; ?>"> 
                        
                        <div class="card-img-container">
                            <?php if($tipo == 'mazo'): ?><span class="badge mazo" style="position:absolute; top:10px; left:10px; z-index:10;">📦 MAZO</span>
                            <?php elseif($tipo == 'sobre'): ?><span class="badge sobre" style="position:absolute; top:10px; left:10px; z-index:10;">🧧 SOBRE</span><?php endif; ?>
                            
                            <?php if($tipo == 'carta' && $es_doble == 1): ?>
                                <div class="flip-layout">
                                    <div class="flip-front"><img src="<?php echo $img_frente; ?>"></div>
                                    <div class="flip-back"><img src="<?php echo $img_atras; ?>"></div>
                                </div>
                            <?php elseif($tipo == 'carta'): ?>
                                <div class="stacked-layout">
                                    <img src="../IMG/cartas/Reverso.png" class="card-stacked-back">
                                    <img src="<?php echo $img_frente; ?>" class="card-stacked-front">
                                </div>
                            <?php else: ?>
                                <img src="<?php echo $img_frente; ?>" class="card-single-img">
                            <?php endif; ?>
                        </div>

                        <div class="card-info">
                            <div class="card-set" style="margin-top: 10px;"><?php echo $row['edicion']; ?> • <?php echo $row['estado']; ?></div>
                            <h4 class="card-title"><?php echo $row['nombre_carta']; ?></h4>
                            <div class="price-container">
                                <span class="card-price">$<?php echo number_format($row['precio'], 2); ?></span> <span class="card-currency">USD</span>
                            </div>
                            <?php if(!$agotado): ?>
                                <div class="card-stock" style="margin-bottom: auto; font-size: 0.85rem; color: #94a3b8;">
                                    Disponibles: <strong style="color:#f8fafc; font-size: 1rem;"><?php echo $row['stock']; ?></strong>
                                </div>
                            <?php endif; ?>

                            <div class="card-actions" style="margin-top: 15px;">
                                <button type="button" class="btn-modern btn-outline btn-full" 
                                        data-titulo="<?php echo $attr_nombre; ?>" data-edicion="<?php echo $attr_edicion; ?>" 
                                        data-rareza="<?php echo $attr_rareza; ?>" data-tipo="<?php echo $attr_tipo; ?>" 
                                        data-tipomtg="<?php echo $attr_tipomtg; ?>" data-detalles="<?php echo $attr_detalles; ?>" 
                                        data-formatos="<?php echo $attr_formatos; ?>" data-imagen="<?php echo $attr_imagen; ?>" 
                                        data-stock="<?php echo $attr_stock; ?>" onclick="abrirModalDesdeData(this)">Info</button>

                                <?php if ($agotado): ?>
                                    <button type="button" class="btn-modern btn-full" style="background: var(--surface-300); color: #888; cursor: not-allowed;" disabled>AGOTADO</button>
                                <?php elseif ($es_mia): ?>
                                    <a href="perfil.php" class="btn-modern btn-full" style="background: var(--surface-300); text-align: center; font-size:0.8rem;">Tu producto</a>
                                <?php elseif (isset($_SESSION['rol']) && $_SESSION['rol'] == 'admin'): ?>
                                    <a href="../Controlador/eliminar_publicacion.php?id=<?php echo $row['id']; ?>" class="btn-danger-outline btn-full" style="text-align: center;" onclick="return confirm('¿Eliminar?');">Eliminar (Admin)</a>
                                <?php elseif (isset($_SESSION['usuario_id'])): ?>
                                    <button type="button" class="btn-modern btn-primary btn-full" 
                                            data-id="<?php echo $attr_id; ?>" data-nombre="<?php echo $attr_nombre; ?>" 
                                            data-precio="<?php echo $row['precio']; ?>" data-imagen="<?php echo $attr_imagen; ?>" 
                                            data-stock="<?php echo $row['stock']; ?>" onclick="addCartDesdeData(this)">Añadir</button>
                                <?php else: ?>
                                    <button type="button" class="btn-modern btn-primary btn-full" onclick="requiereLogin()">Añadir</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php } } else { echo "<div style='grid-column: 1 / -1; text-align: center; padding: 4rem;'><h3 style='color: var(--text-secondary);'>No hay productos en venta.</h3></div>"; } ?>
            </main>
        </div>
    </div>

    <div id="infoModal" class="modal-overlay" onclick="cerrarModal(event)"><div class="modal-content" id="modalContentTarget"></div></div>
    <div id="cartSidebar" class="cart-sidebar">
        <div class="cart-header"><h3>Tu Carrito</h3><span class="close-btn" style="position: static;" onclick="cerrarCarrito()">&times;</span></div>
        <div class="cart-items"></div>
        <div class="cart-footer"><div class="cart-total"><span>Total:</span> <span>$0.00 USD</span></div><button type="button" class="btn-modern btn-primary btn-full" onclick="abrirCheckout()">Proceder al Pago</button></div>
    </div>
    <div id="checkoutModal" class="checkout-modal" onclick="cerrarCheckoutEvent(event)">
        <div class="checkout-box" style="max-width: 800px;">
            <span class="close-btn" onclick="cerrarCheckout()">&times;</span>
            <h2 style="color: var(--accent-gold); margin-bottom: 20px; text-align: center;">Finalizar Pedido</h2>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
                <div>
                    <h3 style="color: #fff; font-size: 1rem; margin-bottom: 15px; border-bottom: 1px solid #333; padding-bottom: 5px;">📍 Dirección de Entrega</h3>
                    <input type="text" id="ship_nombre" class="checkout-input" placeholder="Nombre de quien recibe">
                    <input type="text" id="ship_direccion" class="checkout-input" placeholder="Calle, número y colonia">
                    <div class="grid-2">
                        <input type="text" id="ship_cp" class="checkout-input" placeholder="C.P.">
                        <input type="text" id="ship_ciudad" class="checkout-input" placeholder="Ciudad / Estado">
                    </div>
                </div>
               <div>
                    <h3 style="color: #fff; font-size: 1rem; margin-bottom: 15px; border-bottom: 1px solid #333; padding-bottom: 5px;">💳 Método de Pago</h3>
                    <div class="credit-card-preview" style="height: 150px; margin-bottom: 15px; padding: 15px;">
                        <div style="display: flex; justify-content: space-between; font-size: 0.8rem;"><span>Multiverse Bank</span><strong>VISA</strong></div>
                        <div style="font-family: monospace; font-size: 1.1rem; letter-spacing: 2px; margin-top: 20px;">**** **** **** 1234</div>
                        <div style="display: flex; justify-content: space-between; margin-top: 20px; font-size: 0.7rem;"><span>PLANESWALKER</span><span>12/28</span></div>
                    </div>
                    
                    <input type="text" id="cc_numero" class="checkout-input" placeholder="Número de Tarjeta (16 dígitos)" maxlength="16" style="margin-bottom: 15px;">
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                        <input type="text" id="cc_vencimiento" class="checkout-input" placeholder="MM/AA" maxlength="5">
                        <input type="password" id="cc_cvv" class="checkout-input" placeholder="CVV" maxlength="4">
                    </div>

                    <button type="button" class="btn-modern btn-primary btn-full" style="margin-top: 10px;" onclick="processPayment()">Confirmar y Pagar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // ==========================================
        // 1. SISTEMA DE FILTRADO INSTANTÁNEO
        // ==========================================
        document.addEventListener("DOMContentLoaded", () => {
            const searchInput = document.getElementById('searchInput');
            const categoryFilters = document.querySelectorAll('.filter-category');
            const conditionFilters = document.querySelectorAll('.filter-condition');
            const cards = document.querySelectorAll('.card-item');

            function applyFilters() {
                const searchTerm = searchInput.value.toLowerCase();
                
                // Obtenemos qué casillas están marcadas
                const checkedCategories = Array.from(categoryFilters).filter(cb => cb.checked).map(cb => cb.value);
                const checkedConditions = Array.from(conditionFilters).filter(cb => cb.checked).map(cb => cb.value);

                cards.forEach(card => {
                    const name = card.getAttribute('data-name');
                    const category = card.getAttribute('data-category');
                    const condition = card.getAttribute('data-condition');

                    // Lógica de coincidencia
                    let matchesSearch = name.includes(searchTerm);
                    let matchesCategory = checkedCategories.length === 0 || checkedCategories.includes(category);
                    let matchesCondition = checkedConditions.length === 0 || checkedConditions.includes(condition);

                    // Si cumple todos los filtros, se muestra. Si no, se oculta.
                    if (matchesSearch && matchesCategory && matchesCondition) {
                        card.style.display = ''; 
                    } else {
                        card.style.display = 'none';
                    }
                });
            }

            // Escuchamos cuando el usuario escribe o hace clic
            searchInput.addEventListener('input', applyFilters);
            categoryFilters.forEach(cb => cb.addEventListener('change', applyFilters));
            conditionFilters.forEach(cb => cb.addEventListener('change', applyFilters));
        });


        // ==========================================
        // 2. SISTEMA DE CARRITO Y MODALES
        // ==========================================
        let cart = [];

        function requiereLogin() {
            alert("¡Alto ahí, Planeswalker! Necesitas iniciar sesión o crear una cuenta para poder añadir cartas a tu carrito.");
            window.location.href = 'login.php';
        }

        function abrirModalDesdeData(btnElement) {
            const titulo = btnElement.getAttribute('data-titulo');
            const edicion = btnElement.getAttribute('data-edicion');
            const rareza = btnElement.getAttribute('data-rareza');
            const tipo = btnElement.getAttribute('data-tipo');
            const tipoMtg = btnElement.getAttribute('data-tipomtg');
            const detalles = btnElement.getAttribute('data-detalles');
            const formatos = btnElement.getAttribute('data-formatos');
            const imagen = btnElement.getAttribute('data-imagen');
            const stock = btnElement.getAttribute('data-stock');
            abrirModalTcg(titulo, edicion, rareza, tipo, tipoMtg, detalles, formatos, imagen, stock);
        }

        function addCartDesdeData(btnElement) {
            const id = btnElement.getAttribute('data-id');
            const nombre = btnElement.getAttribute('data-nombre');
            const precio = parseFloat(btnElement.getAttribute('data-precio'));
            const imagen = btnElement.getAttribute('data-imagen');
            const stockMax = parseInt(btnElement.getAttribute('data-stock'));
            addToCart(id, nombre, precio, imagen, stockMax);
        }

        function addToCart(id, nombre, precio, imagen, stockMax) {
            const existingItem = cart.find(item => item.id === id);
            if (existingItem) {
                if (existingItem.quantity < stockMax) { existingItem.quantity++; } 
                else { alert(`¡Límite alcanzado! Solo hay ${stockMax} copias disponibles.`); }
            } else { 
                cart.push({ id, nombre, precio: precio, imagen, quantity: 1, stockMax: stockMax }); 
            }
            renderCart(); abrirCarrito();
        }

        function changeQuantity(id, delta) {
            const item = cart.find(item => item.id === id);
            if (item) {
                let newQty = item.quantity + delta;
                if (newQty <= 0) { removeFromCart(id); return; } 
                if (newQty > item.stockMax) { alert(`Límite de stock: ${item.stockMax}.`); return; }
                item.quantity = newQty;
            }
            renderCart();
        }

        function removeFromCart(id) { 
            cart = cart.filter(item => item.id !== id); 
            renderCart(); 
        }

        function renderCart() {
            const container = document.querySelector('.cart-items');
            const totalElement = document.querySelector('.cart-total span:last-child');
            if (!container || !totalElement) return;

            container.innerHTML = ''; let total = 0;

            if (cart.length === 0) {
                container.innerHTML = '<p style="text-align:center; color:#888; margin-top:20px;">Tu carrito está vacío.</p>';
                totalElement.innerText = `$0.00 USD`; 
                return;
            }

            cart.forEach(item => {
                total += (item.precio * item.quantity);
                const isMax = item.quantity >= item.stockMax ? 'disabled' : '';
                container.innerHTML += `
                    <div class="cart-item">
                        <img src="${item.imagen}" alt="${item.nombre}">
                        <div class="cart-item-info">
                            <div class="cart-item-header">
                                <h4 class="cart-item-title">${item.nombre}</h4>
                                <button type="button" class="btn-delete-item" onclick="removeFromCart('${item.id}')" title="Eliminar">🗑️</button>
                            </div>
                            <span class="price">$${item.precio.toFixed(2)} USD</span>
                            <div class="cart-item-actions">
                                <span class="stock-label">Disponibles: ${item.stockMax}</span>
                                <div class="qty-control">
                                    <button type="button" onclick="changeQuantity('${item.id}', -1)" class="qty-btn">-</button>
                                    <span class="qty-value">${item.quantity}</span>
                                    <button type="button" onclick="changeQuantity('${item.id}', 1)" class="qty-btn" ${isMax}>+</button>
                                </div>
                            </div>
                        </div>
                    </div>`;
            });
            totalElement.innerText = `$${total.toFixed(2)} USD`;
        }

        function abrirCarrito() { document.getElementById('cartSidebar').classList.add('open'); }
        function cerrarCarrito() { document.getElementById('cartSidebar').classList.remove('open'); }
        
        function abrirCheckout() { 
            if(cart.length === 0) { alert("Agrega algo al carrito primero."); return; }
            cerrarCarrito(); 
            const modalPago = document.getElementById('checkoutModal');
            if(modalPago) modalPago.classList.add('mostrar-pago');
        }
        function cerrarCheckout() { document.getElementById('checkoutModal').classList.remove('mostrar-pago'); }
        function cerrarCheckoutEvent(e) { if (e.target.id === 'checkoutModal') cerrarCheckout(); }

        async function processPayment() {
            if (cart.length === 0) return alert("El carrito está vacío");

            const direccion = document.getElementById('ship_direccion').value;
            const nombreRecibe = document.getElementById('ship_nombre').value;
            const ciudad = document.getElementById('ship_ciudad').value;

            if(!direccion || !nombreRecibe || !ciudad) {
                alert("Por favor, completa los datos de envío."); return;
            }

            try {
                const response = await fetch('../Controlador/finalizar_compra.php', {
                    method: 'POST', headers: { 'Content-Type': 'application/json' }, 
                    body: JSON.stringify({ items: cart, envio: { nombre: nombreRecibe, direccion: direccion, ciudad: ciudad } })
                });
                const result = await response.json();
                
                if (result.success) {
                    alert(`¡Hechizo completado! Tus cartas van en camino.`);
                    cart = []; renderCart(); cerrarCheckout(); location.reload(); 
                } else { alert('Error: ' + result.message); }
            } catch (error) { console.error('Error:', error); alert('Error de conexión.'); }
        }

        function abrirModalTcg(titulo, edicion, rareza, tipo, tipoMtg, detalles, formatos, imagen, stock) {
            const listaMaestraFormatos = ["Standard", "Commander", "Modern", "Pioneer", "Legacy"];
            const formatosDeLaCarta = formatos ? formatos.split(',').map(f => f.trim()) : [];

            let formatosHtml = listaMaestraFormatos.map(formato => {
                const esLegal = formatosDeLaCarta.includes(formato);
                if (esLegal) {
                    return `<div class="legality-badge is-legal">${formato} <span>Legal</span></div>`;
                } else {
                    return `<div class="legality-badge not-legal">${formato} <span>Not Legal</span></div>`;
                }
            }).join('');

            document.getElementById('modalContentTarget').innerHTML = `
                <span class="close-btn" onclick="cerrarModalBtn()">&times;</span>
                <div class="modal-layout">
                    <div class="modal-left"><img src="${imagen}" alt="${titulo}"></div>
                    <div class="modal-right">
                        <div>
                            <h2 class="modal-title">${titulo}</h2>
                            <div class="modal-subtitle">▶ Edición: ${edicion} &nbsp;&nbsp;|&nbsp;&nbsp; Rareza: ${rareza}</div>
                        </div>
                        <div class="modal-section" style="display:flex; justify-content:space-between; align-items:center;">
                            <div>
                                <h4>Categoría:</h4><p>${tipo.toUpperCase()}</p>
                            </div>
                            <div style="text-align: right;">
                                <h4>Stock:</h4><p style="color: #d4af37; font-weight: bold; font-size: 1.2rem;">${stock} disponibles</p>
                            </div>
                        </div>
                        <div class="modal-section"><h4>Línea de Tipo (MTG):</h4><p style="color: #10b981; font-weight: bold;">${tipoMtg}</p></div>
                        <div class="modal-section"><h4>Detalles:</h4><p>${detalles}</p></div>
                        <div class="modal-section" style="margin-top: auto;">
                            <h4>Legalidades por Formato:</h4>
                            <div class="legalities-grid">${formatosHtml}</div>
                        </div>
                    </div>
                </div>`;
            document.getElementById('infoModal').style.display = 'flex';
        }

        function cerrarModalBtn() { document.getElementById('infoModal').style.display = 'none'; }
        function cerrarModal(e) { if (e.target.id === 'infoModal') cerrarModalBtn(); }
    </script>
    
    <footer><p>&copy; <?php echo date("Y"); ?> Jr Games.</p></footer>
</body>
</html>