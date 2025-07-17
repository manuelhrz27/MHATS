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

// Obtener datos del usuario si el ID está en la URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM usuarios WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
    } else {
        echo "Usuario no encontrado";
        exit;
    }
}

// Validación y actualización de datos del usuario
$error_message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $apellido_paterno = $_POST['apellido_paterno'];
    $apellido_materno = $_POST['apellido_materno'];
    $email = $_POST['email'];
    $tipo = $_POST['tipo'];

    // Validar formato de correo electrónico
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "El correo electrónico no tiene un formato válido.";
    } else {
        // Comprobar si el correo ya existe en otro usuario
        $check_sql = "SELECT id FROM usuarios WHERE email = '$email' AND id != $id";
        $check_result = $conn->query($check_sql);
        if ($check_result->num_rows > 0) {
            $error_message = "El correo electrónico ya está en uso.";
        } else {
            // Actualizar datos
            $update_sql = "UPDATE usuarios SET nombre='$nombre', apellido_paterno='$apellido_paterno', apellido_materno='$apellido_materno', email='$email', tipo='$tipo' WHERE id=$id";
            if ($conn->query($update_sql) === TRUE) {
                header("Location: usuarios.php");
                exit;
            } else {
                $error_message = "Error al actualizar el usuario: " . $conn->error;
            }
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Usuario</title>
    <style>
        /* Estilos generales */
        body {
            font-family: Arial, sans-serif;
            background-color: #1a1a1a;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .form-container {
            background-color: #222;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.6);
            width: 400px;
            text-align: center;
            animation: fadeIn 1s ease-out;
        }

        h1 {
            color: #ff6600;
            margin-bottom: 1rem;
            animation: glow 2s ease infinite alternate;
        }

        label {
            display: block;
            color: #ff6600;
            font-weight: bold;
            margin-top: 1rem;
            animation: slideIn 0.5s ease;
        }

        input[type="text"], input[type="email"], select {
            width: 100%;
            padding: 0.75rem;
            background-color: #333;
            color: #fff;
            border: 1px solid #444;
            border-radius: 6px;
            margin-top: 0.5rem;
            transition: box-shadow 0.3s ease, transform 0.3s ease;
        }

        input[type="text"]:focus, input[type="email"]:focus, select:focus {
            box-shadow: 0 0 10px #ff6600;
            transform: scale(1.05);
        }

        select {
            background-color: #333;
            color: #ff6600;
            cursor: pointer;
            font-weight: bold;
        }

        .btn-actualizar {
            margin-top: 1.5rem;
            padding: 0.75rem 2rem;
            background-color: #ff6600;
            color: #fff;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .btn-actualizar:hover {
            background-color: #ff8800;
            transform: translateY(-5px) scale(1.05);
            box-shadow: 0px 5px 15px rgba(255, 136, 0, 0.3);
        }

        .error-message {
            color: #ff3333;
            margin-top: 1rem;
            font-weight: bold;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes glow {
            from { text-shadow: 0 0 8px #ff6600, 0 0 16px #ff6600; }
            to { text-shadow: 0 0 16px #ff8800, 0 0 32px #ff8800; }
        }

        @keyframes slideIn {
            from { transform: translateX(-20px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
    </style>
    <script>
        function validateEmail() {
            const emailInput = document.getElementById('email');
            const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            if (!emailPattern.test(emailInput.value)) {
                alert('El correo electrónico no es válido. Debe tener el formato ejemplo@dominio.com.');
                emailInput.focus();
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <div class="form-container">
        <h1>Actualizar Usuario</h1>
        <?php if (!empty($error_message)) : ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <form action="" method="POST" onsubmit="return validateEmail()">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>

            <label for="apellido_paterno">Apellido Paterno:</label>
            <input type="text" id="apellido_paterno" name="apellido_paterno" value="<?php echo htmlspecialchars($usuario['apellido_paterno']); ?>" required>

            <label for="apellido_materno">Apellido Materno:</label>
            <input type="text" id="apellido_materno" name="apellido_materno" value="<?php echo htmlspecialchars($usuario['apellido_materno']); ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>

            <label for="tipo">Tipo:</label>
            <select id="tipo" name="tipo" required>
                <option value="admin" <?php echo ($usuario['tipo'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                <option value="user" <?php echo ($usuario['tipo'] == 'user') ? 'selected' : ''; ?>>User</option>
            </select>

            <button type="submit" class="btn-actualizar">Actualizar</button>
        </form>
    </div>
</body>
</html>
