<?php
// Conexión a la base de datos
include 'conexion.php';

// Comprobar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener los datos del formulario
$nombre = $_POST['nombre'];
$contrasena = $_POST['contrasena'];

// Preparar y ejecutar la consulta para insertar un nuevo administrador
$sql = "INSERT INTO administrador (nombre, contrasena) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $nombre, $contrasena);

if ($stmt->execute()) {
    // Redirigir al login después de un registro exitoso
    header("Location: ../admin/login.html?success=Registro exitoso");
} else {
    // Redirigir al registro con un mensaje de error
    header("Location: ../admin/register.html?error=No se pudo registrar el administrador");
}

// Cerrar la conexión
$stmt->close();
$conn->close();
?>
