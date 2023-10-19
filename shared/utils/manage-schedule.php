<?php
include 'db-conn.php';
include 'send-mail.php';

session_start();

if (isset($_SESSION['id']) && isset($_SESSION['usuario'])) {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        if ($action == 'aprobar') {
            $id = $_POST['id'];

            $select = "SELECT * FROM `lista_horarios` WHERE id = '$id'";
            $result = mysqli_query($conn, $select);
            $schedule = mysqli_fetch_assoc($result);

            $result = $conn->query("SELECT * FROM `lista_horarios` WHERE estado = 'pendiente' OR estado = 'aprobado'");
            foreach ($result->fetch_all(MYSQLI_ASSOC) as $row) {
                if ($schedule['hora_inicio'] < $row['hora_fin'] && $schedule['hora_fin'] > $row['hora_inicio'] && $schedule['espacio'] == $row['espacio'] && $schedule['id'] != $row['id']) {
                    $select = "UPDATE `lista_horarios` SET estado = 'rechazado' WHERE id = " . $row['id'];
                    $result = mysqli_query($conn, $select);

                    $id = $row['id'];
                    $espacio = $row['espacio'];
                    $siglaCurso = $row['sigla_curso'];
                    $nombreCurso = $row['nombre_curso'];
                    $solicitante = $row['solicitante'];
                    $correo = $row['correo'];
                    $horaInicio = $row['hora_inicio'];
                    $horaFin = $row['hora_fin'];

                    if ($schedule['estado'] == 'pendiente') {
                        $estado = 'rechazada';
                    } else {
                        $estado = 'cancelada';
                    }

                    $message = "<p>Su reservación ha sido $estado, a continuación se muestran los detalles de la misma.</p>
                    <p>ID de reservación: <b>$id</b></p>
                    <p>Espacio reservado: <b>$espacio</b></p>
                    <p>Sigla del curso: <b>$siglaCurso</b></p>
                    <p>Nombre del curso: <b>$nombreCurso</b></p>
                    <p>Nombre del solicitante: <b>$solicitante</b></p>
                    <p>Correo del solicitante: <b>$correo</b></p>
                    <p>Desde: <b>$horaInicio</b></p>
                    <p>Hasta: <b>$horaFin</b></p>
                    <p>Puede que otra reservación haya sido solicitada con anterioridad en el horario especificado, si aún requiere de una reservación, por favor solicítela nuevamente o póngase en contacto con la dirección de la escuela, gracias.</p>";
                    sendMail($correo, "Su reservación ha sido $estado", $message);
                }
            }

            $id = $schedule['id'];
            $espacio = $schedule['espacio'];
            $siglaCurso = $schedule['sigla_curso'];
            $nombreCurso = $schedule['nombre_curso'];
            $solicitante = $schedule['solicitante'];
            $correo = $schedule['correo'];
            $horaInicio = $schedule['hora_inicio'];
            $horaFin = $schedule['hora_fin'];

            $select = "UPDATE `lista_horarios` SET estado = 'aprobado' WHERE id = '$id'";
            $result = mysqli_query($conn, $select);

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
            sendMail($correo, "Su reservación ha sido aprobada", $message);
            $conn->close();
            header("Location: ../../administrativo/administrar-solicitudes.php");
            exit();
        } else if ($action == 'rechazar' || $action == 'cancelar') {
            $id = $_POST['id'];

            $select = "SELECT * FROM `lista_horarios` WHERE id = '$id'";
            $result = mysqli_query($conn, $select);
            $schedule = mysqli_fetch_assoc($result);

            $id = $schedule['id'];
            $espacio = $schedule['espacio'];
            $siglaCurso = $schedule['sigla_curso'];
            $nombreCurso = $schedule['nombre_curso'];
            $solicitante = $schedule['solicitante'];
            $correo = $schedule['correo'];
            $horaInicio = $schedule['hora_inicio'];
            $horaFin = $schedule['hora_fin'];

            $select = "UPDATE `lista_horarios` SET estado = 'rechazado' WHERE id = '$id'";
            $result = mysqli_query($conn, $select);

            if ($schedule['estado'] == 'pendiente') {
                $estado = 'rechazada';
            } else {
                $estado = 'cancelada';
            }

            $message = "<p>Su reservación ha sido $estado, a continuación se muestran los detalles de la misma.</p>
            <p>ID de reservación: <b>$id</b></p>
            <p>Espacio reservado: <b>$espacio</b></p>
            <p>Sigla del curso: <b>$siglaCurso</b></p>
            <p>Nombre del curso: <b>$nombreCurso</b></p>
            <p>Nombre del solicitante: <b>$solicitante</b></p>
            <p>Correo del solicitante: <b>$correo</b></p>
            <p>Desde: <b>$horaInicio</b></p>
            <p>Hasta: <b>$horaFin</b></p>
            <p>Puede que otra reservación haya sido creada con anterioridad en el horario solicitado, si aún requiere de una reservación, por favor solicítela nuevamente o póngase en contacto con la dirección de la escuela, gracias.</p>";
            sendMail($correo, "Su reservación ha sido $estado", $message);
            $conn->close();
            header("Location: ../../administrativo/administrar-solicitudes.php");
            exit();
        }
    }
} else {
    header("Location: ../../index.php");
    exit();
}
?>