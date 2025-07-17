<?php
// archivo: pedidos_usuario.php

// Iniciar sesión para acceder a las variables de sesión
session_start();

// Verificar si el usuario está autenticado y tiene un ID asignado en la sesión
if (!isset($_SESSION['id_usuario'])) {
    die("Error: No se ha iniciado sesión o no se encuentra el ID del usuario.");
}

// Obtener el ID del usuario desde la sesión
$id_usuario = $_SESSION['id_usuario'];

// Depuración: Mostrar el ID del usuario
echo "<pre>ID Usuario recuperado de la sesión: " . htmlspecialchars($id_usuario) . "</pre>";

// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gorras";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Error en la conexión: " . $conn->connect_error);
}

// Consulta para obtener los pedidos del usuario
$sql = "
    SELECT 
        p.id_ped, 
        p.fecha_ped, 
        p.total, 
        p.estado_ped, 
        pr.nombre AS producto, 
        pp.cantidad, 
        (pr.precio_venta * pp.cantidad) AS subtotal
    FROM pedido AS p
    JOIN pedido_producto AS pp ON p.id_ped = pp.id_ped
    JOIN productos AS pr ON pp.id_prod = pr.id
    WHERE p.id_usu = ?
    ORDER BY p.fecha_ped DESC
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

// Verificar si hay resultados


// HTML con tabla y botón de regresar
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos del Usuario</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(45deg, #000000, #ff6600);
            color: #fff;
            padding: 20px;
            animation: backgroundScroll 5s linear infinite;
        }
        @keyframes backgroundScroll {
            0% { background-position: 0 0; }
            100% { background-position: 100% 100%; }
        }
        h1 {
            text-align: center;
            color: #ff6600;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);
        }
        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
            animation: fadeIn 1s ease-in-out;
        }
        table th, table td {
            border: 1px solid #444;
            padding: 12px;
            text-align: left;
            transition: background-color 0.3s ease;
        }
        table th {
            background-color: #ff6600;
            color: #fff;
        }
        table tr:nth-child(even) {
            background-color: #333;
        }
        table tr:hover {
            background-color: #ff6600;
            color: #000;
            cursor: pointer;
        }
        .animated-row {
            animation: fadeIn 0.5s ease-in-out;
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .button-container {
            text-align: center;
            margin: 20px;
        }
        .button {
            background-color: #ff6600;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: transform 0.3s, background-color 0.3s;
        }
        .button:hover {
            background-color: #000;
            color: #ff6600;
            transform: scale(1.1);
        }
        .button:active {
            transform: scale(0.95);
        }
    </style>
</head>
<body>
    <h1>Pedidos del Usuario</h1>
    <table>
        <thead>
            <tr>
                <th>ID Pedido</th>
                <th>Fecha</th>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Subtotal</th>
                <th>Total</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr class="animated-row">
                        <td><?= htmlspecialchars($row['id_ped']) ?></td>
                        <td><?= htmlspecialchars($row['fecha_ped']) ?></td>
                        <td><?= htmlspecialchars($row['producto']) ?></td>
                        <td><?= htmlspecialchars($row['cantidad']) ?></td>
                        <td>$<?= number_format($row['subtotal'], 2) ?></td>
                        <td>$<?= number_format($row['total'], 2) ?></td>
                        <td><?= htmlspecialchars($row['estado_ped']) ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" style="text-align: center;">No se encontraron pedidos.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <div class="button-container">
        <button class="button" onclick="location.href='user.html'">Regresar</button>
    </div>
</body>
</html>

<?php
// Cerrar la conexión
$conn->close();
?>
