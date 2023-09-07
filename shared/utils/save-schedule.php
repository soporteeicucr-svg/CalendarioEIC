<?php
include 'db-conn.php';

session_start();

$id = $_POST['id'];
$espacio = $_POST['espacio'];
$siglaCurso = $_POST['sigla_curso'];
$nombreCurso = $_POST['nombre_curso'];
$solicitante = $_POST['solicitante'];
// $repeticion = $_POST['repeticion'];
$horaInicio = $_POST['hora_inicio'];
$horaFin = $_POST['hora_fin'];

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "<script>alert('Error: No hay datos para guardar'); location.replace('./')</script>";
    $conn->close();
    exit;
}
extract($_POST);
$allday = isset($allday);

if (empty($id)) {
    if ($_SESSION['tipo'] == "doncente") {
    $sql = "INSERT INTO `lista_horarios` (`espacio`,`sigla_curso`,`nombre_curso`,`solicitante`,`hora_inicio`,`hora_fin`, estado) VALUES ('$espacio','$siglaCurso','$nombreCurso','$solicitante','$horaInicio','$horaFin','pendiente')";
    } else if ($_SESSION['tipo'] == "administrativo") {
        $sql = "INSERT INTO `lista_horarios` (`espacio`,`sigla_curso`,`nombre_curso`,`solicitante`,`hora_inicio`,`hora_fin`, estado) VALUES ('$espacio','$siglaCurso','$nombreCurso','$solicitante','$horaInicio','$horaFin','aprobado')";
    }
} else {
    $sql = "UPDATE `lista_horarios` set `espacio` = '{$espacio}', `sigla_curso` = '{$siglaCurso}',`nombre_curso` = '{$nombreCurso}',`solicitante` = '{$solicitante}', `hora_inicio` = '{$horaInicio}', `hora_fin` = '{$horaFin}' where `id` = '{$id}'";
}
$save = $conn->query($sql);
if ($save) {
    echo "<script>alert('Solicitud de reserva enviada de forma exitosa');
    location.replace('../../' + '" . $_SESSION['tipo'] . "' + '/solicitud-reserva.php')
    </script>";
} else {
    echo "<pre>";
    echo "An Error occurred.";
    echo "</pre>";
}
$conn->close();
?>