<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <style>

        /* Estilos generales */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: white;
        }

        nav {
            background-color: rgba(0, 64, 128, 0.7); /* Fondo semitransparente */
            display: flex;
            justify-content: center;
            padding: 10px 0;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 10;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-size: 1.2rem;
            transition: color 0.3s ease;
        }

        nav a:hover {
            color: #ffcc00;
        }

        /* Enlace de regreso */
        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            font-size: 1rem;
            color: #ffcc00;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        /* Fondo de video */
        .video-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -1;
        }

    </style>
</head>
<body>
    <!-- Video de fondo -->
    <video class="video-background" autoplay muted loop>
        <source src="fondo.mp4" type="video/mp4">
        
    </video>

    <!-- Barra de navegación -->
    <nav>
        <a href="registro.php">Iniciar Sesion</a>
        <a href="pagina.php">Inicio</a>
        <a href="modelos.php">Modelos</a>
        <a href="reserva.php">Reserva</a>
        <a href="registro_usuario.php">Registrar</a>

        
    </nav>

    <h2>Registro de Usuario</h2>
    <form action="registro_usuario.php" method="POST">
        <label for="nombre">Nombre completo:</label>
        <input type="text" id="nombre" name="nombre" required>

        <label for="email">Correo electrónico:</label>
        <input type="email" id="email" name="email" required>

        <label for="telefono">Teléfono:</label>
        <input type="text" id="telefono" name="telefono" required>

        <label for="usuario">Nombre de usuario:</label>
        <input type="text" id="usuario" name="usuario" required>

        <label for="contrasena">Contraseña:</label>
        <input type="password" id="contrasena" name="contrasena" required>

        <button type="submit">Registrar</button>
    </form>

    <!-- Enlace de regreso -->
    <a href="pagina.php" class="back-link">Volver a la Página Principal</a>
</body>
</html>

<?php
// Conexión a la base de datos usando MySQLi
$conn = new mysqli("localhost", "root", "", "distribuidora_autos");

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . htmlspecialchars($conn->connect_error, ENT_QUOTES, 'UTF-8'));
}

// Procesar el formulario al enviarlo
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitizar entradas
    $nombre = htmlspecialchars(trim($_POST['nombre']), ENT_QUOTES, 'UTF-8');
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $telefono = htmlspecialchars(trim($_POST['telefono']), ENT_QUOTES, 'UTF-8');
    $usuario = htmlspecialchars(trim($_POST['usuario']), ENT_QUOTES, 'UTF-8');
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT); // Encriptar contraseña

    // Validar email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Correo electrónico inválido.");
    }

    // Consulta segura para insertar el nuevo usuario
    $sql = "INSERT INTO usuarios (nombre, email, telefono, usuario, contrasena) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $nombre, $email, $telefono, $usuario, $contrasena);

    if ($stmt->execute()) {
        echo "Usuario registrado con éxito. <a href='registro.php'>Iniciar sesión</a>";
    } else {
        echo "Error al registrar usuario: " . htmlspecialchars($stmt->error, ENT_QUOTES, 'UTF-8');
    }

    // Cerrar la declaración y la conexión
    $stmt->close();
    $conn->close();
}
?>
