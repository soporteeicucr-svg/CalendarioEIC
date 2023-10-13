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
        }
    } else {
        $sql = "UPDATE `lista_horarios` set `espacio` = '{$espacio}', `sigla_curso` = '{$siglaCurso}', `nombre_curso` = '{$nombreCurso}', `solicitante` = '{$solicitante}', `correo` = '{$correo}', `hora_inicio` = '{$horaInicio}', `hora_fin` = '{$horaFin}' where `id` = '{$id}'";
    }
    $save = $conn->query($sql);
    if ($save) {
        ?>

        <!DOCTYPE html>
        <html>

        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            <title>Registro para uso de laboratorios para estudiantes</title>
            <link rel='stylesheet' href='../lib/alertify/css/alertify.css'>
            <link rel='stylesheet' href='../lib/alertify/css/themes/bootstrap.css'>
            <script src='../lib/alertify/alertify.min.js'></script>
        </head>

        <?php
        if ($_SESSION['tipo'] == "docente") {
            ?>

            <body>
                <script>
                    alertify.confirm('Mensaje', 'Solicitud de reserva enviada de manera exitosa, ¿desea cerrar la sesión?', function () { location.replace('../cerrar-sesion.php') }, function () { location.replace('../../' + '<?php echo $_SESSION['tipo'] ?>' + '/inicio.php') }).set({ transition: 'zoom', 'labels': { ok: 'Si', cancel: 'No' } }).autoOk(10);
                </script>
            </body>

            <?php
        } else if ($_SESSION['tipo'] == "administrativo") {
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
            sendMail($correo, 'Su reservación ha sido creada', $message);
            ?>

                <body>
                    <script>
                        alertify.confirm('Mensaje', 'Reservación creada de manera exitosa, ¿desea cerrar la sesión?', function () { location.replace('../cerrar-sesion.php') }, function () { location.replace('../../' + '<?php echo $_SESSION['tipo'] ?>' + '/inicio.php') }).set({ transition: 'zoom', 'labels': { ok: 'Si', cancel: 'No' } }).autoOk(10);
                    </script>
                </body>

            <?php
        }
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