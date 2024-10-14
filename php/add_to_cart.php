<?php
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

// Obtener los datos del POST
$usuario_id = 1; // Simulando el usuario con id 1
$producto_id = $_POST['producto_id'];
$cantidad = $_POST['cantidad'];

// Verificar si el producto ya está en el carrito
$sql = "SELECT * FROM carrito WHERE usuario_id = $usuario_id AND producto_id = $producto_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Actualizar la cantidad si el producto ya está en el carrito
    $sql = "UPDATE carrito SET cantidad = cantidad + $cantidad WHERE usuario_id = $usuario_id AND producto_id = $producto_id";
} else {
    // Insertar un nuevo registro en el carrito
    $sql = "INSERT INTO carrito (usuario_id, producto_id, cantidad) VALUES ($usuario_id, $producto_id, $cantidad)";
}

// Ejecutar la consulta
if ($conn->query($sql) === TRUE) {
    echo "Producto añadido al carrito";
} else {
    echo "Error: " . $conn->error;
}

// Cerrar la conexión
$conn->close();
?>
