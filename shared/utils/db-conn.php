<?php
include 'variables.php';

$conn = new mysqli($host, $username, $password, $dbname);
$conn->set_charset("utf8");
if (!$conn) {
    die("No se pudo conectar con la base de datos." . $conn->error);
}
?>