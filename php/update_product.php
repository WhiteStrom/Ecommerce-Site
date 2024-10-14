<?php
header('Content-Type: application/json');

// Conexión a la base de datos
include 'conexion.php';

// Verificar conexión
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => "Conexión fallida: " . $conn->connect_error]);
    exit();
}

// Obtener datos del cuerpo de la solicitud
$data = json_decode(file_get_contents("php://input"), true);

// Asegurarse de que se envió un ID y los nuevos datos
if (isset($data['id']) && isset($data['nombre']) && isset($data['precio']) && isset($data['descripcion']) && isset($data['imagen']) ) {
    $id = $conn->real_escape_string($data['id']);
    $nombre = $conn->real_escape_string($data['nombre']);
    $precio = $conn->real_escape_string($data['precio']);
    $descripcion = $conn->real_escape_string($data['descripcion']);
    $imagen = $conn->real_escape_string($data['imagen']);

    // Actualizar el producto en la base de datos
    $sql = "UPDATE productos SET nombre='$nombre', precio='$precio', descripcion='$descripcion', imagen='$imagen' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(['success' => true, 'message' => "Producto actualizado exitosamente"]);
    } else {
        echo json_encode(['success' => false, 'error' => "Error al actualizar el producto: " . $conn->error]);
    }
} else {
    echo json_encode(['success' => false, 'error' => "Datos incompletos"]);
}

// Cerrar conexión
$conn->close();
?>
