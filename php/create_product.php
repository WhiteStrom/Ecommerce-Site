<?php
header('Content-Type: application/json');
// Lee la entrada JSON del cliente
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['nombre']) && isset($data['descripcion']) && isset($data['precio']) && isset($data['imagen'])) {
    $nombre = $data['nombre'];
    $descripcion = $data['descripcion'];
    $precio = $data['precio'];
    $imagen = $data['imagen'];

    // Conéctate a la base de datos
    include 'conexion.php';

    // Inserta el producto en la base de datos
    $sql = "INSERT INTO productos (nombre, descripcion, precio, imagen) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssis', $nombre, $descripcion, $precio, $imagen);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Datos incompletos']);
}