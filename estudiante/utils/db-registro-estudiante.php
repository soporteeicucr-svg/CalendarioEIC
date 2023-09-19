<?php
include "../../shared/utils/db-conn.php";

session_start();

if (isset($_SESSION['id']) && isset($_SESSION['usuario'])) {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $carnet = $_POST['carnet'];
    $celular = $_POST['celular'];
    $laboratorio = $_POST['laboratorio'];
    $fechaIngreso = $_POST['fecha_ingreso'];

    $stmt = $conn->prepare("INSERT INTO registro_estudiante(nombre,correo,carnet,celular,laboratorio,fecha_ingreso) values(?,?,?,?,?,?)");
    $stmt->bind_param("ssssss", $nombre, $correo, $carnet, $celular, $laboratorio, $fechaIngreso);
    $stmt->execute();
    $stmt->close();
    $conn->close();
    echo "<script>";
    echo "alert('Formulario enviado de manera exitosa');";
    echo "window.location.href='../../shared/cerrar-sesion.php';";
    echo "</script>";
} else {
    header("Location: ../../index.php");
    exit();
}
?>