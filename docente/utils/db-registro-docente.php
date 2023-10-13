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
    $fechaIngreso = $_POST['fecha_ingreso'];

    $stmt = $conn->prepare("insert into registro_docente(nombre,correo,cedula,celular,sigla_curso,espacio,fecha_ingreso) values(?,?,?,?,?,?,?)");
    $stmt->bind_param("sssssss", $nombre, $correo, $cedula, $celular, $siglaCurso, $espacio, $fechaIngreso);
    $stmt->execute();
    $stmt->close();
    $conn->close();
    ?>

    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Registro para uso de laboratorios para docentes</title>
        <link rel='stylesheet' href='../../shared/lib/alertify/css/alertify.css'>
        <link rel='stylesheet' href='../../shared/lib/alertify/css/themes/bootstrap.css'>
        <script src='../../shared/lib/alertify/alertify.min.js'></script>
    </head>

    <body>
        <script>
            alertify.confirm('Mensaje', 'Formulario enviado de manera exitosa, ¿desea cerrar la sesión?', function () { window.location.href = '../../shared/cerrar-sesion.php' }, function () { window.location.href = '../inicio.php' }).set({ transition: 'zoom', 'labels': { ok: 'Si', cancel: 'No' } }).autoOk(10);
        </script>
    </body>

    <?php
} else {
    header("Location: ../../index.php");
    exit();
}
?>