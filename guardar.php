<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gorras";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
  die("Error de conexión: " . $conn->connect_error);
}

// Obtener los datos del formulario
$nombre = $_POST['username'];
$apellido_paterno = $_POST['ape_p'];
$apellido_materno = $_POST['ape_m'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Encriptar contraseña
$tipo = $_POST['user_type']; // Obtener valor de la lista desplegable

// Insertar datos en la base de datos
$sql = "INSERT INTO usuarios (nombre, apellido_paterno, apellido_materno, email, password, tipo)
        VALUES ('$nombre', '$apellido_paterno', '$apellido_materno', '$email', '$password', '$tipo')";

if ($conn->query($sql) === TRUE) {
  // Obtener el tipo de usuario que se acaba de registrar
  $user_id = $conn->insert_id;
  $result = $conn->query("SELECT tipo FROM usuarios WHERE id = $user_id");
  $row = $result->fetch_assoc();
  
  // Redirigir según el tipo de usuario
  if ($row['tipo'] === 'admin') {
    header("Location: admin.html");
  } else {
    header("Location: user.html");
  }
  exit;
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}

// Cerrar la conexión
$conn->close();
?>
