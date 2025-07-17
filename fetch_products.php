<?php
$conn = new mysqli("localhost", "root", "", "gorras");
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Parámetros de búsqueda y paginación
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$category = isset($_GET['category']) ? $conn->real_escape_string($_GET['category']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Consulta SQL principal
$sql = $category
    ? "SELECT * FROM productos WHERE categoria = '$category' LIMIT $limit OFFSET $offset"
    : "SELECT * FROM productos WHERE nombre LIKE '%$search%' OR categoria LIKE '%$search%' LIMIT $limit OFFSET $offset";

$result = $conn->query($sql);

$products = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = [
            'id' => $row['id'],
            'title' => $row['nombre'],
            'price' => (float)$row['precio_venta'],
            'image' => $row['imagen'],
        ];
    }
}

// Contar total de productos para paginación
$total_query = $category
    ? "SELECT COUNT(*) as total FROM productos WHERE categoria = '$category'"
    : "SELECT COUNT(*) as total FROM productos WHERE nombre LIKE '%$search%' OR categoria LIKE '%$search%'";
$total_result = $conn->query($total_query);
$total_row = $total_result->fetch_assoc();
$total_products = (int)$total_row['total'];
$total_pages = ceil($total_products / $limit);

// Enviar respuesta JSON
header('Content-Type: application/json');
echo json_encode([
    'products' => $products,
    'total_pages' => $total_pages, // Total de páginas
    'current_page' => $page,       // Página actual
    'total_products' => $total_products, // Total de productos
]);

$conn->close();
?>
