<?php
include "../../shared/utils/db-conn.php";

session_start();

if (isset($_SESSION['id']) && isset($_SESSION['usuario'])) {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $cedula = $_POST['cedula'];
    $celular = $_POST['celular'];
    $siglaCurso = $_POST['sigla_curso'];
    $espacio = $_POST['espacio'];
    // $hdmi = $_POST['hdmi'];
    $fechaIngreso = $_POST['fecha_ingreso'];

    $stmt = $conn->prepare("insert into registro_docente(nombre,correo,cedula,celular,sigla_curso,espacio,fecha_ingreso) values(?,?,?,?,?,?,?)");
    $stmt->bind_param("sssssss", $nombre, $correo, $cedula, $celular, $siglaCurso, $espacio, /* $hdmi, */$fechaIngreso);
    $stmt->execute();
    $stmt->close();
    $conn->close();
    header('Location: ../registro-correcto.php');
} else {
    header("Location: ../../index.php");
    exit();
}
?>