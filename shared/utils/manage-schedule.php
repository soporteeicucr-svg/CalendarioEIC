<?php
include 'db-conn.php';
include 'send-mail.php';

session_start();

if (isset($_SESSION['id']) && isset($_SESSION['usuario'])) {
    if (isset($_POST['aprobar'])) {
        $id = $_POST['id'];

        $select = "SELECT * FROM `lista_horarios` WHERE id = '$id'";
        $result = mysqli_query($conn, $select);
        $schedule = mysqli_fetch_assoc($result);

        $result = $conn->query("SELECT * FROM `lista_horarios` WHERE estado = 'pendiente' OR estado = 'aprobado'");
        foreach ($result->fetch_all(MYSQLI_ASSOC) as $row) {
            if ($schedule['hora_inicio'] < $row['hora_fin'] && $schedule['hora_fin'] > $row['hora_inicio'] && $schedule['espacio'] == $row['espacio']) {
                $select = "UPDATE `lista_horarios` SET estado = 'rechazado' WHERE id = " . $row['id'];
                $result = mysqli_query($conn, $select);

                $id = $schedule['id'];
                $espacio = $schedule['espacio'];
                $siglaCurso = $schedule['sigla_curso'];
                $nombreCurso = $schedule['nombre_curso'];
                $solicitante = $schedule['solicitante'];
                $correo = $schedule['correo'];
                $horaInicio = $schedule['hora_inicio'];
                $horaFin = $schedule['hora_fin'];

                $message = "<p>Su reservación ha sido rechazada, a continuación se muestran los detalles de la misma.</p>
                <p>ID de reservación: <b>$id</b></p>
                <p>Espacio reservado: <b>$espacio</b></p>
                <p>Sigla del curso: <b>$siglaCurso</b></p>
                <p>Nombre del curso: <b>$nombreCurso</b></p>
                <p>Nombre del solicitante: <b>$solicitante</b></p>
                <p>Correo del solicitante: <b>$correo</b></p>
                <p>Desde: <b>$horaInicio</b></p>
                <p>Hasta: <b>$horaFin</b></p>
                <p>Puede que otra reservación haya sido solicitada con anterioridad en el horario especificado, si aún requiere de una reservación, por favor solicítela nuevamente o póngase en contacto con la dirección de la escuela, gracias.</p>";
                sendMail($correo, 'Su reservación a sido rechazada', $message);
            }
        }

        $select = "UPDATE `lista_horarios` SET estado = 'aprobado' WHERE id = '$id'";
        $result = mysqli_query($conn, $select);

        $id = $schedule['id'];
        $espacio = $schedule['espacio'];
        $siglaCurso = $schedule['sigla_curso'];
        $nombreCurso = $schedule['nombre_curso'];
        $solicitante = $schedule['solicitante'];
        $correo = $schedule['correo'];
        $horaInicio = $schedule['hora_inicio'];
        $horaFin = $schedule['hora_fin'];

        $message = "<p>Su reservación ha sido aprobada, a continuación se muestran los detalles de la misma.</p>
        <p>ID de reservación: <b>$id</b></p>
        <p>Espacio reservado: <b>$espacio</b></p>
        <p>Sigla del curso: <b>$siglaCurso</b></p>
        <p>Nombre del curso: <b>$nombreCurso</b></p>
        <p>Nombre del solicitante: <b>$solicitante</b></p>
        <p>Correo del solicitante: <b>$correo</b></p>
        <p>Desde: <b>$horaInicio</b></p>
        <p>Hasta: <b>$horaFin</b></p>
        <p>Recuerde utilizar el sistema de registro y retiro de cartuchera antes de dirigirse a su reservación, gracias.</p>";
        sendMail($correo, 'Su reservación a sido aprobada', $message);

        echo '<script type = "text/javascript">';
        echo 'alert("La reservación ha sido aprobada");';
        echo 'window.location.href = "../../administrativo/administrar-solicitudes.php";';
        echo '</script>';
    }
    
    if (isset($_POST['rechazar'])) {
        $id = $_POST['id'];

        $select = "SELECT * FROM `lista_horarios` WHERE id = '$id'";
        $result = mysqli_query($conn, $select);
        $schedule = mysqli_fetch_assoc($result);

        $select = "UPDATE `lista_horarios` SET estado = 'rechazado' WHERE id = '$id'";
        $result = mysqli_query($conn, $select);

        $id = $schedule['id'];
        $espacio = $schedule['espacio'];
        $siglaCurso = $schedule['sigla_curso'];
        $nombreCurso = $schedule['nombre_curso'];
        $solicitante = $schedule['solicitante'];
        $correo = $schedule['correo'];
        $horaInicio = $schedule['hora_inicio'];
        $horaFin = $schedule['hora_fin'];

        $message = "<p>Su reservación ha sido rechazada, a continuación se muestran los detalles de la misma.</p>
        <p>ID de reservación: <b>$id</b></p>
        <p>Espacio reservado: <b>$espacio</b></p>
        <p>Sigla del curso: <b>$siglaCurso</b></p>
        <p>Nombre del curso: <b>$nombreCurso</b></p>
        <p>Nombre del solicitante: <b>$solicitante</b></p>
        <p>Correo del solicitante: <b>$correo</b></p>
        <p>Desde: <b>$horaInicio</b></p>
        <p>Hasta: <b>$horaFin</b></p>
        <p>Puede que otra reservación haya sido creada con anterioridad en el horario solicitado, si aún requiere de una reservación, por favor solicítela nuevamente o póngase en contacto con la dirección de la escuela, gracias.</p>";
        sendMail($correo, 'Su reservación a sido rechazada', $message);

        echo '<script type = "text/javascript">';
        echo 'alert("La reservación ha sido rechazada");';
        echo 'window.location.href = "../../administrativo/administrar-solicitudes.php";';
        echo '</script>';
    }
    $conn->close();
} else {
    header("Location: ../../index.php");
    exit();
}
?>