<!-- restablecer_contraseña.php -->
<?php
// Verificar si el token es válido
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Conexión a la base de datos
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "Shapy";
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Verificar el token en la base de datos
    $sql = "SELECT Id_Usuarios, token_expiry FROM Usuarios WHERE reset_token = '$token'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Si el token es válido, se permite cambiar la contraseña
        $row = $result->fetch_assoc();
        $userId = $row['Id_Usuarios'];
        $expiry = $row['token_expiry'];

        // Verificar si el token ha expirado
        if (strtotime($expiry) < time()) {
            echo "El enlace ha expirado. Solicita uno nuevo.";
        } else {
            // Mostrar formulario para ingresar nueva contraseña
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $newPassword = $_POST['new_password'];
                $newPasswordConfirm = $_POST['new_password_confirm'];

                // Verificar que las contraseñas coinciden
                if ($newPassword == $newPasswordConfirm) {
                    $newPasswordHashed = password_hash($newPassword, PASSWORD_BCRYPT);

                    // Actualizar la contraseña en la base de datos
                    $update_sql = "UPDATE Usuarios SET Contraseña = '$newPasswordHashed', reset_token = NULL, token_expiry = NULL WHERE Id_Usuarios = '$userId'";
                    if ($conn->query($update_sql) === TRUE) {
                        echo "Tu contraseña ha sido cambiada exitosamente.";
                    } else {
                        echo "Error al actualizar la contraseña.";
                    }
                } else {
                    echo "Las contraseñas no coinciden.";
                }
            }
        }
    } else {
        echo "Token no válido.";
    }

    // Cerrar conexión
    $conn->close();
}
?>

<!-- Formulario para ingresar la nueva contraseña -->
<div class="form-container">
    <form action="restablecer_contraseña.php?token=<?php echo $_GET['token']; ?>" method="POST">
        <h1>Restablecer Contraseña</h1>
        <input type="password" name="new_password" placeholder="Nueva contraseña" required />
        <input type="password" name="new_password_confirm" placeholder="Confirmar nueva contraseña" required />
        <button type="submit">Restablecer contraseña</button>
    </form>
</div>
