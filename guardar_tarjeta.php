<?php
session_start();

// Verificar que id_usuario está definido en la sesión
if (!isset($_SESSION['id_usuario'])) {
    die("Error: Usuario no identificado. Por favor inicia sesión.");
}

// Conectar a la base de datos
$conn = new mysqli("localhost", "root", "", "gorras");

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Validar y obtener datos del formulario
$numero_tarjeta = $conn->real_escape_string($_POST['numero_tarjeta']);
$nombre_titular = $conn->real_escape_string($_POST['nombre_titular']);
$fech_expir = $conn->real_escape_string($_POST['fech_expir']);
$cvv = $conn->real_escape_string($_POST['cvv']);
$id_usuario = $_SESSION['id_usuario'];  // Recuperar id_usuario desde la sesión

// Insertar datos en la tabla `tarjeta`
$sql = "INSERT INTO tarjeta (numero_tarjeta, nombre_titular, fech_expir, cvv, id_usuario)
        VALUES ('$numero_tarjeta', '$nombre_titular', '$fech_expir', '$cvv', '$id_usuario')";

if ($conn->query($sql) === TRUE) {
    // Redirigir a checkout.php si la inserción es exitosa
    header("Location: checkout.php");
    exit();
} else {
    echo "Error al guardar los datos de la tarjeta: " . $conn->error;
}

// Cerrar la conexión
$conn->close();
?><?php
session_start();

// Verificar que id_usuario está definido en la sesión
if (!isset($_SESSION['id_usuario'])) {
    die("Error: Usuario no identificado. Por favor inicia sesión.");
}

// Conectar a la base de datos
$conn = new mysqli("localhost", "root", "", "gorras");

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Validar y obtener datos del formulario
$numero_tarjeta = $conn->real_escape_string($_POST['numero_tarjeta']);
$nombre_titular = $conn->real_escape_string($_POST['nombre_titular']);
$fech_expir = $conn->real_escape_string($_POST['fech_expir']);
$cvv = $conn->real_escape_string($_POST['cvv']);
$id_usuario = $_SESSION['id_usuario'];  // Recuperar id_usuario desde la sesión

// Insertar datos en la tabla `tarjeta`
$sql = "INSERT INTO tarjeta (numero_tarjeta, nombre_titular, fech_expir, cvv, id_usuario)
        VALUES ('$numero_tarjeta', '$nombre_titular', '$fech_expir', '$cvv', '$id_usuario')";

if ($conn->query($sql) === TRUE) {
    // Redirigir a checkout.php si la inserción es exitosa
    header("Location: checkout.php");
    exit();
} else {
    echo "Error al guardar los datos de la tarjeta: " . $conn->error;
}

// Cerrar la conexión
$conn->close();
?>