<?php
session_start();
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

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['Id_Usuarios'])) {
    // Redirigir al inicio de sesión si no ha iniciado sesión
    header("Location: ../inicio_sesion.html");
    exit();
}

// Obtener los valores del formulario
$peso_actual = $_POST['peso_actual'] ?? NULL;
$altura = $_POST['altura'] ?? NULL;
$objetivo_peso = $_POST['objetivo_peso'] ?? NULL;
$genero = $_POST['genero'] ?? NULL;
$nivel_actividad = $_POST['nivel_actividad'] ?? NULL;
$meta_salud = $_POST['meta_salud'] ?? NULL;
$preferencia_dieta = $_POST['preferencia_dieta'] ?? NULL;
$tiempo_ejercicio = $_POST['tiempo_ejercicio'] ?? NULL;
$preferencia_ejercicio = $_POST['preferencia_ejercicio'] ?? NULL;

// Validar si los campos no son vacíos
if (empty($peso_actual) || empty($altura) || empty($objetivo_peso) || empty($genero) || empty($nivel_actividad) || empty($meta_salud) || empty($preferencia_dieta) || empty($tiempo_ejercicio) || empty($preferencia_ejercicio)) {
    echo "Error: Todos los campos deben ser completados.";
    exit();
}

// Preparar la consulta con todos los valores insertados directamente en la consulta SQL
$userId = $_SESSION['Id_Usuarios'];

$sql = "UPDATE Usuarios 
SET 
    Peso_actual_kg = '$peso_actual',
    Altura_cm = '$altura',
    Objetivo_peso_kg = '$objetivo_peso',
    Genero = '$genero',
    Nivel_actividad = '$nivel_actividad',
    Meta_salud = '$meta_salud',
    Preferencia_dieta = '$preferencia_dieta',
    Tiempo_ejercicio = '$tiempo_ejercicio',
    Preferencia_ejercicio = '$preferencia_ejercicio'
WHERE Id_Usuarios = '$userId';";



if ($conn->query($sql) === TRUE) {
    // Redirigir a index.html después de insertar correctamente
    header("Location: ../index.html");
    exit();
} else {
    echo "Error al insertar los datos: " . $conn->error;
}

// Cerrar la conexión
$conn->close();
?>
