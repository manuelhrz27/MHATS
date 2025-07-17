<?php
// Conectar a la base de datos
$conn = new mysqli("localhost", "root", "", "gorras");

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Actualizar los datos del producto
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $precio_venta = $_POST['precio_venta'];
    $precio_compra = $_POST['precio_compra'];
    $categoria = $_POST['categoria'];
    $stock = $_POST['stock'];
    
    // Procesar la imagen si se sube una nueva
    if (!empty($_FILES['imagen']['name'])) {
        $imagen = 'imagenes/' . basename($_FILES['imagen']['name']);
        move_uploaded_file($_FILES['imagen']['tmp_name'], $imagen);
        $query = "UPDATE productos SET nombre='$nombre', precio_venta='$precio_venta', precio_compra='$precio_compra', categoria='$categoria', stock='$stock', imagen='$imagen' WHERE id=$id";
    } else {
        $query = "UPDATE productos SET nombre='$nombre', precio_venta='$precio_venta', precio_compra='$precio_compra', categoria='$categoria', stock='$stock' WHERE id=$id";
    }

    $conn->query($query);
    header("Location: produc.php");
}
?>

<?php $conn->close(); ?>
