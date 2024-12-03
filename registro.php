<?php
// Conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "distribuidora_autos");

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Procesar el formulario al enviarlo
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitizar entradas para evitar inyecciones
    $usuario = filter_var($_POST['usuario'], FILTER_SANITIZE_STRING);
    $contrasena = $_POST['contrasena']; // Contraseña no se sanitiza, pero debe validarse

    // Consulta para verificar las credenciales del usuario
    $sql = "SELECT * FROM usuarios WHERE usuario=?";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            // Verificar la contraseña
            if (password_verify($contrasena, $row['contrasena'])) {
                // Iniciar sesión (puedes almacenar el ID del usuario en una sesión)
                session_start();
                // Regenerar ID de sesión para prevenir fijación de sesión
                session_regenerate_id(true);
                $_SESSION['user_id'] = $row['id'];
                header("Location: dashboard.php"); // Redirigir a la página principal
                exit();
            } else {
                echo "<p style='color:red;'>Contraseña incorrecta.</p>";
            }
        } else {
            echo "<p style='color:red;'>Usuario no encontrado.</p>";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
    <style>
        /* Estilos generales */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: white;
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

        /* Barra de navegación */
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

        /* Formulario de inicio de sesión */
        .login-container {
            width: 100%;
            max-width: 400px;
            margin: 100px auto;
            padding: 20px;
            background-color: rgba(0, 64, 128, 0.8); /* Fondo semitransparente */
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        .login-container h2 {
            text-align: center;
            color: #ffcc00;
        }

        .login-container input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }

        .login-container button {
            width: 100%;
            padding: 12px;
            background-color: #004080;
            color: white;
            border: none;
            font-size: 1rem;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .login-container button:hover {
            background-color: #00264d;
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

    <!-- Formulario de inicio de sesión -->
    <div class="login-container">
        <h2>Iniciar sesión</h2>

        <form action="registro.php" method="POST">
            <label for="usuario">Nombre de usuario</label>
            <input type="text" id="usuario" name="usuario" placeholder="Introduce tu nombre de usuario" required>

            <label for="contrasena">Contraseña</label>
            <input type="password" id="contrasena" name="contrasena" placeholder="Introduce tu contraseña" required>

            <button type="submit">Iniciar sesión</button>
        </form>

        <a href="pagina.php" class="back-link">Volver a la Página Principal</a>
    </div>
</body>
</html>
