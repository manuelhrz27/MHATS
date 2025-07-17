<?php
$nombre = $_POST['nombre'];
$precio_venta = $_POST['precio_venta'];
$precio_compra = $_POST['precio_compra'];
$categoria = $_POST['categoria'];
$stock = $_POST['stock'];
$imagen = $_FILES['imagen'];

// Verificar y crear la carpeta si no existe
$target_dir = "imagenes/";
if (!is_dir($target_dir)) {
    mkdir($target_dir, 0777, true);
}

// Guardar imagen en la carpeta
$target_file = $target_dir . basename($imagen["name"]);
if (move_uploaded_file($imagen["tmp_name"], $target_file)) {
    // Conectar a la base de datos
    $conn = new mysqli("localhost", "root", "", "gorras");

    // Verificar conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Insertar datos
    $sql = "INSERT INTO productos (nombre, precio_venta, precio_compra, categoria, stock, imagen)
            VALUES ('$nombre', '$precio_venta', '$precio_compra', '$categoria', '$stock', '$target_file')";

    if ($conn->query($sql) === TRUE) {
        header("Location: admin.html");
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Cerrar conexión
    $conn->close();
} else {
    echo "Error al subir la imagen.";
}
?>
