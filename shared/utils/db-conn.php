<?php
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'sistema_reservas_eic';

$conn = new mysqli($host, $username, $password, $dbname);
if (!$conn) {
    die("No se pudo conectar con la base de datos." . $conn->error);
}
?>