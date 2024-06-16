<?php
include 'db.php';


function sanitize($input) {
    global $conn;
    return mysqli_real_escape_string($conn, htmlspecialchars(strip_tags(trim($input))));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    $rut = sanitize($_POST['rut']);
    $correo = sanitize($_POST['correo']);
    $tipo = sanitize($_POST['tipo']);

   
    $sql = "UPDATE usuarios SET Correo=?, Tipo=? WHERE Rut=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $correo, $tipo, $rut);

   
    if ($stmt->execute()) {
        echo "Usuario actualizado exitosamente";
    } else {
        echo "Error al actualizar usuario: " . $stmt->error;
    }

    
    $stmt->close();
    $conn->close();
} else {
   
    if (isset($_GET['rut'])) {
        $rut = sanitize($_GET['rut']);

       
        $sql = "SELECT * FROM usuarios WHERE Rut=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $rut);
        $stmt->execute();
        $result = $stmt->get_result();

        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
        } else {
            die("No se encontró el usuario solicitado.");
        }

        
        $stmt->close();
    } else {
        die("Rut de usuario no especificado.");
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Usuario</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Editar Usuario</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <input type="hidden" name="rut" value="<?php echo htmlspecialchars($row['Rut']); ?>">
        Correo: <input type="email" name="correo" value="<?php echo htmlspecialchars($row['Correo']); ?>" required><br>
        Tipo:
        <select name="tipo" required>
            <option value="">Selecciona un tipo</option>
            <option value="Vendedor" <?php if ($row['Tipo'] == 'Vendedor') echo 'selected'; ?>>Vendedor</option>
            <option value="Administrador" <?php if ($row['Tipo'] == 'Administrador') echo 'selected'; ?>>Administrador</option>
            <option value="Mecanico" <?php if ($row['Tipo'] == 'Mecanico') echo 'selected'; ?>>Mecánico</option>
            <option value="Aseguradora" <?php if ($row['Tipo'] == 'Aseguradora') echo 'selected'; ?>>Aseguradora</option>
            <option value="Analista" <?php if ($row['Tipo'] == 'Analista') echo 'selected'; ?>>Analista</option>
            <option value="Gerente" <?php if ($row['Tipo'] == 'Gerente') echo 'selected'; ?>>Gerente</option>
        </select><br>
        <input type="submit" value="Actualizar Usuario">
    </form>

    <br>
    <a href="index_usuario.php">Volver a la lista de Usuarios</a>
</body>
</html>
