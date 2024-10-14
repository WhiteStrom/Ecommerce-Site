<?php
// Conexión a la base de datos
include 'conexion.php';

// Comprobar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consultar los productos
$sql = "SELECT * FROM productos";
$result = $conn->query($sql);

$productos = array();

// Guardar los productos en un array
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $productos[] = $row;
    }
}

// Devolver los productos como JSON
echo json_encode($productos);

// Cerrar la conexión
$conn->close();
?>
