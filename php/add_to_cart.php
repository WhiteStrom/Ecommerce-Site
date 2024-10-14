<?php
// Conexión a la base de datos
include 'conexion.php';

// Comprobar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener los datos del POST
$usuario_id = 1; // Simulando el usuario con id 1
$producto_id = $_POST['producto_id'];
$cantidad = $_POST['cantidad'];

// Verificar si el producto ya está en el carrito
$sql = "SELECT * FROM carrito WHERE usuario_id = $usuario_id AND producto_id = $producto_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Actualizar la cantidad si el producto ya está en el carrito
    $sql = "UPDATE carrito SET cantidad = cantidad + $cantidad WHERE usuario_id = $usuario_id AND producto_id = $producto_id";
} else {
    // Insertar un nuevo registro en el carrito
    $sql = "INSERT INTO carrito (usuario_id, producto_id, cantidad) VALUES ($usuario_id, $producto_id, $cantidad)";
}

// Ejecutar la consulta
if ($conn->query($sql) === TRUE) {
    echo "Producto añadido al carrito";
} else {
    echo "Error: " . $conn->error;
}

// Cerrar la conexión
$conn->close();
?>
