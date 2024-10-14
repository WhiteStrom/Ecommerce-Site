<?php
// conexion.php
$servername = "ecommerce2k24-server.mysql.database.azure.com";
$username = "gxnxubadse";
$password = "qKDe0VUjZ$2hTrW9";
$dbname = "tienda_online";
$ssl_cert_path = __DIR__ . "/SSL/DigiCertGlobalRootCA.crt.pem";  // Ruta completa al certificado

// Habilitar reportes de errores de MySQLi para depuración
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Crear la conexión
$conn = mysqli_init();

// Verificar si la configuración SSL se establece correctamente
if (!mysqli_ssl_set($conn, NULL, NULL, "/var/www/html/DigiCertGlobalRootCA.crt.pem" , NULL, NULL)) {
    die(json_encode(['success' => false, 'error' => "Falló la configuración SSL"]));
}

// Realizar la conexión a la base de datos
if (!mysqli_real_connect($conn, $servername, $username, $password, $dbname, 3306, NULL, MYSQLI_CLIENT_SSL)) {
    die(json_encode(['success' => false, 'error' => "Conexión fallida: " . mysqli_connect_error()]));
}

// Si todo va bien, la conexión es exitosa
echo json_encode(['success' => true, 'message' => "Conexión exitosa a la base de datos"]);
?>
