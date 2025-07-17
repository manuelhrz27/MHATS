<?php
// archivo: editar_pedido.php

// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gorras";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Error en la conexión: " . $conn->connect_error);
}

// Obtener el ID del pedido desde la solicitud GET
$id_ped = isset($_GET['id_ped']) ? intval($_GET['id_ped']) : 0;

// Recuperar los datos del pedido
$sql = "SELECT * FROM pedido WHERE id_ped = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_ped);
$stmt->execute();
$result = $stmt->get_result();
$pedido = $result->fetch_assoc();

if (!$pedido) {
    die("Pedido no encontrado.");
}

// Procesar el formulario cuando se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $estado_ped = $_POST['estado_ped'];

    // Actualizar el estado del pedido
    $update_sql = "UPDATE pedido SET estado_ped = ? WHERE id_ped = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("si", $estado_ped, $id_ped);

    if ($update_stmt->execute()) {
        echo "<script>alert('Pedido actualizado correctamente.'); window.location.href='pedid.php';</script>";
    } else {
        echo "<script>alert('Error al actualizar el pedido.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Pedido</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(45deg, #000000, #ff6600);
            color: #fff;
            padding: 20px;
        }
        form {
            width: 50%;
            margin: auto;
            padding: 20px;
            background-color: #333;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
        }
        h1 {
            text-align: center;
            color: #ff6600;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);
        }
        label {
            font-weight: bold;
            display: block;
            margin: 10px 0 5px;
        }
        select, input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            font-size: 16px;
            border: none;
            border-radius: 5px;
        }
        input[type="submit"] {
            background-color: #ff6600;
            color: #fff;
            cursor: pointer;
            transition: transform 0.3s, background-color 0.3s;
        }
        input[type="submit"]:hover {
            background-color: #000;
            color: #ff6600;
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <h1>Editar Pedido</h1>
    <form method="POST">
        <label for="estado_ped">Estado del Pedido:</label>
        <select name="estado_ped" id="estado_ped" required>
            <option value="Pendiente" <?= $pedido['estado_ped'] === 'Pendiente' ? 'selected' : '' ?>>Pendiente</option>
            <option value="Entregado" <?= $pedido['estado_ped'] === 'Entregado' ? 'selected' : '' ?>>Entregado</option>
        </select>
        <input type="submit" value="Actualizar Pedido">
    </form>
</body>
</html>

<?php
// Cerrar conexión
$conn->close();
?>
