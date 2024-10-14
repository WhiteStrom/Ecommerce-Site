<?php
// Conexión a la base de datos
$servername = "ecommerce2k24-server.mysql.database.azure.com";
$username = "gxnxubadse";
$password = "qKDe0VUjZ$2hTrW9";
$dbname = "tienda_online";

// Habilitar reportes de errores de MySQLi para depuración
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Crear la conexión con MySQLi
$conn = mysqli_init();

// Configuración SSL
$ssl_cert_path = __DIR__ . "/../SSL/DigiCertGlobalRootCA.crt.pem";
if (!mysqli_ssl_set($conn, NULL, NULL, $ssl_cert_path, NULL, NULL)) {
    die(json_encode(['success' => false, 'error' => "Falló la configuración SSL"]));
}

// Realizar la conexión a la base de datos
if (!mysqli_real_connect($conn, $servername, $username, $password, $dbname, 3306, NULL, MYSQLI_CLIENT_SSL)) {
    die(json_encode(['success' => false, 'error' => "Conexión fallida: " . mysqli_connect_error()]));
}

// Si todo va bien, la conexión es exitosa
echo json_encode(['success' => true, 'message' => "Conexión exitosa a la base de datos"]);

// Comprobar la conexión (esta parte no es necesaria aquí, ya que la verificación se hizo arriba)
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'error' => "Conexión fallida: " . $conn->connect_error]));
}

// Consultar los productos
$sql = "SELECT * FROM productos";
$result = $conn->query($sql);

// Verificar si hay resultados
$productos = array();
if ($result && $result->num_rows > 0) {
    // Guardar los productos en un array
    while ($row = $result->fetch_assoc()) {
        $productos[] = $row;
    }
} else {
    echo json_encode(['success' => false, 'message' => "No se encontraron productos"]);
    exit;
}

// Devolver los productos como JSON
echo json_encode($productos);

// Cerrar la conexión
$conn->close();
?>
