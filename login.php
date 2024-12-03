<?php
session_start(); // Iniciar sesión de forma segura

// Configurar cookies de sesión de forma segura
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.use_strict_mode', 1);

// Verificar si el usuario ya está logueado
if (isset($_SESSION['user_id'])) {
    header("Location: pagina.php"); // Redirigir si ya está logueado
    exit();
}

// Variables de error
$error_message = null;

// Procesar el formulario de inicio de sesión
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitizar entradas del formulario
    $usuario = filter_input(INPUT_POST, 'usuario', FILTER_SANITIZE_STRING);
    $contrasena = filter_input(INPUT_POST, 'contrasena', FILTER_SANITIZE_STRING);

    // Validar que los campos no estén vacíos
    if (!$usuario || !$contrasena) {
        $error_message = "Por favor, completa todos los campos.";
    } else {
        // Conexión segura a la base de datos
        $conn = new mysqli("localhost", "root", "", "distribuidora_autos");

        // Verificar conexión
        if ($conn->connect_error) {
            die("Conexión fallida: " . htmlspecialchars($conn->connect_error));
        }

        // Consulta preparada para verificar las credenciales
        $sql = "SELECT id, contrasena FROM usuarios WHERE usuario = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $usuario);
            $stmt->execute();
            $stmt->store_result();

            // Verificar si el usuario existe
            if ($stmt->num_rows === 1) {
                $stmt->bind_result($user_id, $hashed_password);
                $stmt->fetch();

                // Verificar la contraseña
                if (password_verify($contrasena, $hashed_password)) {
                    // Iniciar sesión
                    $_SESSION['user_id'] = $user_id;
                    header("Location: pagina.php"); // Redirigir a la página principal
                    exit();
                } else {
                    $error_message = "Credenciales incorrectas.";
                }
            } else {
                $error_message = "Credenciales incorrectas.";
            }

            $stmt->close();
        } else {
            $error_message = "Error al procesar la solicitud. Inténtalo de nuevo.";
        }

        $conn->close();
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

        <?php
        if (isset($error_message)) {
            echo "<p style='color:red;'>$error_message</p>";
        }
        ?>

        <form action="login.php" method="POST">
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
