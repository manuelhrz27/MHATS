<?php
// archivo: pedid.php

// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gorras";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Error en la conexión: " . $conn->connect_error);
}

// Consultar todos los datos de la tabla pedido
$sql = "SELECT id_ped, fecha_ped, total, estado_ped FROM pedido";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Pedidos</title>
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
        .button {
            background-color: #ff6600;
            color: #fff;
            border: none;
            padding: 8px 16px;
            font-size: 14px;
            border-radius: 5px;
            cursor: pointer;
            transition: transform 0.3s, background-color 0.3s;
        }
        .button:hover {
            background-color: #000;
            color: #ff6600;
            transform: scale(1.1);
        }
    </style>
</head>
<body>
    <h1>Gestión de Pedidos</h1>
    <table>
        <thead>
            <tr>
                <th>ID Pedido</th>
                <th>Fecha</th>
                <th>Total</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id_ped']) ?></td>
                        <td><?= htmlspecialchars($row['fecha_ped']) ?></td>
                        <td>$<?= number_format($row['total'], 2) ?></td>
                        <td><?= htmlspecialchars($row['estado_ped']) ?></td>
                        <td>
                            <form action="editar_pedido.php" method="GET">
                                <input type="hidden" name="id_ped" value="<?= htmlspecialchars($row['id_ped']) ?>">
                                <button type="submit" class="button">Editar</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align: center;">No se encontraron pedidos.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>

<?php
// Cerrar conexión
$conn->close();
?>
