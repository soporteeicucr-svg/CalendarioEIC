<?php
include '../shared/utils/db-conn.php';

session_start();

$schedules = [];

if (isset($_SESSION['id']) && isset($_SESSION['usuario'])) {
    ?>

    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Administración de solicitudes de reserva</title>
        <link rel="stylesheet" type="text/css" href="../shared/lib/bootstrap/bootstrap.min.css">
        <link rel='stylesheet' type="text/css" href='../shared/lib/alertify/css/alertify.css'>
        <link rel='stylesheet' type="text/css" href='../shared/lib/alertify/css/themes/bootstrap.css'>
        <link rel="stylesheet" type="text/css" href="../shared/css/reserva.css">
        <link rel="stylesheet" type="text/css" href="../shared/css/tabs.css">
        <script src='../shared/lib/alertify/alertify.min.js'></script>
        <script src="../shared/lib/jquery/jquery-3.6.0.min.js"></script>
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
                                        $schedules[$row['id']] = $row;
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
                                            <td class="d-flex align-items-center justify-content-center">
                                                <form action="../shared/utils/manage-schedule.php" method="POST">
                                                    <input type="hidden" name="id" id="<?php echo $row['id']; ?>"
                                                        value="<?php echo $row['id']; ?>" />
                                                    <input type="hidden" name="action" value="aprobar" />
                                                    <input type="button" value="Aprobar" onclick="sendForm(this.form);" />
                                                </form>
                                                <form action="../shared/utils/manage-schedule.php" method="POST">
                                                    <input type="hidden" name="id" id="<?php echo $row['id']; ?>"
                                                        value="<?php echo $row['id']; ?>" />
                                                    <input type="hidden" name="action" value="rechazar" />
                                                    <input type="button" value="Rechazar" onclick="sendForm(this.form);" />
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
                                        $schedules[$row['id']] = $row;
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
                                            <td class="d-flex align-items-center justify-content-center">
                                                <form action="../shared/utils/manage-schedule.php" method="POST">
                                                    <input type="hidden" name="id" id="<?php echo $row['id']; ?>"
                                                        value="<?php echo $row['id']; ?>" />
                                                    <input type="hidden" name="action" value="cancelar" />
                                                    <input type="button" value="Cancelar" onclick="sendForm(this.form);" />
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
                                        $schedules[$row['id']] = $row;
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
                                            <td class="d-flex align-items-center justify-content-center">
                                                <form action="../shared/utils/manage-schedule.php" method="POST">
                                                    <input type="hidden" name="id" id="<?php echo $row['id']; ?>"
                                                        value="<?php echo $row['id']; ?>" />
                                                    <input type="hidden" name="action" value="aprobar" />
                                                    <input type="button" value="Aprobar" onclick="sendForm(this.form);" />
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
        let activeTab = "";

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

            activeTab = tabName;
        }

        var scheds = $.parseJSON('<?= json_encode($schedules) ?>');

        function sendForm(form) {
            var schedule = scheds[form["id"].value];
            var overlapping = [];

            for (i in scheds) {
                if (schedule['hora_inicio'] < scheds[i]['hora_fin'] && schedule['hora_fin'] > scheds[i]['hora_inicio'] && schedule['espacio'] == scheds[i]['espacio'] && schedule['id'] != scheds[i]['id'] && scheds[i]['estado'] != "rechazado") {
                    overlapping.push(scheds[i]);
                }
            };

            let action = "";
            let result = "";
            if (form["action"].value == "aprobar") {
                action = "aprobará";
                result = "aprobada"
            } else if (form["action"].value == "cancelar") {
                action = "cancelará";
                result = "cancelada"
            } else if (form["action"].value == "rechazar") {
                action = "rechazará";
                result = "rechazada"
            }

            if (overlapping.length == 0) {

                alertify.confirm('Mensaje',
                    'Esto ' + action + ' la reservación seleccionada, ¿desea continuar?',
                    function () {
                        /*
                        alertify.alert('Mensaje',
                            'La reservación ha sido ' + result,
                            function () { form.submit() }
                        ).set({ transition: 'zoom', closable: false, 'label': 'Aceptar' });
                        */
                        var style = document.createElement("style");
                        style.textContent = ".ajs-cancel { display: none; }";
                        document.head.appendChild(style);
                        setTimeout(function () {
                            alertify.confirm('Mensaje',
                            'La reservación ha sido ' + result,
                            function () { form.submit() },
                            function () { }
                        ).set({ transition: "zoom", closable: false, labels: { ok: 'Aceptar', cancel: 'Cancelar' } }).autoOk(5);
                        }, 100);
                    },
                    function () { }
                ).set({ transition: "zoom", closable: false, labels: { ok: 'Si', cancel: 'No' } });

            } else if (overlapping.length == 1) {

                if (activeTab == "Pendientes") {
                    result = "rechazada"
                } else if (activeTab == "Rechazadas") {
                    result = "cancelada"
                }
                alertify.confirm('Mensaje',
                    'La reservación con ID ' + overlapping[0]['id'] + ' entre las horas ' + overlapping[0]['hora_inicio'] + ' y ' + overlapping[0]['hora_fin'] + ' será ' + result + ', ¿desea continuar?',
                    function () {
                        /*
                        alertify.alert('Mensaje',
                            'La reservación ha sido aprobada',
                            function () { form.submit() }
                        ).set({ transition: 'zoom', closable: false, 'label': 'Aceptar' });
                        */
                        var style = document.createElement("style");
                        style.textContent = ".ajs-cancel { display: none; }";
                        document.head.appendChild(style);
                        setTimeout(function () {
                            alertify.confirm('Mensaje',
                            'La reservación ha sido aprobada',
                            function () { form.submit() },
                            function () { }
                        ).set({ transition: "zoom", closable: false, labels: { ok: 'Aceptar', cancel: 'Cancelar' } }).autoOk(5);
                        }, 100);
                    },
                    function () { }
                ).set({ transition: "zoom", closable: false, "labels": { ok: "Aceptar", cancel: "Cancelar" } });

            } else {

                if (activeTab == "Pendientes") {
                    result = "rechazadas"
                } else if (activeTab == "Rechazadas") {
                    result = "canceladas"
                }
                var overlappingMessage = "";
                for (i in overlapping) {
                    overlappingMessage = overlappingMessage + 'ID: ' + overlapping[i]['id'] + ', Inicio: ' + overlapping[i]['hora_inicio'] + ', Finalización: ' + overlapping[i]['hora_fin'] + '<br>';
                };
                alertify.confirm('Mensaje',
                    'Las reservaciones:<br>' + overlappingMessage + 'serán ' + result + ', ¿desea continuar?',
                    function () {
                        /*
                        alertify.alert('Mensaje',
                            'La reservación ha sido aprobada',
                            function () { form.submit() }
                        ).set({ transition: 'zoom', closable: false, 'label': 'Aceptar' });
                        */
                        var style = document.createElement("style");
                        style.textContent = ".ajs-cancel { display: none; }";
                        document.head.appendChild(style);
                        setTimeout(function () {
                            alertify.confirm('Mensaje',
                            'La reservación ha sido aprobada',
                            function () { form.submit() },
                            function () { }
                        ).set({ transition: "zoom", closable: false, labels: { ok: 'Aceptar', cancel: 'Cancelar' } }).autoOk(5);
                        }, 100);
                    },
                    function () { }
                ).set({ transition: "zoom", closable: false, "labels": { ok: "Aceptar", cancel: "Cancelar" } });
            }
        }

        // Get the element with id="defaultOpen" and click on it
        document.getElementById("defaultOpen").click();
    </script>

    </html>

    <?php
} else {
    header("Location: index.php");
    exit();
}
?>