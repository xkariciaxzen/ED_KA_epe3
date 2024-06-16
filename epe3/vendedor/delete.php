<?php
include 'db.php';


if (isset($_GET['id'])) {
    $id = intval($_GET['id']); 
} else {
    die("ID de repuesto no especificado.");
}


$sql = "DELETE FROM repuestos WHERE RepuestoID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id); 


if ($stmt->execute()) {
    echo "Repuesto eliminado exitosamente";
} else {
    echo "Error al eliminar repuesto: " . $stmt->error;
}


$stmt->close();
$conn->close();


header("Location: index.php");
exit();
?>
