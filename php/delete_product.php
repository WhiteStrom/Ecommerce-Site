<?php
header('Content-Type: application/json');

// Conexión a la base de datos
include 'conexion.php';

// Verificar conexión
if ($conn->connect_error) {
    echo json_encode(['error' => "Conexión fallida: " . $conn->connect_error]);
    exit();
}

// Obtener el ID del producto desde el cuerpo de la solicitud
$data = json_decode(file_get_contents('php://input'), true);
$id = isset($data['id']) ? (int)$data['id'] : 0;

if ($data['_method'] === 'DELETE' && $id > 0) {
    // Eliminar el producto
    $stmt = $conn->prepare("DELETE FROM productos WHERE id = ?");
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => "Error al eliminar el producto: " . $conn->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'ID inválido no permitido']);
}

// Cerrar conexión
$conn->close();
?>
