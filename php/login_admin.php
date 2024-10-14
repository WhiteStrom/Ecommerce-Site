<?php
// Iniciar sesión
session_start();

// Conexión a la base de datos
include 'conexion.php';

// Comprobar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener los datos del formulario
$nombre = $_POST['nombre'];
$contrasena = $_POST['contrasena'];

// Preparar y ejecutar la consulta
$sql = "SELECT * FROM administrador WHERE nombre = ? AND contrasena = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $nombre, $contrasena);
$stmt->execute();
$result = $stmt->get_result();

// Verificar si el administrador existe
if ($result->num_rows > 0) {
    // Iniciar la sesión y redirigir al panel de administración
    $_SESSION['admin'] = $nombre;
    header("Location: ../admin/index.html");
} else {
    // Redirigir al login con un mensaje de error
    header("Location: ../admin/login.html?error=Credenciales incorrectas");
}

// Cerrar la conexión
$stmt->close();
$conn->close();
?>
