<?php
include '../shared/utils/db-conn.php';
include '../shared/utils/console-log.php';

session_start();

if (isset($_SESSION['id']) && isset($_SESSION['usuario'])) {
    ?>

    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Administración de solicitudes de reserva</title>
        <link rel="stylesheet" type="text/css" href="../shared/lib/bootstrap/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="../shared/css/reserva.css">
    </head>

    <body class="bg-light">
        <nav class="navbar navbar-expand-lg navbar-dark bg-gradient bg-primary" id="topNavBar">
            <div class="container">
                <a class="navbar-brand">
                    Escuela de Ingeniería Civil
                </a>
                <div>
                    <button class="buttonHome" onclick="location.href='inicio.php'">Inicio</button>
                </div>
            </div>
        </nav>
        <div class="container py-3" id="page-container">
            <div class="text-center">
                <h1>Solicitudes de reserva</h1>
            </div>
            <br>
            <table class="table">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Sala/Aula</th>
                        <th>Sigla</th>
                        <th>Curso</th>
                        <th>Solicitante</th>
                        <th>Inicio</th>
                        <th>Finalización</th>
                        <th>Acción</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    $query = "SELECT * FROM `lista_horarios` WHERE estado = 'pendiente'";
                    $result = mysqli_query($conn, $query);
                    while ($row = mysqli_fetch_array($result)) {
                        ?>
                        <tr>
                            <td>
                                <?php echo $row['id']; ?>
                            </td>
                            <td>
                                <?php echo $row['espacio']; ?>
                            </td>
                            <td>
                                <?php echo $row['sigla_curso']; ?>
                            </td>
                            <td>
                                <?php echo $row['nombre_curso']; ?>
                            </td>
                            <td>
                                <?php echo $row['solicitante']; ?>
                            </td>
                            <td>
                                <?php echo $row['hora_inicio']; ?>
                            </td>
                            <td>
                                <?php echo $row['hora_fin']; ?>
                            </td>
                            <td>
                                <form action="administrar-solicitudes.php" method="POST" style="">
                                    <input type="hidden" name="id" id="<?php echo $row['id']; ?>"
                                        value="<?php echo $row['id']; ?>" />
                                    <input type="submit" name="aprobar" value="Aprobar" />
                                    <input type="submit" name="rechazar" value="Rechazar" />
                                </form>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </body>

    <?php
    if (isset($_POST['aprobar'])) {
        $id = $_POST['id'];

        $select = "SELECT * FROM `lista_horarios` WHERE id = '$id'";
        $result = mysqli_query($conn, $select);
        $schedule = mysqli_fetch_assoc($result);

        $select = "UPDATE `lista_horarios` SET estado = 'aprobado' WHERE id = '$id'";
        $result = mysqli_query($conn, $select);

        $schedules = $conn->query("SELECT * FROM `lista_horarios` WHERE estado = 'pendiente'");
        foreach ($schedules->fetch_all(MYSQLI_ASSOC) as $row) {
            if ($schedule['hora_inicio'] < $row['hora_fin'] && $schedule['hora_fin'] > $row['hora_inicio'] && $schedule['espacio'] == $row['espacio']) {
                $select = "UPDATE `lista_horarios` SET estado = 'rechazado' WHERE id = " . $row['id'];
                $result = mysqli_query($conn, $select);
            }
        }

        echo '<script type = "text/javascript">';
        echo 'alert("La reservación ha sido aprobada");';
        echo 'window.location.href = "administrar-solicitudes.php";';
        echo '</script>';
        die(); //Stops PHP from further execution
    }

    if (isset($_POST['rechazar'])) {
        $id = $_POST['id'];

        $select = "UPDATE `lista_horarios` SET estado = 'rechazado' WHERE id = '$id'";
        $result = mysqli_query($conn, $select);

        echo '<script type = "text/javascript">';
        echo 'alert("La reservación ha sido rechazada");';
        echo 'window.location.href = "administrar-solicitudes.php";';
        echo '</script>';
        die(); //Stops PHP from further execution
    }
    ?>

    </html>

    <?php
} else {
    header("Location: index.php");
    exit();
}
?>