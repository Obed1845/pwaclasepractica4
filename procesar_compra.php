<?php
require 'db.php';

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (isset($data['carrito']) && isset($data['cliente_id'])) {
    $cliente_id = (int)$data['cliente_id'];
    
    $stmt = $conn->prepare("INSERT INTO Pedidos (cliente_id, producto_id) VALUES (?, ?)");
    
    foreach ($data['carrito'] as $item) {
        $producto_id = (int)$item['id'];
        $stmt->bind_param("ii", $cliente_id, $producto_id);
        $stmt->execute();
    }
    
    $stmt->close();
    echo "¡Compra procesada con éxito! Tus vehículos están en preparación.";
} else {
    echo "Error al procesar la solicitud.";
}

$conn->close();
?>