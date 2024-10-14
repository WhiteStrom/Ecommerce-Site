<?php
header('Content-Type: application/json');
// Lee la entrada JSON del cliente
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['nombre']) && isset($data['descripcion']) && isset($data['precio']) && isset($data['imagen'])) {
    $nombre = $data['nombre'];
    $descripcion = $data['descripcion'];
    $precio = $data['precio'];
    $imagen = $data['imagen'];

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
