<?php
// Iniciar sesión
session_start();

// Verificar que el usuario está logueado
if (!isset($_SESSION['id_usuario'])) {
    die("Error: Usuario no identificado. Por favor, inicie sesión.");
}

// Conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "gorras");

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener id del usuario desde la sesión
$id_usuario = $_SESSION['id_usuario'];

// Recuperar las tarjetas asociadas al usuario
$sql_tarjetas = "SELECT id_tarjeta, numero_tarjeta FROM tarjeta WHERE id_usuario = $id_usuario";
$result_tarjetas = $conn->query($sql_tarjetas);
$tarjetas = [];
if ($result_tarjetas->num_rows > 0) {
    while ($row = $result_tarjetas->fetch_assoc()) {
        $tarjetas[] = $row;
    }
}

// Procesar el formulario de registro de pedido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $direccion = $conn->real_escape_string($_POST['direccion']);
    $colonia = $conn->real_escape_string($_POST['colonia']);
    $no = $conn->real_escape_string($_POST['no']);
    $calle = $conn->real_escape_string($_POST['calle']);
    $fecha_ped = date('Y-m-d'); // Usar la fecha actual
    $estado_ped = 'pendiente'; // Estado inicial del pedido
    $total = $conn->real_escape_string($_POST['total']);
    $id_tarjeta = $conn->real_escape_string($_POST['id_tarjeta']); // ID de la tarjeta seleccionada

    // Recuperar carrito desde el formulario
    $cart = isset($_POST['cart']) ? json_decode($_POST['cart'], true) : null;

    if (!$cart || !is_array($cart)) {
        die("Error: El carrito está vacío o tiene un formato inválido.");
    }

    // Iniciar una transacción
    $conn->begin_transaction();

    try {
        // Insertar el pedido en la tabla PEDIDO
        $sql_pedido = "INSERT INTO PEDIDO (direccion, colonia, no, calle, fecha_ped, estado_ped, total, id_usu, id_tarjeta)
                       VALUES ('$direccion', '$colonia', '$no', '$calle', '$fecha_ped', '$estado_ped', '$total', '$id_usuario', '$id_tarjeta')";

        if (!$conn->query($sql_pedido)) {
            throw new Exception("Error al registrar el pedido: " . $conn->error);
        }

        // Obtener el ID del pedido recién insertado
        $id_pedido = $conn->insert_id;

        foreach ($cart as $item) {
            $id_prod = $conn->real_escape_string($item['id']);
            $cantidad = $conn->real_escape_string($item['quantity']);

            // Verificar que el producto existe en la tabla productos
            $result_producto = $conn->query("SELECT id FROM productos WHERE id = '$id_prod'");
            if ($result_producto->num_rows === 0) {
                throw new Exception("Error: El producto con ID $id_prod no existe.");
            }

            // Insertar cada producto en la tabla PEDIDO_PRODUCTO
            $sql_pedido_producto = "INSERT INTO PEDIDO_PRODUCTO (id_ped, id_prod, cantidad)
                                    VALUES ('$id_pedido', '$id_prod', '$cantidad')";

            if (!$conn->query($sql_pedido_producto)) {
                throw new Exception("Error al registrar el producto en el pedido: " . $conn->error);
            }
        }

        // Confirmar la transacción
        $conn->commit();

        // Redirigir a ticket.php tras completar el pedido
        header("Location: ticket.php?id_pedido=$id_pedido");
        exit;
    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        $conn->rollback();
        echo $e->getMessage();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Pedido</title>
    <style>
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
        }

        h1 {
            color: #ff6600;
            margin-bottom: 1rem;
        }

        label {
            display: block;
            color: #ff6600;
            font-weight: bold;
            margin-top: 1rem;
        }

        input[type="text"], input[type="number"], select {
            width: 100%;
            padding: 0.75rem;
            background-color: #333;
            color: #fff;
            border: 1px solid #444;
            border-radius: 6px;
            margin-top: 0.5rem;
        }

        .btn-registrar {
            margin-top: 1.5rem;
            padding: 0.75rem 2rem;
            background-color: #ff6600;
            color: #fff;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Registrar Pedido</h1>
        <form action="" method="POST">
            <label for="direccion">Dirección:</label>
            <input type="text" id="direccion" name="direccion" required>

            <label for="colonia">Colonia:</label>
            <input type="text" id="colonia" name="colonia" required>

            <label for="no">Número:</label>
            <input type="text" id="no" name="no">

            <label for="calle">Calle:</label>
            <input type="text" id="calle" name="calle" required>

            <label for="total">Total:</label>
            <input type="number" id="total" name="total" step="0.01" readonly required>

            <label for="id_tarjeta">Seleccionar Tarjeta:</label>
            <select id="id_tarjeta" name="id_tarjeta" required>
                <?php foreach ($tarjetas as $tarjeta): ?>
                    <option value="<?php echo $tarjeta['id_tarjeta']; ?>">
                        <?php echo '**** ' . substr($tarjeta['numero_tarjeta'], -4); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <input type="hidden" name="cart" id="cart">
            
            <button type="submit" class="btn-registrar">Registrar Pedido</button>
        </form>
    </div>

    <script>
        // Recuperar el total del carrito y el carrito completo desde localStorage
        document.addEventListener('DOMContentLoaded', () => {
            const total = localStorage.getItem('cart-total');
            const cart = localStorage.getItem('cart');

            if (total) {
                document.getElementById('total').value = parseFloat(total).toFixed(2);
            }
            if (cart) {
                document.getElementById('cart').value = cart;
            }
        });
        
    </script>
</body>
</html>