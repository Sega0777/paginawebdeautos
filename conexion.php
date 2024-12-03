<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "distribuidora_autos";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Comprobar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
