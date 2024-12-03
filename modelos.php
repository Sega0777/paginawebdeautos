<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modelos de Autos</title>
    <style>
        /* Estilos generales */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }

        /* Barra de navegación */
        nav {
            background-color: #004080;
            display: flex;
            justify-content: center;
            padding: 10px 0;
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

        header {
            background-color: #004080;
            color: white;
            padding: 20px;
            text-align: center;
        }

        header h1 {
            margin: 0;
            font-size: 2.5rem;
        }

        header p {
            margin: 5px 0 0;
            font-size: 1.2rem;
        }

        /* Contenedor principal */
        .modelos-container {
            padding: 20px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        /* Tarjeta de modelo */
        .modelo-card {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            padding: 20px;
            transition: transform 0.3s ease;
        }

        .modelo-card:hover {
            transform: translateY(-5px);
        }

        .modelo-card img {
            width: 100%;
            height: auto;
            border-bottom: 1px solid #ddd;
            margin-bottom: 15px;
        }

        .modelo-card h3 {
            font-size: 1.5rem;
            margin: 0 0 10px;
        }

        .modelo-card p {
            font-size: 1rem;
            margin: 0 0 15px;
        }

        .modelo-card button {
            background-color: #004080;
            color: white;
            border: none;
            padding: 10px 15px;
            font-size: 1rem;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .modelo-card button:hover {
            background-color: #00264d;
        }

        footer {
            text-align: center;
            padding: 10px;
            background-color: #004080;
            color: white;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <!--------------------------------------- Barra de navegación ------------------------------------->
    <nav>
        <a href="pagina.php">Inicio</a>
        <a href="modelos.php">Modelos</a>
        <a href="reserva.php">Reserva</a>
        <a href="registro_usuario.php">Registrar</a>

    </nav>

    <!------------------------------------------- Encabezado ------------------------------------------>
    <header>
        <h1>Modelos de Autos</h1>
        <p>Explora nuestras opciones y encuentra el auto perfecto para ti.</p>
    </header>

    <!--------------------------------------------- modelos ------------------------------------------->
    <main>
        <div class="modelos-container">
            

            <?php
                // Configuración de la base de datos
                $host = "localhost";
                $usuario = "root";
                $password = "";
                $db = "distribuidora_autos";

                // Conexión a la base de datos con manejo de errores
                $conn = new mysqli($host, $usuario, $password, $db);

                if ($conn->connect_error) {
                    // No mostrar detalles del error al usuario
                    die("Error al conectar con la base de datos. Por favor, inténtelo más tarde.");
                }

                // Consulta preparada para obtener los modelos
                $stmt = $conn->prepare("SELECT modelo, descripcion, precio, imagen FROM autos");
                if ($stmt) {
                    $stmt->execute();
                    $stmt->bind_result($modelo, $descripcion, $precio, $imagen);

                    // Mostrar los resultados en tarjetas
                    while ($stmt->fetch()) {
                        // Escapar datos para evitar XSS
                        $modelo = htmlspecialchars($modelo, ENT_QUOTES, 'UTF-8');
                        $descripcion = htmlspecialchars($descripcion, ENT_QUOTES, 'UTF-8');
                        $precio = htmlspecialchars($precio, ENT_QUOTES, 'UTF-8');
                        $imagen = htmlspecialchars($imagen, ENT_QUOTES, 'UTF-8');

                        echo "
                        <div class='modelo-card'>
                            <img src='$imagen' alt='$modelo'>
                            <h3>$modelo</h3>
                            <p>$descripcion Precio: \$$precio</p>
                            <a href='reserva.php'><button>Reservar</button></a>
                        </div>";
                    }

                    $stmt->close();
                } else {
                    echo "<p>No se pudieron cargar los modelos en este momento. Por favor, inténtelo más tarde.</p>";
                }

                // Cerrar conexión
                $conn->close();
            ?>
            
        </div>
    </main>

    <!------------------------------------------ Pie de página ------------------------------------------->
    <footer>
        <p>&copy; 2024 Distribuidora de Autos. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
