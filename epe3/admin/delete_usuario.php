<?php
include 'db.php';

$rut = $_GET['rut'];

$sql = "DELETE FROM usuarios WHERE Rut='$rut'";

if ($conn->query($sql) === TRUE) {
    echo "Usuario eliminado exitosamente";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
header("Location: index_usuario.php");
exit();
?>
