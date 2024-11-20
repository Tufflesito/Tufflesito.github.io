<?php
// Iniciar la sesión
session_start();

// Conexión a la base de datos
$servername = "localhost";
$username = "root";  // Cambiar si es necesario
$password = "";      // Cambiar si es necesario
$dbname = "Shapy";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Recibir los datos del formulario
$nombre_apellido = $_POST['nombre_apellido'];
$email = $_POST['email'];
$contraseña = password_hash($_POST['contraseña'], PASSWORD_BCRYPT);
$edad = $_POST['edad'];

// Verificar si el email ya está registrado
$sql_check = "SELECT * FROM Usuarios WHERE Email = '$email'";
$result = $conn->query($sql_check);

// Si ya existe un usuario con ese email, mostrar un mensaje
if ($result->num_rows > 0) {
    echo "<script>alert('El correo electrónico ya está registrado. Por favor, ingrese otro.'); window.location.href = '../inicio_sesion.html';</script>";
    exit();
}

// Insertar datos en la tabla Usuarios
$sql = "INSERT INTO Usuarios (Nombres_Apellidos, Edad, Email, contraseña) 
        VALUES ('$nombre_apellido', '$edad', '$email', '$contraseña')";

if ($conn->query($sql) === TRUE) {
    // Obtener el ID del usuario registrado
    $userId = $conn->insert_id;
    
    // Guardar información en la sesión
    $_SESSION['Id_Usuarios'] = $userId;
    $_SESSION['Nombre'] = $nombre_apellido;
    $_SESSION['Email'] = $email;

    // Redirigir al cuestionario
    header("Location: ../questionario.html");
    exit();
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Cerrar la conexión
$conn->close();
?>
