<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$database = "gorras";

$conn = new mysqli($servername, $username, $password, $database);

// Verificar conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Consultas para las gráficas
$data = [];

// 1. Total de ventas por mes
$query = "SELECT MONTH(fecha_ped) AS mes, SUM(total) AS ventas FROM pedido GROUP BY MONTH(fecha_ped)";
$result = $conn->query($query);
$data['ventas_mes'] = $result->fetch_all(MYSQLI_ASSOC);

// 2. Productos más vendidos
$query = "SELECT productos.nombre, SUM(PEDIDO_PRODUCTO.cantidad) AS total FROM PEDIDO_PRODUCTO
          JOIN productos ON PEDIDO_PRODUCTO.id_prod = productos.id
          GROUP BY productos.nombre
          ORDER BY total DESC LIMIT 5";
$result = $conn->query($query);
$data['productos_mas_vendidos'] = $result->fetch_all(MYSQLI_ASSOC);

// 3. Usuarios con más pedidos
$query = "SELECT usuarios.nombre AS usuario_nombre, COUNT(pedido.id_ped) AS total_pedidos FROM pedido
          JOIN usuarios ON pedido.id_usu = usuarios.id
          GROUP BY usuarios.nombre
          ORDER BY total_pedidos DESC LIMIT 5";
$result = $conn->query($query);
$data['usuarios_pedidos'] = $result->fetch_all(MYSQLI_ASSOC);

// 4. Stock de productos
$query = "SELECT nombre, stock FROM productos";
$result = $conn->query($query);
$data['stock_productos'] = $result->fetch_all(MYSQLI_ASSOC);

// 5. Ventas totales por usuario
$query = "SELECT usuarios.nombre AS usuario_nombre, SUM(pedido.total) AS total_ventas FROM pedido
          JOIN usuarios ON pedido.id_usu = usuarios.id
          GROUP BY usuarios.nombre";
$result = $conn->query($query);
$data['ventas_usuario'] = $result->fetch_all(MYSQLI_ASSOC);

// 6. Total de pedidos por día
$query = "SELECT DATE(fecha_ped) AS dia, COUNT(*) AS total_pedidos FROM pedido GROUP BY DATE(fecha_ped)";
$result = $conn->query($query);
$data['pedidos_por_dia'] = $result->fetch_all(MYSQLI_ASSOC);

// 7. Categorías más populares
$query = "SELECT categoria, SUM(PEDIDO_PRODUCTO.cantidad) AS total FROM PEDIDO_PRODUCTO
          JOIN productos ON PEDIDO_PRODUCTO.id_prod = productos.id
          GROUP BY categoria
          ORDER BY total DESC";
$result = $conn->query($query);
$data['categorias_populares'] = $result->fetch_all(MYSQLI_ASSOC);

// 8. Ingresos totales por mes
$query = "SELECT MONTH(fecha_ped) AS mes, SUM(total) AS ingresos FROM pedido GROUP BY MONTH(fecha_ped)";
$result = $conn->query($query);
$data['ingresos_mes'] = $result->fetch_all(MYSQLI_ASSOC);

// 9. Pedidos por usuario
$query = "SELECT usuarios.nombre AS usuario_nombre, COUNT(pedido.id_ped) AS total_pedidos FROM pedido
          JOIN usuarios ON pedido.id_usu = usuarios.id
          GROUP BY usuarios.nombre
          ORDER BY total_pedidos DESC";
$result = $conn->query($query);
$data['pedidos_usuario'] = $result->fetch_all(MYSQLI_ASSOC);

// 10. Promedio de ventas por pedido
$query = "SELECT AVG(total) AS promedio_venta FROM pedido";
$result = $conn->query($query);
$data['promedio_venta'] = $result->fetch_assoc();

// Total de usuarios
$query = "SELECT COUNT(*) AS total_usuarios FROM usuarios";
$result = $conn->query($query);
$data['total_usuarios'] = $result->fetch_assoc();

// Ingresos totales
$query = "SELECT SUM(total) AS ingresos_totales FROM pedido";
$result = $conn->query($query);
$data['ingresos_totales'] = $result->fetch_assoc();

// Devolver datos en JSON
header('Content-Type: application/json');
echo json_encode($data);

// Cerrar conexión
$conn->close();
?>
