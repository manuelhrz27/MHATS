<?php
// Iniciar sesión
session_start();

// Verificar que el usuario está logueado
if (!isset($_SESSION['id_usuario'])) {
    die("Error: Usuario no identificado. Por favor, inicie sesión.");
}

// Verificar que se ha recibido un ID de pedido
if (!isset($_GET['id_pedido'])) {
    die("Error: No se proporcionó un ID de pedido.");
}

$id_pedido = intval($_GET['id_pedido']);

// Conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "gorras");

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener la información del pedido
$sql_pedido = "SELECT p.id_ped, p.direccion, p.colonia, p.no, p.calle, p.fecha_ped, p.total, t.numero_tarjeta 
               FROM PEDIDO p 
               INNER JOIN tarjeta t ON p.id_tarjeta = t.id_tarjeta
               WHERE p.id_ped = $id_pedido";
$result_pedido = $conn->query($sql_pedido);

if ($result_pedido->num_rows === 0) {
    die("Error: Pedido no encontrado.");
}

$pedido = $result_pedido->fetch_assoc();

// Obtener los productos del pedido
$sql_productos = "SELECT pp.cantidad, prod.nombre, prod.precio_venta, prod.imagen 
                  FROM PEDIDO_PRODUCTO pp
                  INNER JOIN productos prod ON pp.id_prod = prod.id
                  WHERE pp.id_ped = $id_pedido";
$result_productos = $conn->query($sql_productos);

$productos = [];
if ($result_productos->num_rows > 0) {
    while ($row = $result_productos->fetch_assoc()) {
        $productos[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket de Pedido</title>
    <style>
        body {
            background-color: #1a1a1a;
            color: #fff;
            font-family: 'Poppins', sans-serif;
            text-align: center;
            padding: 20px;
            margin: 0;
        }
        .ticket-container {
            background: linear-gradient(135deg, #ff6700, #ff4500);
            color: #fff;
            padding: 20px;
            border-radius: 15px;
            max-width: 700px;
            margin: 30px auto;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.7);
            animation: fadeIn 1s ease-in-out;
        }
        h1 {
            color: #fff;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
        }
        .details {
            text-align: left;
            margin-top: 20px;
        }
        .details p {
            margin: 5px 0;
        }
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #fff;
            text-align: center;
        }
        th {
            background-color: rgba(255, 255, 255, 0.2);
        }
        .product-img {
            width: 60px;
            height: 60px;
            border-radius: 5px;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.5);
        }
        .total {
            font-weight: bold;
            font-size: 1.2em;
            text-align: right;
            margin-top: 20px;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="ticket-container">
        <h1>Ticket de Pedido</h1>
        <div class="details">
            <p><strong>Pedido ID:</strong> <?php echo $pedido['id_ped']; ?></p>
            <p><strong>Fecha:</strong> <?php echo $pedido['fecha_ped']; ?></p>
            <p><strong>Dirección:</strong> <?php echo $pedido['direccion'] . ", " . $pedido['colonia'] . ", No. " . $pedido['no'] . ", " . $pedido['calle']; ?></p>
            <p><strong>Tarjeta:</strong> **** **** **** <?php echo substr($pedido['numero_tarjeta'], -4); ?></p>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Imagen</th>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos as $producto): ?>
                    <tr>
                        <td><img src="<?php echo $producto['imagen']; ?>" alt="<?php echo $producto['nombre']; ?>" class="product-img"></td>
                        <td><?php echo $producto['nombre']; ?></td>
                        <td><?php echo $producto['cantidad']; ?></td>
                        <td>$<?php echo number_format($producto['precio_venta'], 2); ?></td>
                        <td>$<?php echo number_format($producto['precio_venta'] * $producto['cantidad'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="total">
            <p><strong>Total:</strong> $<?php echo number_format($pedido['total'], 2); ?></p>
        </div>
    </div>
</body>
</html>