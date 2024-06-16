<?php
include 'db.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombreRepuesto = $_POST['nombreRepuesto'];
    $cantidadStock = $_POST['cantidadStock'];
    $precioUnitario = $_POST['precioUnitario'];
    $proveedor = $_POST['proveedor'];

 
    $sql = "INSERT INTO repuestos (NombreRepuesto, CantidadStock, PrecioUnitario, Proveedor) VALUES ('$nombreRepuesto', '$cantidadStock', '$precioUnitario', '$proveedor')";

    if ($conn->query($sql) === TRUE) {
        echo '<div style="color: green;">Repuesto añadido exitosamente</div>';
    } else {
        echo '<div style="color: red;">Error al añadir repuesto: ' . $conn->error . '</div>';
    }
}


$sql = "SELECT * FROM repuestos";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Lista de Repuestos</title>
    <link rel="stylesheet" type="text/css" href="vendedor.css">
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        .form-container {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h2>Lista de Repuestos</h2>
    

    <div class="form-container">
        <h3>Agregar Nuevo Repuesto</h3>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            Nombre Repuesto: <input type="text" name="nombreRepuesto" required><br>
            Cantidad Stock: <input type="number" name="cantidadStock" required><br>
            Precio Unitario: <input type="number" name="precioUnitario" step="0.01" required><br>
            Proveedor: <input type="text" name="proveedor" required><br>
            <input type="submit" value="Agregar Repuesto">
        </form>
    </div>
    
   
    <table>
        <tr>
            <th>ID</th>
            <th>Nombre Repuesto</th>
            <th>Cantidad Stock</th>
            <th>Precio Unitario</th>
            <th>Proveedor</th>
            <th>Acciones</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
             
                $id = htmlspecialchars($row["RepuestoID"]);
                $nombre = htmlspecialchars($row["NombreRepuesto"]);
                $cantidad = htmlspecialchars($row["CantidadStock"]);
                $precio = htmlspecialchars($row["PrecioUnitario"]);
                $proveedor = htmlspecialchars($row["Proveedor"]);
                
                echo "<tr>
                        <td>{$id}</td>
                        <td>{$nombre}</td>
                        <td>{$cantidad}</td>
                        <td>{$precio}</td>
                        <td>{$proveedor}</td>
                        <td>
                            <a href='edit.php?id={$id}'>Editar</a>
                            <a href='delete.php?id={$id}' onclick='return confirm(\"¿Estás seguro de eliminar este repuesto?\")'>Eliminar</a>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No hay repuestos disponibles</td></tr>";
        }
        $conn->close();
        ?>
    </table>
</body>
</html>

