<?php
// Conectar a la base de datos
$conn = new mysqli("localhost", "root", "", "gorras");
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener ID del producto
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Consultar el producto
$sql = "SELECT * FROM productos WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $product = $result->fetch_assoc();
} else {
    echo "<p>Producto no encontrado.</p>";
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $product['nombre']; ?> - Detalles</title>
    <style>
        /* Fondo y tipografía */
        body {

            background-image: url(https://www.wallpapertip.com/wmimgs/51-513883_new-era-caps.jpg);
            background-color: #000;
            color: #fff;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            background: linear-gradient(135deg, #ff6700, #333);
            padding: 20px;
            border-radius: 15px;
            width: 85%;
            max-width: 500px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4);
            overflow: hidden;
            transform: scale(0.9);
            animation: fadeIn 0.6s ease-out forwards;
        }
        
        /* Animación de aparición */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Imagen del producto */
        .product-image {
            width: 100%;
            max-width: 300px;
            border-radius: 10px;
            margin: 0 auto 15px;
            display: block;
            animation: slideIn 0.7s ease-in-out forwards;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Información del producto */
        .product-info h1 {
            font-size: 22px;
            color: #ff6700;
            text-align: center;
            margin-bottom: 10px;
        }
        .product-info p {
            font-size: 16px;
            line-height: 1.6;
            color: #e0e0e0;
            margin: 10px 0;
            text-align: center;
        }
        .product-price {
            font-size: 20px;
            font-weight: bold;
            color: #ff6700;
            text-align: center;
            margin: 15px 0;
        }

        /* Botón de regreso */
        .back-button {
            display: inline-block;
            margin: 20px auto 0;
            padding: 10px 20px;
            font-size: 14px;
            font-weight: bold;
            color: #fff;
            background-color: #ff6700;
            text-decoration: none;
            border-radius: 8px;
            transition: background-color 0.3s ease, transform 0.3s;
            display: block;
            text-align: center;
        }
        .back-button:hover {
            background-color: #e65a00;
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="<?php echo $product['imagen']; ?>" alt="<?php echo $product['nombre']; ?>" class="product-image">
        <div class="product-info">
            <h1><?php echo $product['nombre']; ?></h1>
            <p><strong>Categoría:</strong> <?php echo $product['categoria']; ?></p>
            <p class="product-price">Precio: $<?php echo $product['precio_venta']; ?></p>
            <p><strong>Descripción:</strong> Este producto es parte de nuestra colección exclusiva de gorras, seleccionado para darte un estilo único y moderno.</p>
            <p><strong>Stock Disponible:</strong> <?php echo $product['stock']; ?> unidades</p>
            <a href="producto.php" class="back-button">Regresar al catálogo</a>
        </div>
    </div>
</body>
</html>
