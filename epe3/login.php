<?php
session_start();
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST['Correo'];
    $password = $_POST['Contraseña'];

    echo "Correo recibido: " . htmlspecialchars($correo) . "<br>";
    echo "Contraseña recibida: " . htmlspecialchars($password) . "<br>";

    $query = "SELECT * FROM usuarios WHERE Correo=?";
    $stmt = $conn->prepare($query);

    if ($stmt === false) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }
    
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        echo "Usuario encontrado: " . htmlspecialchars($user['Correo']) . "<br>";

        if ($password == $user['Contraseña']) { // Si las contraseñas no están hasheadas
            echo "Contraseña verificada.<br>";
            $_SESSION['Correo'] = $user['Correo'];
            $_SESSION['Tipo'] = $user['Tipo'];

            if ($user['Tipo'] == 'Administrador') {
                echo "Redirigiendo a administrador...<br>";
                header('Location: admin/index_usuario.php');
                exit();
            } elseif ($user['Tipo'] == 'Vendedor') {
                echo "Redirigiendo a vendedor...<br>";
                header('Location: vendedor/index.php');
                exit();
            } else {
                echo "Usuario sin acceso.<br>";
            }
        } else {
            echo "Correo o contraseña incorrectos (verificación de contraseña fallida).<br>";
            $error_message = "Correo o contraseña incorrectos.";
        }
    } else {
        echo "Correo o contraseña incorrectos (usuario no encontrado).<br>";
        $error_message = "Correo o contraseña incorrectos.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="login-container">
        <h2>Iniciar Sesión</h2>
        <?php if (isset($error_message)) { ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php } ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label>Correo:</label>
            <input type="email" name="Correo" required><br><br>
            <label>Contraseña:</label>
            <input type="password" name="Contraseña" required><br><br>
            <input type="submit" value="Iniciar Sesión">
        </form>
    </div>
</body>
</html>
