<?php
include '../../shared/utils/db-conn.php';

session_start();

if (isset($_SESSION['usuario']) && isset($_SESSION['tipo'])) {
    if ($_SESSION['tipo'] == 'docente') {
        $nombre = $_POST['nombre'];
        $correo = $_POST['correo'];
        $cedula = $_POST['cedula'];
        $celular = $_POST['celular'];
        $siglaCurso = $_POST['sigla_curso'];
        $espacio = $_POST['espacio'];
        $fechaIngreso = $_POST['fecha_ingreso'];

        $stmt = $conn->prepare("INSERT INTO registro_docente(nombre,correo,cedula,celular,sigla_curso,espacio,fecha_ingreso) values(?,?,?,?,?,?,?)");
        $stmt->bind_param("sssssss", $nombre, $correo, $cedula, $celular, $siglaCurso, $espacio, $fechaIngreso);
        $stmt->execute();
        $stmt->close();
        $conn->close();
        if ($_POST['continue'] == 'yes') {
            header("Location: ../inicio.php");
        } else {
            header("Location: ../../shared/cerrar-sesion.php");
        }
        exit();
    } else {
        header("Location: ../../index.php");
        exit();
    }
} else {
    header("Location: ../../index.php");
    exit();
}
?>