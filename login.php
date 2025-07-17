<?php
// Iniciar la sesión
session_start();

// Configuración de la conexión a la base de datos
$servername = "localhost"; // Cambia esto si usas otro servidor
$username = "root";   // Coloca tu usuario de base de datos aquí
$password = ""; // Coloca tu contraseña de base de datos aquí
$dbname = "gorras"; // Coloca el nombre de tu base de datos aquí

// Crear conexión a la base de datos
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Procesar los datos enviados por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Consulta para verificar si el usuario existe en la base de datos
    $sql = "SELECT * FROM usuarios WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verificar la contraseña usando password_verify
        if (password_verify($password, $row['password'])) {
            // Guardar el id del usuario en la sesión para usar en otras páginas
            $_SESSION['id_usuario'] = $row['id'];

            // Redirigir según el tipo de usuario
            if ($row['tipo'] === 'admin') {
                header("Location: admin.html");
            } else {
                header("Location: user.html");
            }
            exit();
        } else {
            echo "Contraseña incorrecta";
        }
    } else {
        echo "Usuario no encontrado";
    }
} else {
    echo "Método de solicitud no válido";
}

// Cerrar conexión
$conn->close();
?>
