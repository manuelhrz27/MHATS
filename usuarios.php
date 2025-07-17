<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gorras";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Verificar si se ha enviado una solicitud de eliminación
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = intval($_POST['delete_id']);
    $delete_sql = "DELETE FROM usuarios WHERE id = $delete_id";
    
    if ($conn->query($delete_sql) === TRUE) {
        echo "<script>alert('Usuario eliminado correctamente');</script>";
    } else {
        echo "<script>alert('Error al eliminar el usuario');</script>";
    }
}

// Consultar los datos de la tabla usuarios
$sql = "SELECT * FROM usuarios";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios</title>
    <style>
        /* Estilos generales */
        body {
            background-image: url("https://www.wallpapertip.com/wmimgs/51-513883_new-era-caps.jpg");
  background-repeat: no-repeat;
  background-size: cover;
            font-family: Arial, sans-serif;
            background-color: #1a1a1a;
            color: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            padding: 20px;
            animation: fadeIn 1s ease-in-out;
            box-shadow: 0px 0px 10px rgba(255, 69, 0, 0.7);
            border-radius: 10px;
            background-color: #222;
            margin-top: 20px;
        }
        h1 {
            text-align: center;
            color: #ff4500;
            font-size: 2.5em;
            margin-bottom: 20px;
            animation: slideDown 1s ease-out;
        }
        .filter-container {
            text-align: right;
            margin-bottom: 20px;
        }
        .filter-input {
            padding: 8px;
            width: 200px;
            background-color: #333;
            color: #fff;
            border: 1px solid #ff4500;
            border-radius: 5px;
            transition: box-shadow 0.3s;
        }
        .filter-input:focus {
            box-shadow: 0 0 10px #ff4500;
        }

        /* Tabla */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            animation: fadeInUp 0.8s ease-in-out;
        }
        th, td {
            padding: 15px;
            text-align: center;
            border: 1px solid #ff4500;
            transition: background-color 0.3s;
        }
        th {
            background-color: #ff4500;
            color: #fff;
        }
        tr:hover {
            background-color: #444;
        }
        tr:nth-child(even) {
            background-color: #333;
        }

        /* Botones */
        .btn {
            padding: 10px 20px;
            margin: 5px;
            color: #fff;
            background-color: #ff4500;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
        }
        .btn:hover {
            background-color: #e63900;
            transform: scale(1.1);
        }
        .center-button {
            text-align: center;
            margin-top: 30px;
        }
        .main-button {
            padding: 12px 30px;
            background-color: #ff4500;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 1.2em;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
        }
        .main-button:hover {
            background-color: #e63900;
            transform: scale(1.1);
        }

        /* Animaciones */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slideDown {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        @keyframes fadeInUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
    </style>
    <script>
        function filterTable() {
            let input = document.getElementById("filterInput").value.toLowerCase();
            let table = document.getElementById("userTable");
            let tr = table.getElementsByTagName("tr");

            for (let i = 1; i < tr.length; i++) {
                let rowText = tr[i].innerText.toLowerCase();
                tr[i].style.display = rowText.includes(input) ? "" : "none";
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Lista de Usuarios</h1>

        <div class="filter-container">
            <input type="text" id="filterInput" class="filter-input" onkeyup="filterTable()" placeholder="Buscar...">
        </div>

        <table id="userTable">
            <tr>
                <th>Nombre</th>
                <th>Apellido Paterno</th>
                <th>Apellido Materno</th>
                <th>Email</th>
                <th>Tipo</th>
                <th>Acciones</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row['nombre']) . "</td>
                            <td>" . htmlspecialchars($row['apellido_paterno']) . "</td>
                            <td>" . htmlspecialchars($row['apellido_materno']) . "</td>
                            <td>" . htmlspecialchars($row['email']) . "</td>
                            <td>" . htmlspecialchars($row['tipo']) . "</td>
                            <td>
                                <a href='actualizar_usuario.php?id=" . $row['id'] . "' class='btn'>Actualizar</a>
                                <form method='POST' style='display:inline;'>
                                    <input type='hidden' name='delete_id' value='" . $row['id'] . "'>
                                    <button type='submit' class='btn'>Eliminar</button>
                                </form>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No hay usuarios registrados</td></tr>";
            }
            ?>
        </table>

        <div class="center-button">
            <button class="main-button" onclick="window.location.href='admin.html'">Volver</button>
        </div>
    </div>

    <?php $conn->close(); ?>
</body>
</html>
