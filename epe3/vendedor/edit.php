<?php
include 'db.php';

function sanitize($input) {
    global $conn;
    return mysqli_real_escape_string($conn, htmlspecialchars(strip_tags(trim($input))));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = sanitize($_POST['id']);
    $nombreRepuesto = sanitize($_POST['nombreRepuesto']);
    $cantidadStock = (int)$_POST['cantidadStock'];
    $precioUnitario = (float)$_POST['precioUnitario'];
    $proveedor = sanitize($_POST['proveedor']);

    $sql = "UPDATE repuestos SET NombreRepuesto=?, CantidadStock=?, PrecioUnitario=?, Proveedor=? WHERE RepuestoID=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sidsi", $nombreRepuesto, $cantidadStock, $precioUnitario, $proveedor, $id);

    if ($stmt->execute()) {
        echo "Repuesto actualizado exitosamente";
    } else {
        echo "Error al actualizar repuesto: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    if (isset($_GET['id'])) {
        $id = sanitize($_GET['id']);

        $sql = "SELECT * FROM repuestos WHERE RepuestoID=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
        } else {
            die("No se encontró el repuesto solicitado.");
        }

        $stmt->close();
    } else {
        die("ID de repuesto no especificado.");
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Repuesto</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <h2>Editar Repuesto</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['RepuestoID']); ?>">
        Nombre Repuesto: <input type="text" name="nombreRepuesto" value="<?php echo htmlspecialchars($row['NombreRepuesto']); ?>" required><br>
        Cantidad Stock: <input type="number" name="cantidadStock" value="<?php echo htmlspecialchars($row['CantidadStock']); ?>" required><br>
        Precio Unitario: <input type="number" name="precioUnitario" step="0.01" value="<?php echo htmlspecialchars($row['PrecioUnitario']); ?>" required><br>
        Proveedor: <input type="text" name="proveedor" value="<?php echo htmlspecialchars($row['Proveedor']); ?>" required><br>
        <input type="submit" value="Actualizar Repuesto">
    </form>

    <br>
    <a href="index.php">Volver a la lista de Repuestos</a>
</body>
</html>
