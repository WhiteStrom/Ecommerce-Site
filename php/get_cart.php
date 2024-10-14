<?php
// Conexión a la base de datos
include 'conexion.php';

// Comprobar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consultar el carrito del usuario (simulando el usuario con id 1)
$usuario_id = 1;
$sql = "SELECT c.id, p.nombre, c.cantidad, p.precio, (c.cantidad * p.precio) AS precio_total FROM carrito c JOIN productos p ON c.producto_id = p.id WHERE c.usuario_id = $usuario_id";
$result = $conn->query($sql);

$carrito = array();

// Guardar los productos en el carrito en un array
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $carrito[] = $row;
    }
}

// Devolver el carrito como JSON
echo json_encode($carrito);

// Cerrar la conexión
$conn->close();
?>
