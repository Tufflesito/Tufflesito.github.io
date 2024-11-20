<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['Id_Usuarios'])) {
    header("Location: ../inicio_sesion.html");
    exit();
}

// Conexión a la base de datos
$servername = "localhost";
$username = "root"; 
$password = "";
$dbname = "Shapy";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$userId = $_SESSION['Id_Usuarios'];
$sql = "SELECT Contraseña FROM Usuarios WHERE Id_Usuarios = '$userId'";
$result = $conn->query($sql);
$storedPassword = "";

if ($result->num_rows > 0) {
    $userData = $result->fetch_assoc();
    $storedPassword = $userData['Contraseña'];
} else {
    $error_message = "Usuario no encontrado.";
    exit();
}

// Procesar el cambio de contraseña
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['new_password'])) {
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $newPasswordConfirm = $_POST['new_password_confirm'];

    if (password_verify($currentPassword, $storedPassword)) {
        if ($newPassword == $newPasswordConfirm) {
            $newPasswordHashed = password_hash($newPassword, PASSWORD_BCRYPT);

            // Actualizar contraseña en la base de datos
            $update_sql = "UPDATE Usuarios SET Contraseña = '$newPasswordHashed' WHERE Id_Usuarios = '$userId'";
            if ($conn->query($update_sql) === TRUE) {
                $success_message = "Contraseña actualizada correctamente.";
                echo "<script>window.location.href = 'espacio.php';</script>";
            } else {
                $error_message = "Error al actualizar la contraseña: " . $conn->error;
            }
        } else {
            $error_message = "Las contraseñas no coinciden.";
        }
    } else {
        $error_message = "La contraseña actual es incorrecta.";
    }
}

$conn->close();
?>
