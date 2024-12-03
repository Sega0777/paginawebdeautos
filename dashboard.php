<?php
// Iniciar la sesión
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: registro.php");
    exit();
}

// Conexión a la base de datos
include('conexion.php');

// Preparar consulta para obtener las reservas del usuario autenticado
$user_id = $_SESSION['user_id'];
$sql = "SELECT r.id, a.modelo, r.fecha_reserva 
        FROM reservas r
        JOIN autos a ON r.auto_id = a.id
        WHERE r.usuario_id = ?";
        
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    error_log("Error al preparar la consulta SQL: " . $conn->error);
    die("Ocurrió un problema al cargar las reservas. Intenta más tarde.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        nav {
            background-color: #004080;
            color: white;
            padding: 10px;
            text-align: center;
        }
        nav a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
        }
        nav a:hover {
            text-decoration: underline;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .reservas-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .reservas-table th, .reservas-table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        .reservas-table th {
            background-color: #004080;
            color: white;
        }
        .logout-link {
            display: block;
            margin-top: 20px;
            text-align: center;
            color: #004080;
            text-decoration: none;
        }
        .logout-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<!-- Barra de navegación -->
<nav>
    <a href="dashboard.php">Dashboard</a>
    <a href="pagina.php">Inicio</a>
    <a href="modelos.php">Modelos</a>
    <a href="reserva.php">Reservar Auto</a>
    <a href="logout.php">Cerrar Sesión</a>
</nav>

<!-- Contenedor principal -->
<div class="container">
    <h2>Bienvenido a tu Dashboard</h2>
    <h3>Tus reservas</h3>

    <?php if ($result->num_rows > 0): ?>
        <!-- Mostrar las reservas en una tabla -->
        <table class="reservas-table">
            <thead>
                <tr>
                    <th>Modelo de Auto</th>
                    <th>Fecha de Reserva</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['modelo']; ?></td>
                        <td><?php echo $row['fecha_reserva']; ?></td>
                        <td><a href="cancelar_reserva.php?id=<?php echo $row['id']; ?>">Cancelar</a></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No tienes reservas aún.</p>
    <?php endif; ?>

    <!-- Enlace de cerrar sesión -->
    <a href="logout.php" class="logout-link">Cerrar sesión</a>
</div>

</body>
</html>

<?php
// Liberar resultados y cerrar conexiones
$result->free();
$stmt->close();
$conn->close();
?>
