<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Distribuidora de Autos</title>
    <style>
        /* Estilos generales */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }

        /* Encabezado */
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

        /* Barra de navegación */
        nav {
            background-color: #00264d;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        nav ul {
            list-style: none;
            margin: 0;
            padding: 10px 20px;
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        nav ul li {
            display: inline;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            font-size: 1.1rem;
            padding: 8px 12px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        nav ul li a:hover {
            background-color: #004080;
        }

        /* Contenido principal */
        main {
            padding: 20px;
        }

        .autos {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .auto-card {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            padding: 20px;
        }

        .auto-card img {
            width: 100%;
            height: auto;
            border-bottom: 1px solid #ddd;
            margin-bottom: 15px;
            transition: transform 0.3s ease-in-out;
        }

        .auto-card img:hover {
            transform: scale(1.1);
        }

        .auto-card h3 {
            font-size: 1.5rem;
            margin: 0 0 10px;
        }

        .auto-card p {
            font-size: 1rem;
            margin: 0 0 15px;
        }

        .auto-card button {
            background-color: #004080;
            color: white;
            border: none;
            padding: 10px 15px;
            font-size: 1rem;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .auto-card button:hover {
            background-color: #00264d;
        }

        /* Pie de página */
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
    <!-- Barra de navegación -->
    <nav>
        <ul>
            <li><a href="registro.php">Iniciar Sesion</a></li>
            <li><a href="#inicio">Menú</a></li>
            <li><a href="modelos.php">Modelos</a></li>
            <li><a href="reserva.php">Reserva</a></li>
            <li><a href="registro_usuario.php">Registrar</a></li>

        </ul>
    </nav>

    <!-- Encabezado -->
    <header id="inicio">
        <h1>Distribuidora de Autos</h1>
        <p>Encuentra el auto de tus sueños y resérvalo hoy mismo.</p>
    </header>

    <!-- Contenido principal -->
    <main>
        <!-- Sección de Modelos -->
        <section id="modelos" class="autos">
            <h2>Modelos Más Elegidos</h2>

            <?php
            // Conexión a la base de datos usando MySQLi
            $conn = new mysqli("localhost", "root", "", "distribuidora_autos");

            // Verificar conexión
            if ($conn->connect_error) {
                die("Conexión fallida: " . $conn->connect_error);
            }

            // Consulta segura para obtener los modelos más elegidos
            $stmt = $conn->prepare("SELECT imagen, modelo, precio FROM autos LIMIT ?");
            $limit = 3; // Cambia el límite según lo que necesites
            $stmt->bind_param("i", $limit);
            $stmt->execute();
            $result = $stmt->get_result();

            // Mostrar cada modelo en una tarjeta
            while ($row = $result->fetch_assoc()) {
                echo "
                <div class='auto-card'>
                    <img src='" . htmlspecialchars($row['imagen'], ENT_QUOTES, 'UTF-8') . "' alt='" . htmlspecialchars($row['modelo'], ENT_QUOTES, 'UTF-8') . "'>
                    <h3>" . htmlspecialchars($row['modelo'], ENT_QUOTES, 'UTF-8') . "</h3>
                    <p>Precio: $" . htmlspecialchars($row['precio'], ENT_QUOTES, 'UTF-8') . "</p>
                    <a href='reserva.php'><button>Reservar</button></a>
                </div>";
            }

            // Cerrar la declaración y la conexión
            $stmt->close();
            $conn->close();
            ?>


    </main>

    <!-- Pie de página -->
    <footer>
        <p>&copy; 2024 Distribuidora de Autos. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
