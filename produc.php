<?php
// Conectar a la base de datos
$conn = new mysqli("localhost", "root", "", "gorras");

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Eliminar producto si se ha solicitado
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $conn->query("DELETE FROM productos WHERE id=$id");
    header("Location: produc.php");
}

// Recuperar todos los productos
$result = $conn->query("SELECT * FROM productos");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
    <style>
        /* Estilos generales */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            background-image: url("https://www.wallpapertip.com/wmimgs/51-513883_new-era-caps.jpg");
            background-repeat: no-repeat;
            background-size: cover;
            background-color: #1a1a1a;
            display: flex;
            justify-content: center;
            padding: 20px;
            min-height: 100vh;
            overflow-y: auto;
        }

        .form-container {
            background-color: #282828;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.6);
            width: 90%;
            max-width: 1200px;
            text-align: center;
            animation: fadeInUp 1s ease-out;
            transform-origin: top;
            overflow-y: auto;
            max-height: 90vh;
        }

        h1 {
            color: #ff6600;
            margin-bottom: 1rem;
            animation: glow 2s ease infinite alternate;
        }

        .search-box {
            margin-bottom: 1rem;
            text-align: center;
        }

        .search-box input[type="text"] {
            padding: 0.75rem;
            width: 60%;
            border: 1px solid #444;
            background-color: #333;
            color: #fff;
            border-radius: 6px;
            transition: box-shadow 0.3s ease, transform 0.2s ease;
        }

        .search-box input[type="text"]:focus {
            box-shadow: 0 0 12px #ff6600;
            transform: scale(1.05);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
            animation: slideIn 1s ease;
        }

        table, th, td {
            border: 1px solid #444;
        }

        th, td {
            padding: 1rem;
            text-align: center;
            color: #fff;
            font-size: 1rem;
        }

        th {
            background-color: #ff6600;
        }

        tr:nth-child(even) {
            background-color: #2c2c2c;
        }

        tr:hover {
            background-color: #ff660033;
            transition: background-color 0.3s ease;
        }

        /* Tamaño de la imagen en la tabla */
        td img {
            border-radius: 8px;
            width: 100px;
            height: auto;
            transition: transform 0.3s ease;
        }

        /* Efecto de agrandar al pasar el cursor */
        td img:hover {
            transform: scale(1.5);
            box-shadow: 0px 4px 15px rgba(255, 102, 0, 0.4);
        }

        .btn-accion {
            padding: 0.5rem 1rem;
            background-color: #ff6600;
            border: none;
            color: #fff;
            font-weight: bold;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s ease, transform 0.3s ease;
            margin: 0 0.3rem;
        }

        .btn-accion:hover {
            background-color: #ff8800;
            transform: scale(1.1);
            box-shadow: 0px 5px 15px rgba(255, 136, 0, 0.3);
        }

        .eliminar {
            background-color: #ff3333;
        }

        .eliminar:hover {
            background-color: #ff4444;
        }

        /* Estilo del botón de admin */
        .btn-admin {
            display: inline-block;
            margin-top: 1.5rem;
            padding: 0.75rem 2rem;
            background-color: #ff6600;
            color: #fff;
            font-weight: bold;
            font-size: 1.1rem;
            text-decoration: none;
            border-radius: 8px;
            transition: transform 0.3s ease, background-color 0.3s ease;
            cursor: pointer;
        }

        .btn-admin:hover {
            background-color: #ff8800;
            transform: translateY(-5px) scale(1.05);
            box-shadow: 0px 5px 15px rgba(255, 136, 0, 0.3);
        }

        /* Animaciones */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-100%);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes glow {
            from {
                text-shadow: 0 0 8px #ff6600, 0 0 16px #ff6600;
            }
            to {
                text-shadow: 0 0 16px #ff8800, 0 0 32px #ff8800;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Lista de Productos</h1>
        
        <!-- Buscador -->
        <div class="search-box">
            <input type="text" id="buscar" placeholder="Buscar producto...">
        </div>

        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Precio Venta</th>
                    <th>Precio Compra</th>
                    <th>Categoría</th>
                    <th>Stock</th>
                    <th>Imagen</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="tabla-productos">
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['nombre']; ?></td>
                        <td><?php echo $row['precio_venta']; ?></td>
                        <td><?php echo $row['precio_compra']; ?></td>
                        <td><?php echo $row['categoria']; ?></td>
                        <td><?php echo $row['stock']; ?></td>
                        <td><img src="<?php echo $row['imagen']; ?>" alt="Imagen del producto"></td>
                        <td>
                            <a href="actualizar_producto.php?id=<?php echo $row['id']; ?>" class="btn-accion">Actualizar</a>
                            <a href="produc.php?eliminar=<?php echo $row['id']; ?>" class="btn-accion eliminar">Eliminar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <a href="admin.html" class="btn-admin">Volver</a>
    </div>

    <script>
        document.getElementById('buscar').addEventListener('keyup', function() {
            const filter = this.value.toUpperCase();
            const rows = document.getElementById('tabla-productos').getElementsByTagName('tr');
            
            for (let i = 0; i < rows.length; i++) {
                const cell = rows[i].getElementsByTagName('td')[0];
                if (cell) {
                    const textValue = cell.textContent || cell.innerText;
                    rows[i].style.display = textValue.toUpperCase().indexOf(filter) > -1 ? '' : 'none';
                }
            }
        });

        // Confirmación antes de eliminar
        document.querySelectorAll('.eliminar').forEach(btn => {
            btn.addEventListener('click', function(event) {
                if (!confirm("¿Estás seguro de que deseas eliminar este producto?")) {
                    event.preventDefault();
                }
            });
        });
    </script>
</body>
</html>

<?php $conn->close(); ?>
