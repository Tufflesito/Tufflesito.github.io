<!-- recuperar_contraseña.php -->
<?php
// Si el formulario es enviado, procesamos la solicitud
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Conexión a la base de datos
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "Shapy";
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Verificar si el email existe
    $sql = "SELECT Id_Usuarios, Email FROM Usuarios WHERE Email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Si el email existe, generamos un token único para restablecer la contraseña
        $token = bin2hex(random_bytes(50));  // Genera un token seguro
        $expiry = date("Y-m-d H:i:s", strtotime('+1 hour'));  // El enlace será válido durante 1 hora

        // Guardar el token y la fecha de expiración en la base de datos
        $row = $result->fetch_assoc();
        $userId = $row['Id_Usuarios'];

        $update_sql = "UPDATE Usuarios SET reset_token = '$token', token_expiry = '$expiry' WHERE Id_Usuarios = '$userId'";
        if ($conn->query($update_sql) === TRUE) {
            // Enviar el correo con el enlace para restablecer la contraseña
            $reset_link = "http://tu_dominio.com/restablecer_contraseña.php?token=$token";  // Cambia el dominio por el real
            $subject = "Recuperación de contraseña";
            $message = "Haz clic en el siguiente enlace para restablecer tu contraseña: $reset_link";
            $headers = "From: no-reply@tudominio.com";

            mail($email, $subject, $message, $headers);

            echo "Se ha enviado un enlace a tu correo para restablecer tu contraseña.";
        } else {
            echo "Error al generar el enlace de recuperación.";
        }
    } else {
        echo "El correo electrónico no está registrado.";
    }

    // Cerrar conexión
    $conn->close();
}
?>

<!-- Formulario para ingresar el correo -->
<div class="form-container">
    <form action="recuperar_contraseña.php" method="POST">
        <h1>Recuperar Contraseña</h1>
        <input type="email" name="email" placeholder="Introduce tu correo electrónico" required />
        <button type="submit">Enviar enlace de recuperación</button>
    </form>
</div>
