<?php
include 'db.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rut = $_POST['rut'];
    $correo = $_POST['correo'];
    $contraseña = password_hash($_POST['contraseña'], PASSWORD_DEFAULT); 
    $tipo = $_POST['tipo'];

    $sql = "INSERT INTO usuarios (Rut, Correo, Contraseña, Tipo) VALUES ('$rut', '$correo', '$contraseña', '$tipo')";

    if ($conn->query($sql) === TRUE) {
        echo "Usuario añadido exitosamente";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$sql = "SELECT * FROM usuarios";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Lista de Usuarios</title>
    <link rel="stylesheet" href="admin.css"> 
</head>
<body>
    <h2>Lista de Usuarios</h2>
    
  
    <h3>Agregar Nuevo Usuario</h3>
    <form method="post" action="index_usuario.php">
        Rut: <input type="text" name="rut" required><br>
        Correo: <input type="email" name="correo" required><br>
        Contraseña: <input type="password" name="contraseña" required><br>
        Tipo:
        <select name="tipo" required>
            <option value="">Selecciona un tipo</option>
            <option value="Vendedor">Vendedor</option>
            <option value="Administrador">Administrador</option>
            <option value="Mecanico">Mecánico</option>
            <option value="Aseguradora">Aseguradora</option>
            <option value="Analista">Analista</option>
            <option value="Gerente">Gerente</option>
        </select><br>
        <input type="submit" value="Agregar Usuario">
    </form>


    <table border="1">
        <tr>
            <th>Rut</th>
            <th>Correo</th>
            <th>Tipo</th>
            <th>Acciones</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>".$row["Rut"]."</td>
                        <td>".$row["Correo"]."</td>
                        <td>".$row["Tipo"]."</td>
                        <td>
                            <a href='edit_usuario.php?rut=".$row["Rut"]."'>Editar</a>
                            <a href='delete_usuario.php?rut=".$row["Rut"]."' onclick='return confirm(\"¿Estás seguro de eliminar este usuario?\")'>Eliminar</a>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No hay usuarios disponibles</td></tr>";
        }
        $conn->close();
        ?>
    </table>
</body>
</html>
