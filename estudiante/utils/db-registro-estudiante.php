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
    ?>

    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Registro para uso de laboratorios para estudiantes</title>
        <link rel='stylesheet' href='../../shared/lib/alertify/css/alertify.css'>
        <link rel='stylesheet' href='../../shared/lib/alertify/css/themes/bootstrap.css'>
        <script src='../../shared/lib/alertify/alertify.min.js'></script>
        <style>
            .ajs-cancel {
                display: none;
            }
        </style>
    </head>

    <body>
        <script>
            alertify.confirm('Formulario enviado de manera exitosa, saliendo...', function () { window.location.href = '../../shared/cerrar-sesion.php' }).setHeader('Mensaje').set({transition: 'zoom', 'labels': { ok: 'Salir', cancel: 'Cancelar' }}).autoOk(10);
        </script>
    </body>

    <?php
} else {
    header("Location: ../../index.php");
    exit();
}
?>