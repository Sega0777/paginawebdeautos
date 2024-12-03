<?php
// Conexión a la base de datos
include('conexion.php');

// Llamar al procedimiento almacenado
$sql = "CALL obtenerReservasPorUsuario()";
$result = $conn->query($sql);

// Verificar si hay resultados
if ($result->num_rows > 0) {
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr>
            <th>ID Usuario</th>
            <th>Nombre</th>
            <th>Total Reservas</th>
          </tr>";

    // Mostrar resultados
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['usuario_id']}</td>
                <td>{$row['nombre_usuario']}</td>
                <td>{$row['total_reservas']}</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<p>No hay reservas registradas.</p>";
}

// Cerrar conexión
$conn->close();
?>
