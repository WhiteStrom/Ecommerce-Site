<?php
header('Content-Type: application/json');

// Datos de conexión a la base de datos
$servername = "ecommerce2k24-server.mysql.database.azure.com";
$username = "gxnxubadse";
$password = "qKDe0VUjZ$2hTrW9";
$dbname = "tienda_online";

// Habilitar reportes de errores de MySQLi para depuración
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Crear la conexión
$conn = mysqli_init();

// Verificar si la configuración SSL se establece correctamente
if (!mysqli_ssl_set($conn, NULL, NULL, __DIR__ . "/../SSL/DigiCertGlobalRootCA.crt.pem", NULL, NULL)) {
    echo json_encode(['success' => false, 'error' => "Falló la configuración SSL"]);
    exit();
}

// Realizar la conexión a la base de datos
if (!mysqli_real_connect($conn, $servername, $username, $password, $dbname, 3306, NULL, MYSQLI_CLIENT_SSL)) {
    echo json_encode(['success' => false, 'error' => "Conexión fallida: " . mysqli_connect_error()]);
    exit();
}

// Obtener datos del cuerpo de la solicitud
$data = json_decode(file_get_contents("php://input"), true);

// Asegurarse de que se envió un ID y los nuevos datos
if (isset($data['id']) && isset($data['nombre']) && isset($data['precio']) && isset($data['descripcion']) && isset($data['imagen'])) {
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
