<?php
header('Content-Type: application/json');

// Lee la entrada JSON del cliente
$data = json_decode(file_get_contents('php://input'), true);

// Verificar si todos los campos requeridos están presentes
if (isset($data['nombre'], $data['descripcion'], $data['precio'], $data['imagen'])) {
    $nombre = $data['nombre'];
    $descripcion = $data['descripcion'];
    $precio = $data['precio'];
    $imagen = $data['imagen'];

    // Validar los datos recibidos
    if (empty($nombre) || empty($descripcion) || empty($precio) || empty($imagen)) {
        echo json_encode(['success' => false, 'error' => 'Todos los campos son obligatorios.']);
        exit;
    }

    // Validar el tipo de dato de precio
    if (!is_numeric($precio) || $precio <= 0) {
        echo json_encode(['success' => false, 'error' => 'El precio debe ser un número positivo.']);
        exit;
    }

    // Datos de conexión a la base de datos
    $servername = "ecommerce2k24-server.mysql.database.azure.com";
    $username = "gxnxubadse";
    $password = "qKDe0VUjZ$2hTrW9";
    $dbname = "tienda_online";

    // Habilitar reportes de errores de MySQLi para depuración
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    // Crear la conexión
    $conn = mysqli_init();

    // Configuración de SSL para la conexión
    if (!mysqli_ssl_set($conn, NULL, NULL, __DIR__ . "/../SSL/DigiCertGlobalRootCA.crt.pem", NULL, NULL)) {
        echo json_encode(['success' => false, 'error' => "Falló la configuración SSL"]);
        exit;
    }

    // Realizar la conexión a la base de datos
    if (!mysqli_real_connect($conn, $servername, $username, $password, $dbname, 3306, NULL, MYSQLI_CLIENT_SSL)) {
        echo json_encode(['success' => false, 'error' => "Conexión fallida: " . mysqli_connect_error()]);
        exit;
    }

    // Inserta el producto en la base de datos
    $sql = "INSERT INTO productos (nombre, descripcion, precio, imagen) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    // Vincular parámetros al statement
    $stmt->bind_param('ssds', $nombre, $descripcion, $precio, $imagen);

    try {
        // Ejecutar la consulta
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'No se pudo crear el producto.']);
        }
    } catch (Exception $e) {
        // Capturar cualquier error y devolverlo en la respuesta
        echo json_encode(['success' => false, 'error' => 'Excepción: ' . $e->getMessage()]);
    }

    // Cierra el statement y la conexión
    $stmt->close();
    $conn->close();
} else {
    // Si no se envían los datos correctamente
    echo json_encode(['success' => false, 'error' => 'Datos incompletos']);
}
