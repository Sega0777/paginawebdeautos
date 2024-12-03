<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserva tu Auto</title>
    <style>
        /* Estilos generales */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Contenedor del formulario */
        .reserva-container {
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            max-width: 500px;
            width: 100%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .reserva-container h1 {
            text-align: center;
            color: #004080;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .form-group textarea {
            resize: none;
            height: 100px;
        }

        .form-group button {
            width: 100%;
            padding: 10px;
            font-size: 1.2rem;
            background-color: #004080;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .form-group button:hover {
            background-color: #00264d;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 15px;
            font-size: 1rem;
            color: #004080;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="reserva-container">
        <h1>Reserva tu Auto</h1>

        <?php
        // Conexión a la base de datos
        $conn = new mysqli("localhost", "root", "", "distribuidora_autos");

        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Sanitización de entradas
            $nombre = filter_var(trim($_POST['nombre']), FILTER_SANITIZE_STRING);
            $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
            $telefono = filter_var(trim($_POST['telefono']), FILTER_SANITIZE_STRING);
            $modelo_id = filter_var(trim($_POST['modelo_id']), FILTER_SANITIZE_NUMBER_INT);

            // Validación de campos
            if (empty($nombre) || empty($email) || empty($telefono) || empty($modelo_id)) {
                die("Error: Todos los campos son obligatorios.");
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                die("Error: El correo electrónico no es válido.");
            }

            // Verificar que el usuario exista en la base de datos
            $usuario_id = obtenerUsuarioIdPorEmail($conn, $email);
            if (!$usuario_id) {
                die("Error: El correo electrónico no está registrado.");
            }

            // Insertar la reserva de manera segura con consulta preparada
            $sql = "INSERT INTO reservas (usuario_id, auto_id) VALUES (?, ?)";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("ii", $usuario_id, $modelo_id);
                if ($stmt->execute()) {
                    echo "<p>Reserva realizada con éxito.</p>";
                } else {
                    echo "<p>Error al realizar la reserva: " . $stmt->error . "</p>";
                }
                $stmt->close();
            } else {
                echo "<p>Error al preparar la consulta: " . $conn->error . "</p>";
            }
        }

        // Función para obtener el ID del usuario por correo electrónico
        function obtenerUsuarioIdPorEmail($conn, $email) {
            $sql = "SELECT id FROM usuarios WHERE email = ?";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $stmt->bind_result($id);
                $stmt->fetch();
                $stmt->close();
                return $id;
            }
            return null;
        }
        ?>


        <form action="#" method="POST">
            <!-- Nombre completo -->
            <div class="form-group">
                <label for="nombre">Nombre Completo</label>
                <input type="text" id="nombre" name="nombre" placeholder="Ingresa tu nombre completo" required>
            </div>

            <!-- Email -->
            <div class="form-group">
                <label for="email">Correo Electrónico</label>
                <input type="email" id="email" name="email" placeholder="Ingresa tu correo electrónico" required>
            </div>

            <!-- Teléfono -->
            <div class="form-group">
                <label for="telefono">Teléfono</label>
                <input type="tel" id="telefono" name="telefono" placeholder="Ingresa tu número de teléfono" required>
            </div>

            <!-- Modelo del auto -->
            <div class="form-group">
                <label for="modelo">Modelo del Auto</label>
                <select id="modelo" name="modelo_id" required>
                    <option value="" disabled selected>Selecciona un modelo</option>
                    <?php
                    // Cargar modelos desde la base de datos para el select
                    $result = $conn->query("SELECT id, modelo FROM autos");
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['id']}'>{$row['modelo']}</option>";
                    }
                    ?>
                </select>
                </div>

            <!-- Botón de enviar -->
            <div class="form-group">
                <button type="submit">Enviar Reserva</button>
            </div>
        </form>

        <!-- Enlace de regreso -->
        <a href="pagina.php" class="back-link">Volver a la Página Principal</a>
    </div>
</body>
</html>
