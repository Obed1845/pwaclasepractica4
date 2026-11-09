<?php
require 'db.php';
$sql = "SELECT p.id, p.nombre, p.descripcion, p.precio, p.imagen, v.nombre AS vendedor FROM Productos p JOIN Vendedores v ON p.vendedor_id = v.id";
$resultado = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Catálogo de Vehículos Eléctricos</title>
    <style>
        :root {
            --primary: #0056b3;
            --bg: #f8f9fa;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg);
            margin: 0;
            padding: 20px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: white;
            padding: 15px 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        .card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .card img {
            max-width: 100%;
            height: 180px;
            object-fit: contain;
            margin-bottom: 15px;
        }
        .price {
            font-size: 1.5rem;
            color: var(--primary);
            font-weight: bold;
            margin: 10px 0;
        }
        .vendedor {
            font-size: 0.9rem;
            color: #6c757d;
        }
        .btn {
            background: var(--primary);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 1rem;
            margin-top: 10px;
        }
        .btn:hover {
            background: #004494;
        }
        #carrito-panel {
            position: fixed;
            top: 20px;
            right: 20px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            display: none;
            width: 300px;
            z-index: 1000;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>Catálogo EV</h2>
        <button class="btn" style="width: auto;" onclick="toggleCarrito()">Carrito (<span id="contador">0</span>)</button>
    </div>

    <div id="carrito-panel">
        <h3>Tu Carrito</h3>
        <ul id="lista-carrito" style="list-style: none; padding: 0;"></ul>
        <button class="btn" onclick="procesarCompra()" style="background: #28a745;">Procesar Pago</button>
        <button class="btn" onclick="toggleCarrito()" style="background: #dc3545;">Cerrar</button>
    </div>

    <div class="grid">
        <?php while($producto = $resultado->fetch_assoc()): ?>
        <div class="card">
            <img src="<?php echo htmlspecialchars($producto['imagen']); ?>" alt="Vehículo">
            <h3><?php echo htmlspecialchars($producto['nombre']); ?></h3>
            <p><?php echo htmlspecialchars($producto['descripcion']); ?></p>
            <p class="vendedor">Vendido por: <?php echo htmlspecialchars($producto['vendedor']); ?></p>
            <div class="price">$<?php echo number_format($producto['precio'], 2); ?></div>
            <button class="btn" onclick="agregarAlCarrito(<?php echo $producto['id']; ?>, '<?php echo htmlspecialchars($producto['nombre']); ?>')">Añadir al Carrito</button>
        </div>
        <?php endwhile; ?>
    </div>

    <script>
        let carrito = [];

        function agregarAlCarrito(id, nombre) {
            carrito.push({ id, nombre });
            actualizarUI();
            alert(nombre + " añadido al carrito.");
        }

        function actualizarUI() {
            document.getElementById('contador').innerText = carrito.length;
            const lista = document.getElementById('lista-carrito');
            lista.innerHTML = '';
            carrito.forEach((item) => {
                const li = document.createElement('li');
                li.innerText = item.nombre;
                li.style.marginBottom = '10px';
                li.style.borderBottom = '1px solid #eee';
                lista.appendChild(li);
            });
        }

        function toggleCarrito() {
            const panel = document.getElementById('carrito-panel');
            panel.style.display = panel.style.display === 'block' ? 'none' : 'block';
        }

        function procesarCompra() {
            if (carrito.length === 0) {
                alert("El carrito está vacío.");
                return;
            }

            fetch('procesar_compra.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ carrito: carrito, cliente_id: 1 })
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
                carrito = [];
                actualizarUI();
                toggleCarrito();
            });
        }
    </script>

</body>
</html>
<?php
$conn->close();
?>