<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['Id_Usuarios'])) {
    // Redirigir al inicio de sesión si no ha iniciado sesión
    header("Location: inicio_sesion.html");
    exit();
}

// Configuración de conexión a la base de datos
$servername = "localhost";
$username = "root";  // Cambiar estos valores si es necesario
$password = "";      // Cambiar estos valores si es necesario
$dbname = "Shapy";

// Crear conexión a la base de datos
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar si la conexión es exitosa
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener los datos del usuario desde la base de datos
$userId = $_SESSION['Id_Usuarios'];
$sql = "SELECT Nombres_Apellidos, Email, Edad, Peso_actual_kg, Altura_cm, Objetivo_peso_kg, Genero, Nivel_actividad, Meta_salud, Preferencia_dieta, Tiempo_ejercicio, Preferencia_ejercicio FROM Usuarios WHERE Id_Usuarios = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);  // Bind para evitar inyección SQL
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Asignar los datos del usuario a variables
    $userData = $result->fetch_assoc();
    $nombre_usuario = $userData['Nombres_Apellidos'];
    $email = $userData['Email'];
    $edad = $userData['Edad'];
    $peso_actual = $userData['Peso_actual_kg'];
    $altura = $userData['Altura_cm'];
    $objetivo_peso = $userData['Objetivo_peso_kg'];
    $genero = $userData['Genero'];
    $nivel_actividad = $userData['Nivel_actividad'];
    $meta_salud = $userData['Meta_salud'];
    $preferencia_dieta = $userData['Preferencia_dieta'];
    $tiempo_ejercicio = $userData['Tiempo_ejercicio'];
    $preferencia_ejercicio = $userData['Preferencia_ejercicio'];
} else {
    echo "No se encontraron datos del usuario.";
    exit();
}

// Cerrar la conexión a la base de datos
$conn->close();

// Cerrar sesión
if (isset($_POST['logout'])) {
    session_unset(); 
    session_destroy();
    header("Location: ../inicio_sesion.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Espacio Personal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.2/css/all.css">
    <link href="../CSS/styles.css" rel="stylesheet">
    <link href="../CSS/espacio.css" rel="stylesheet">
    <link href="../CSS/bootstrap.css" rel="stylesheet">
</head>
<body>

    <!-- Sección de navegación -->
    <section class="navigation">
        <div class="nav-container">
            <div class="brand">
                <img class="logo" src="../IMG/LOGO.png">
            </div>
            <nav>
                <div class="nav-mobile"><a id="navbar-toggle" href="#!"><span></span></a></div>
                <ul class="nav-list">
                    <li><a href="../index.html">Home</a></li>
                    <li><a href="../Preguntas.html">Preguntas frecuentes</a></li>
                    <li><a href="../Inicio_sesion.html">Espacio personal</a></li>
                    <li><a href="../Sobre_nosotros.html">Acerca nosotros</a></li>
                </ul>
            </nav>
        </div>
    </section>

    <!-- Sección de perfil de usuario -->
    <section class="user-profile">
        <div class="container">
            <div class="profile-card">
                <h2> <?php echo htmlspecialchars($nombre_usuario); ?></h2>
                <p><i class="fas fa-user"></i> <strong>Nombre:</strong> <?php echo $nombre_usuario; ?></p>
                <p><i class="fas fa-envelope"></i> <strong>Email:</strong> <?php echo $email; ?></p>
                <p><i class="fas fa-birthday-cake"></i> <strong>Edad:</strong> <?php echo $edad; ?> años</p>
                <p><i class="fas fa-weight"></i> <strong>Peso actual:</strong> <?php echo $peso_actual; ?> kg</p>
                <p><i class="fas fa-ruler-vertical"></i> <strong>Altura:</strong> <?php echo $altura; ?> cm</p>
                <p><i class="fas fa-bullseye"></i> <strong>Objetivo de peso:</strong> <?php echo $objetivo_peso; ?> kg</p>
                <p><i class="fas fa-venus-mars"></i> <strong>Género:</strong> <?php echo $genero; ?></p>
                <p><i class="fas fa-running"></i> <strong>Nivel de actividad:</strong> <?php echo $nivel_actividad; ?></p>
                <p><i class="fas fa-heart"></i> <strong>Meta de salud:</strong> <?php echo $meta_salud; ?></p>
                <p><i class="fas fa-utensils"></i> <strong>Preferencia de dieta:</strong> <?php echo $preferencia_dieta; ?></p>
                <p><i class="fas fa-clock"></i> <strong>Tiempo de ejercicio:</strong> <?php echo $tiempo_ejercicio; ?> minutos al día</p>
                <p><i class="fas fa-dumbbell"></i> <strong>Preferencia de ejercicio:</strong> <?php echo $preferencia_ejercicio; ?></p>

                <!-- Botón para abrir el modal de cambio de contraseña -->
                <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#changePasswordModal">
                    Cambiar Contraseña
                </button>
            </div>

            <!-- Modal de cambio de contraseña -->
            <div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="changePasswordModalLabel">Actualizar Contraseña</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="cambiar_contraseña.php" method="POST">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="currentPassword">Contraseña Actual</label>
                                    <input type="password" id="currentPassword" name="current_password" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="newPassword">Nueva Contraseña</label>
                                    <input type="password" id="newPassword" name="new_password" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="confirmNewPassword">Confirmar Nueva Contraseña</label>
                                    <input type="password" id="confirmNewPassword" name="new_password_confirm" class="form-control" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-primary">Actualizar Contraseña</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Formulario para cerrar sesión -->
            <form method="POST" action="">
                <button type="submit" name="logout" class="btn-custom">Cerrar sesión</button>
            </form>
        </div>
    </section>

    <!-- Scripts de Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
