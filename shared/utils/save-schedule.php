<?php
include 'db-conn.php';
include 'send-mail.php';

session_start();

if (isset($_SESSION['id']) && isset($_SESSION['usuario'])) {
    $id = $_POST['id'];
    $espacio = $_POST['espacio'];
    $siglaCurso = $_POST['sigla_curso'];
    $nombreCurso = $_POST['nombre_curso'];
    $solicitante = $_POST['solicitante'];
    $correo = $_POST['correo'];
    $fecha = $_POST['fecha'];
    $horaInicio = $fecha . " " . $_POST['hora_inicio'];
    $horaFin = $fecha . " " . $_POST['hora_fin'];

    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        echo "<script>alert('Error: No hay datos para guardar'); location.replace('./')</script>";
        $conn->close();
        exit;
    }
    extract($_POST);
    $allday = isset($allday);

    $sql = "";

    if (empty($id)) {
        if ($_SESSION['tipo'] == "docente") {
            $sql = "INSERT INTO `lista_horarios` (`espacio`,`sigla_curso`,`nombre_curso`,`solicitante`,`correo`,`hora_inicio`,`hora_fin`, estado) VALUES ('$espacio','$siglaCurso','$nombreCurso','$solicitante','$correo','$horaInicio','$horaFin','pendiente')";
        } else if ($_SESSION['tipo'] == "administrativo") {
            $sql = "INSERT INTO `lista_horarios` (`espacio`,`sigla_curso`,`nombre_curso`,`solicitante`,`correo`,`hora_inicio`,`hora_fin`, estado) VALUES ('$espacio','$siglaCurso','$nombreCurso','$solicitante','$correo','$horaInicio','$horaFin','aprobado')";
            $message = "<p>Su reservación ha sido creada satisfactoriamente, a continuación se muestran los detalles de la misma.</p>
            <p>ID de reservación: <b>$id</b></p>
            <p>Espacio reservado: <b>$espacio</b></p>
            <p>Sigla del curso: <b>$siglaCurso</b></p>
            <p>Nombre del curso: <b>$nombreCurso</b></p>
            <p>Nombre del solicitante: <b>$solicitante</b></p>
            <p>Correo del solicitante: <b>$correo</b></p>
            <p>Desde: <b>$horaInicio</b></p>
            <p>Hasta: <b>$horaFin</b></p>
            <p>Recuerde utilizar el sistema de registro y retiro de cartuchera antes de dirigirse a su reservación, gracias.</p>";
            sendMail($correo, 'Su reservación a sido creada', $message);
        }
    } else {
        $sql = "UPDATE `lista_horarios` set `espacio` = '{$espacio}', `sigla_curso` = '{$siglaCurso}', `nombre_curso` = '{$nombreCurso}', `solicitante` = '{$solicitante}', `correo` = '{$correo}', `hora_inicio` = '{$horaInicio}', `hora_fin` = '{$horaFin}' where `id` = '{$id}'";
    }
    $save = $conn->query($sql);
    if ($save) {
        if ($_SESSION['tipo'] == "docente") {
            echo "<script>alert('Solicitud de reserva enviada de forma exitosa');";
        } else if ($_SESSION['tipo'] == "administrativo") {
            echo "<script>alert('Reservación creada de forma exitosa');";
        }
        echo "location.replace('../../' + '" . $_SESSION['tipo'] . "' + '/inicio.php')";
        echo "</script>";
    } else {
        echo "<pre>";
        echo "An Error occurred.";
        echo "</pre>";
    }
    $conn->close();
} else {
    header("Location: ../../index.php");
    exit();
}
?>