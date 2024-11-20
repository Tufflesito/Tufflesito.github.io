<?php
session_start();

// Conexión a la base de datos
$servername = "localhost";
$username = "root";      // Cambiar si es necesario
$password = "";          // Cambiar si es necesario
$dbname = "Shapy";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Recibir datos del formulario
$email = $_POST['email'];
$password = $_POST['password'];

// Verificar si el email existe en la base de datos
$sql = "SELECT Id_Usuarios, Contraseña FROM Usuarios WHERE Email = '$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Si el email existe, obtener el hash de la contraseña
    $row = $result->fetch_assoc();
    $hash = $row['Contraseña'];

    // Verificar la contraseña usando password_verify
    if (password_verify($password, $hash)) {
        // Autenticación exitosa, guardar el ID del usuario en sesión
        $_SESSION['Id_Usuarios'] = $row['Id_Usuarios'];
        header("Location: espacio.php");  // Redirige a la página del usuario
        exit();
    } else {
        // Contraseña incorrecta
        echo "Contraseña incorrecta.";
    }
} else {
    // El email no existe
    echo "El usuario no está registrado.";
}

// Cerrar conexión
$conn->close();
?>
