<?php
// Iniciar sesión
session_start();

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
