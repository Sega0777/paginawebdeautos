<?php
// Iniciar la sesión para verificar si el usuario está autenticado
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header("Location: registro.php");
    exit();
}

// Conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "distribuidora_autos");

// Verificar la conexión
if ($conn->connect_error) {
    // Registrar el error en los logs del servidor y mostrar un mensaje genérico al usuario
    error_log("Conexión fallida: " . $conn->connect_error);
    die("Ocurrió un problema con la conexión a la base de datos. Intente más tarde.");
}

// Verificar si se ha enviado un ID de reserva para cancelar
if (isset($_GET['id'])) {
    $reserva_id = filter_var($_GET['id'], FILTER_VALIDATE_INT);

    if (!$reserva_id) {
        echo "<p style='color:red;'>ID de reserva inválido.</p>";
        exit();
    }

    // Verificar si la reserva pertenece al usuario actual
    $sql = "SELECT * FROM reservas WHERE id = ? AND usuario_id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ii", $reserva_id, $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Proceder a cancelar la reserva
            $sql_cancelar = "DELETE FROM reservas WHERE id = ? AND usuario_id = ?";
            if ($stmt_cancelar = $conn->prepare($sql_cancelar)) {
                $stmt_cancelar->bind_param("ii", $reserva_id, $_SESSION['user_id']);
                if ($stmt_cancelar->execute()) {
                    header("Location: reserva.php?status=success");
                    exit();
                } else {
                    error_log("Error al cancelar la reserva: " . $stmt_cancelar->error);
                    echo "<p style='color:red;'>Ocurrió un error al cancelar la reserva. Intente nuevamente más tarde.</p>";
                }
                $stmt_cancelar->close();
            }
        } else {
            echo "<p style='color:red;'>No se encontró la reserva o no pertenece a este usuario.</p>";
        }
        $stmt->close();
    }
} else {
    echo "<p style='color:red;'>No se ha proporcionado un ID de reserva para cancelar.</p>";
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
