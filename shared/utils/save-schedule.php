<?php
include 'db-conn.php';
include 'send-mail.php';

session_start();

if (isset($_SESSION['usuario'])) {
    if (isset($_POST['repetir']) && $_POST['repetir'] == 'si') {
        $dates = explode(',', $_POST['dates']);
        $approved = explode(',', $_POST['approved']);
        $pending = explode(',', $_POST['pending']);

        if (count($approved) > 0) {
            foreach ($approved as $id) {
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

                $select = "UPDATE `lista_horarios` SET estado = 'rechazado' WHERE id = " . $id;
                $result = mysqli_query($conn, $select);

                $message = "<p>Su reservación ha sido cancelada, a continuación se muestran los detalles de la misma.</p>
                    <p>ID de reservación: <b>$id</b></p>
                    <p>Espacio reservado: <b>$espacio</b></p>
                    <p>Sigla del curso: <b>$siglaCurso</b></p>
                    <p>Nombre del curso: <b>$nombreCurso</b></p>
                    <p>Nombre del solicitante: <b>$solicitante</b></p>
                    <p>Correo del solicitante: <b>$correo</b></p>
                    <p>Desde: <b>$horaInicio</b></p>
                    <p>Hasta: <b>$horaFin</b></p>
                    <p>Puede que otra reservación haya sido solicitada con anterioridad en el horario especificado, si aún requiere de una reservación, por favor solicítela nuevamente o póngase en contacto con la dirección de la escuela, gracias.</p>";
                sendMail($correo, "Su reservación ha sido cancelada", $message, '<h3>No contestar a este correo</h3>');
            }
        }

        if (count($pending) > 0) {
            foreach ($pending as $id) {
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

                $select = "UPDATE `lista_horarios` SET estado = 'rechazado' WHERE id = " . $id;
                $result = mysqli_query($conn, $select);

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
                sendMail($correo, "Su reservación ha sido rechazada", $message, '<h3>No contestar a este correo</h3>');
            }
        }

        $newIds = '';
        $fechas = '';

        $espacio = $_POST['espacio'];
        $siglaCurso = $_POST['sigla_curso'];
        $nombreCurso = $_POST['nombre_curso'];
        $solicitante = $_POST['solicitante'];
        $correo = $_POST['correo'];
        $dia = $_POST['numero_dia'];
        $rangoInicio = $_POST['rango_inicio'];
        $rangoFin = $_POST['rango_fin'];
        $horaInicio = $_POST['hora_inicio'];
        $horaFin = $_POST['hora_fin'];

        foreach ($dates as $date) {
            $fechas = $fechas . $date . '<br>';
            $inicio = $date . " " . $horaInicio;
            $fin = $date . " " . $horaFin;

            $sql = "INSERT INTO `lista_horarios` (`espacio`,`sigla_curso`,`nombre_curso`,`solicitante`,`correo`,`hora_inicio`,`hora_fin`, estado) VALUES ('$espacio','$siglaCurso','$nombreCurso','$solicitante','$correo','$inicio','$fin','aprobado')";
            $save = $conn->query($sql);

            if ($save) {
                $select = "SELECT * FROM `lista_horarios` WHERE `espacio` = '$espacio' AND `sigla_curso` = '$siglaCurso' AND `nombre_curso` = '$nombreCurso' AND `solicitante` = '$solicitante' AND `correo` = '$correo' AND `hora_inicio` = '$inicio' AND `hora_fin` = '$fin'";
                $result = mysqli_query($conn, $select);
                $schedule = mysqli_fetch_assoc($result);

                if ($newIds == '') {
                    $newIds = $schedule['id'];
                } else {
                    $newIds = $newIds . ', ' . $schedule['id'];
                }
            }
        }

        if ($dia == '1') {
            $dia = 'Lunes';
        } else if ($dia == '2') {
            $dia = 'Martes';
        } else if ($dia == '3') {
            $dia = 'Miércoles';
        } else if ($dia == '4') {
            $dia = 'Jueves';
        } else if ($dia == '5') {
            $dia = 'Viernes';
        } else if ($dia == '6') {
            $dia = 'Sábado';
        }

        $message = "<p>Las reservaciones han sido creadas satisfactoriamente, a continuación se muestran los detalles de las mismas.</p>
            <p>IDs de reservación: <b>$newIds</b></p>
            <p>Espacio reservado: <b>$espacio</b></p>
            <p>Sigla del curso: <b>$siglaCurso</b></p>
            <p>Nombre del curso / razón de reserva: <b>$nombreCurso</b></p>
            <p>Nombre del solicitante: <b>$solicitante</b></p>
            <p>Correo del solicitante: <b>$correo</b></p>
            <p>Días: <b>$dia</b></p>
            <p>Fechas:</p>
            <p><b>$fechas</b></p>
            <p>Desde: <b>$horaInicio</b></p>
            <p>Hasta: <b>$horaFin</b></p>";
        sendMail($correo, "Sus reservaciones han sido creadas", $message, '<h3>No contestar a este correo</h3>');

        $conn->close();

        $tipo = $_SESSION['tipo'];

        if ($_POST['continue'] == 'yes') {
            header("Location: ../../$tipo/inicio.php");
        } else {
            header("Location: ../cerrar-sesion.php");
        }
        exit();

    } else {
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
            echo "<script>alert('Error: No hay datos para guardar'); location.replace('../../index.php')</script>";
            $conn->close();
            exit;
        }
        extract($_POST);
        $sql = "";

        $tipo = $_SESSION['tipo'];

        if (empty($id)) {
            if ($tipo == "docente") {
                $sql = "INSERT INTO `lista_horarios` (`espacio`,`sigla_curso`,`nombre_curso`,`solicitante`,`correo`,`hora_inicio`,`hora_fin`, estado) VALUES ('$espacio','$siglaCurso','$nombreCurso','$solicitante','$correo','$horaInicio','$horaFin','pendiente')";
            } else if ($tipo == "administrativo") {
                $sql = "INSERT INTO `lista_horarios` (`espacio`,`sigla_curso`,`nombre_curso`,`solicitante`,`correo`,`hora_inicio`,`hora_fin`, estado) VALUES ('$espacio','$siglaCurso','$nombreCurso','$solicitante','$correo','$horaInicio','$horaFin','aprobado')";
            }
        } else {
            $sql = "UPDATE `lista_horarios` set `espacio` = '{$espacio}', `sigla_curso` = '{$siglaCurso}', `nombre_curso` = '{$nombreCurso}', `solicitante` = '{$solicitante}', `correo` = '{$correo}', `hora_inicio` = '{$horaInicio}', `hora_fin` = '{$horaFin}' where `id` = '{$id}'";
        }
        $save = $conn->query($sql);
        if ($save) {
            if ($tipo == "docente") {
                $select = "SELECT * FROM `lista_horarios` WHERE `espacio` = '$espacio' AND `sigla_curso` = '$siglaCurso' AND `nombre_curso` = '$nombreCurso' AND `solicitante` = '$solicitante' AND `correo` = '$correo' AND `hora_inicio` = '$horaInicio' AND `hora_fin` = '$horaFin'";
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

                $message = "<p>$solicitante ha solicitado reservar el espacio $espacio, a continuación se muestran los detalles de la solicitud.</p>
                <p>ID de reservación: <b>$id</b></p>
                <p>Espacio reservado: <b>$espacio</b></p>
                <p>Sigla del curso: <b>$siglaCurso</b></p>
                <p>Nombre del curso: <b>$nombreCurso</b></p>
                <p>Nombre del solicitante: <b>$solicitante</b></p>
                <p>Correo del solicitante: <b>$correo</b></p>
                <p>Desde: <b>$horaInicio</b></p>
                <p>Hasta: <b>$horaFin</b></p>";

                $approvalRequired = false;
                if ($espacio == 'Aula PEC' || $espacio == 'Sala Interna') {
                    $approvalRequired = true;
                }

                $header = '<h3>Un docente ha solicitado una reserva de espacio';
                if ($approvalRequired) {
                    $header = $header . ' que requiere visto bueno de la dirección</h3>';
                } else {
                    $header = $header . '</h3>';
                }
                $subject = $header;

                notifyMail($subject, $message, $header, $approvalRequired);
                if ($_POST['continue'] == 'yes') {
                    header("Location: ../../$tipo/inicio.php");
                } else {
                    header("Location: ../cerrar-sesion.php");
                }
                exit();
            } else if ($tipo == "administrativo") {
                $select = "SELECT * FROM `lista_horarios` WHERE `espacio` = '$espacio' AND `sigla_curso` = '$siglaCurso' AND `nombre_curso` = '$nombreCurso' AND `solicitante` = '$solicitante' AND `correo` = '$correo' AND `hora_inicio` = '$horaInicio' AND `hora_fin` = '$horaFin'";
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
                sendMail($correo, 'Su reservación ha sido creada', $message, '<h3>No contestar a este correo</h3>');
                if ($_POST['continue'] == 'yes') {
                    header("Location: ../../$tipo/inicio.php");
                } else {
                    header("Location: ../cerrar-sesion.php");
                }
                exit();
            }
        } else {
            echo "<pre>";
            echo "An Error occurred.";
            echo "</pre>";
        }
        $conn->close();
    }
} else {
    header("Location: ../../index.php");
    exit();
}
?>