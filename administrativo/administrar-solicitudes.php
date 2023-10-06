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
        <link rel="stylesheet" type="text/css" href="../shared/css/tabs.css">
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
            <div class="row">
                <div class="col-md-14">

                    <div class="text-center">
                        <h1>Solicitudes de reserva</h1>
                    </div>

                    <!-- Tab links -->
                    <div class="tab d-flex justify-content-center">
                        <button class="tablinks" onclick="openTab(event, 'Pendientes')" id="defaultOpen">Pendientes</button>
                        <button class="tablinks" onclick="openTab(event, 'Aprobadas')">Aprobadas</button>
                        <button class="tablinks" onclick="openTab(event, 'Rechazadas')">Rechazadas</button>
                    </div>

                    <!-- Tab content -->
                    <div id="Pendientes" class="tabcontent">
                        <div id="tab Pendientes">
                            <table class="table" style="margin-bottom: 0;">
                                <thead>
                                    <tr>
                                        <th>ID</th>
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
                                                <form action="administrar-solicitudes.php" method="POST">
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
                    </div>

                    <div id="Aprobadas" class="tabcontent">
                        <div id="tab Aprobadas">
                            <table class="table" style="margin-bottom: 0;">
                                <thead>
                                    <tr>
                                        <th>ID</th>
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
                                    $query = "SELECT * FROM `lista_horarios` WHERE estado = 'aprobado'";
                                    $result = $conn->query($query);
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
                                                <form action="administrar-solicitudes.php" method="POST">
                                                    <input type="hidden" name="id" id="<?php echo $row['id']; ?>"
                                                        value="<?php echo $row['id']; ?>" />
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
                    </div>

                    <div id="Rechazadas" class="tabcontent">
                        <div id="tab Rechazadas">
                            <table class="table" style="margin-bottom: 0;">
                                <thead>
                                    <tr>
                                        <th>ID</th>
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
                                    $query = "SELECT * FROM `lista_horarios` WHERE estado = 'rechazado'";
                                    $result = $conn->query($query);
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
                                                <form action="administrar-solicitudes.php" method="POST">
                                                    <input type="hidden" name="id" id="<?php echo $row['id']; ?>"
                                                        value="<?php echo $row['id']; ?>" />
                                                    <input type="submit" name="aprobar" value="Aprobar" />
                                                </form>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </body>
    <script>
        function openTab(evt, tabName) {
            // Declare all variables
            var i, tabcontent, tablinks;

            // Get all elements with class="tabcontent" and hide them
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }

            // Get all elements with class="tablinks" and remove the class "active"
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }

            // Show the current tab, and add an "active" class to the button that opened the tab
            document.getElementById(tabName).style.display = "block";
            evt.currentTarget.className += " active";
        }

        // Get the element with id="defaultOpen" and click on it
        document.getElementById("defaultOpen").click();
    </script>

    <?php
    if (isset($_POST['aprobar'])) {
        $id = $_POST['id'];

        $select = "SELECT * FROM `lista_horarios` WHERE id = '$id'";
        $result = mysqli_query($conn, $select);
        $schedule = mysqli_fetch_assoc($result);

        consoleLog($schedule);

        $schedules = $conn->query("SELECT * FROM `lista_horarios` WHERE estado = 'pendiente' OR estado = 'aprobado'");
        foreach ($schedules->fetch_all(MYSQLI_ASSOC) as $row) {
            if ($schedule['hora_inicio'] < $row['hora_fin'] && $schedule['hora_fin'] > $row['hora_inicio'] && $schedule['espacio'] == $row['espacio']) {
                $select = "UPDATE `lista_horarios` SET estado = 'rechazado' WHERE id = " . $row['id'];
                $result = mysqli_query($conn, $select);
            }
        }

        $select = "UPDATE `lista_horarios` SET estado = 'aprobado' WHERE id = '$id'";
        $result = mysqli_query($conn, $select);

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