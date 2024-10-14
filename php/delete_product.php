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
    die(json_encode(['success' => false, 'error' => "Falló la configuración SSL"]));
}

// Realizar la conexión a la base de datos
if (!mysqli_real_connect($conn, $servername, $username, $password, $dbname, 3306, NULL, MYSQLI_CLIENT_SSL)) {
    die(json_encode(['success' => false, 'error' => "Conexión fallida: " . mysqli_connect_error()]));
}

// Verificar conexión
if ($conn->connect_error) {
    echo json_encode(['error' => "Conexión fallida: " . $conn->connect_error]);
    exit();
}

// Obtener el ID del producto desde el cuerpo de la solicitud
$data = json_decode(file_get_contents('php://input'), true);
$id = isset($data['id']) ? (int)$data['id'] : 0;

if (isset($data['_method']) && $data['_method'] === 'DELETE' && $id > 0) {
    // Eliminar el producto
    $stmt = $conn->prepare("DELETE FROM productos WHERE id = ?");
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => "Error al eliminar el producto: " . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'ID inválido no permitido']);
}

// Cerrar conexión
$conn->close();
?>
