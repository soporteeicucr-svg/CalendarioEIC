<?php
include '../shared/utils/db-conn.php';
include '../shared/utils/translate-date.php';
include '../shared/utils/send-mail.php';

session_start();

if (isset($_SESSION['usuario'])) {
    if (isset($_COOKIE['id'])) {
        $id = $_COOKIE['id'];
        unset($_COOKIE['id']);
        echo '<script>document.cookie = "id=' . $id . '; expires=Thu, 01 Jan 1970 00:00:00 UTC";</script>';

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
        sendMail($correo, 'Su reservación ha sido cancelada', $message);

        echo '<script type = "text/javascript">';
        echo 'window.location.href = "calendario-reserva.php";';
        echo '</script>';
        die(); //Stops PHP from further execution
    }
    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Calendario de reservas para administradores</title>
        <link rel='stylesheet' type="text/css" href='../shared/lib/alertify/css/alertify.css'>
        <link rel='stylesheet' type="text/css" href='../shared/lib/alertify/css/themes/bootstrap.css'>
        <link rel="stylesheet" type="text/css" href="../shared/lib/bootstrap/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="../shared/lib/fontawesome/css/all.css">
        <link rel="stylesheet" type="text/css" href="../shared/lib/fullcalendar/lib/main.min.css">
        <link rel="stylesheet" type="text/css" href="../shared/css/reserva.css">
        <link rel="stylesheet" type="text/css" href="../shared/css/tabs.css">
        <script src='../shared/lib/alertify/alertify.min.js'></script>
        <script src="../shared/lib/bootstrap/bootstrap.min.js"></script>
        <script src='../shared/lib/fullcalendar/index.global.min.js'></script>
        <script src="../shared/lib/fullcalendar/lib/main.min.js"></script>
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

                    <!-- Tab links -->
                    <div class="tab d-flex justify-content-center">
                        <button class="tablinks" onclick="openCalendar(event, 'Laboratorio 1')" id="defaultOpen">Laboratorio
                            1</button>
                        <button class="tablinks" onclick="openCalendar(event, 'Laboratorio 2')">Laboratorio 2</button>
                        <button class="tablinks" onclick="openCalendar(event, 'Laboratorio 3')">Laboratorio 3</button>
                        <button class="tablinks" onclick="openCalendar(event, 'Aula PEC')">Aula PEC (visto bueno)</button>
                        <button class="tablinks" onclick="openCalendar(event, 'Sala Interna')">Sala Interna (visto
                            bueno)</button>
                        <button class="tablinks" onclick="openCalendar(event, 'Sala Externa')">Sala Externa</button>
                    </div>

                    <!-- Tab content -->
                    <div id="Laboratorio 1" class="tabcontent">
                        <div id="calendar Laboratorio 1"></div>
                    </div>

                    <div id="Laboratorio 2" class="tabcontent">
                        <div id="calendar Laboratorio 2"></div>
                    </div>

                    <div id="Laboratorio 3" class="tabcontent">
                        <div id="calendar Laboratorio 3"></div>
                    </div>

                    <div id="Aula PEC" class="tabcontent">
                        <div id="calendar Aula PEC"></div>
                    </div>

                    <div id="Sala Interna" class="tabcontent">
                        <div id="calendar Sala Interna"></div>
                    </div>

                    <div id="Sala Externa" class="tabcontent">
                        <div id="calendar Sala Externa"></div>
                    </div>

                </div>
            </div>
        </div>
        <div class="modal fade" tabindex="-1" data-bs-backdrop="static" id="event-details-modal">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-3">
                    <div class="modal-header rounded-0">
                        <h5 class="modal-title">Detalles de reservación</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body rounded-0">
                        <div class="container-fluid">
                            <dl>
                                <dt class="text-muted">Información</dt>
                                <dd id="title" class="fw-bold fs-4"></dd>
                                <dt class="text-muted" id="details"></dt>
                                <dd id="description" class=""></dd>
                                <dt class="text-muted" id="reservedBy"></dt>
                                <dd id="name" class=""></dd>
                                <dt class="text-muted">Inicio</dt>
                                <dd id="start" class=""></dd>
                                <dt class="text-muted">Finalización</dt>
                                <dd id="end" class=""></dd>
                            </dl>
                        </div>
                    </div>
                    <div class="modal-footer rounded-0 justify-content-between">
                        <div class="text-start">
                            <dd id="reserveID" class="mb-0"></dd>
                        </div>
                        <div class="text-end">
                            <button type="button" class="btn btn-secondary btn-sm rounded-3"
                                onclick="deleteEvent(document.getElementById('reserveID').innerHTML.split(': ')[1])"
                                data-bs-dismiss="modal">Borrar</button>
                            <button type="button" class="btn btn-secondary btn-sm rounded-3"
                                data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php
        $schedules = $conn->query("SELECT * FROM `lista_horarios` WHERE estado = 'aprobado'");
        $sched_res = [];
        foreach ($schedules->fetch_all(MYSQLI_ASSOC) as $row) {
            // Fechas en ingles
            // $row['sdate'] = date("F d, Y h:i A", strtotime($row['hora_inicio']));
            // $row['edate'] = date("F d, Y h:i A", strtotime($row['hora_fin']));
            $row['sdate'] = translateDate($row['hora_inicio']);
            $row['edate'] = translateDate($row['hora_fin']);

            $sched_res[$row['id']] = $row;
        }
        ?>
    </body>
    <script>
        var scheds = $.parseJSON('<?= json_encode($sched_res) ?>');
        var calendarNames = ["Laboratorio 1", "Laboratorio 2", "Laboratorio 3", "Aula PEC", "Sala Interna", "Sala Externa"];
        var calendars = [];
        var currentCalendar = "Laboratorio 1";

        for (i in calendarNames) {
            var events = [];
            if (!!scheds) {
                Object.keys(scheds).map((k) => {
                    if (scheds[k].espacio == calendarNames[i]) {
                        var row = scheds[k];
                        if (row.sigla_curso != "NA") {
                            events.push({
                                id: row.id,
                                title: row.sigla_curso,
                                start: row.hora_inicio,
                                end: row.hora_fin,
                            });
                        } else {
                            events.push({
                                id: row.id,
                                title: row.nombre_curso,
                                start: row.hora_inicio,
                                end: row.hora_fin,
                            });
                        }
                    }
                });
            }

            var calendar = new FullCalendar.Calendar(document.getElementById("calendar " + calendarNames[i]), {
                headerToolbar: {
                    left: "prev,next today",
                    right: "timeGridWeek,dayGridMonth",
                    center: "title"
                },
                initialView: 'timeGridWeek',
                slotMinTime: "07:00:00",
                slotMaxTime: "22:00:00",
                allDaySlot: false,
                expandRows: true,
                selectable: true,
                hiddenDays: [0],
                locale: "es",
                themeSystem: "bootstrap",
                buttonText: {
                    today: 'Hoy', month: 'Mes', week: 'Semana', list: 'Lista'
                },
                events: events,
                eventClick: function (info) {
                    var _details = $("#event-details-modal");
                    var id = info.event.id;
                    if (!!scheds[id]) {
                        _details.find("#title").text("Reserva de " + scheds[id].espacio);
                        if (scheds[id].sigla_curso != "NA") {
                            document.getElementById('details').innerHTML = "Sigla y nombre del curso";
                            document.getElementById('reservedBy').innerHTML = "Nombre del docente";
                            _details.find("#description").text(scheds[id].sigla_curso + ": " + scheds[id].nombre_curso);
                        } else {
                            document.getElementById('details').innerHTML = "Razón de la reserva";
                            document.getElementById('reservedBy').innerHTML = "Reservado por";
                            _details.find("#description").text(scheds[id].nombre_curso);
                        }
                        _details.find("#name").text(scheds[id].solicitante);
                        _details.find("#start").text(scheds[id].sdate);
                        _details.find("#end").text(scheds[id].edate);
                        _details.find("#reserveID").text("ID de reserva: " + scheds[id].id);
                        _details.modal("show");
                    } else {
                        alertify.warning('Evento sin parámetros');
                    }
                },
                noEventsText: "No hay eventos",
                editable: true,
            });

            calendar.setOption('aspectRatio', 1.6);
            calendars.push(calendar);
        }

        function openCalendar(evt, calendarName) {
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
            document.getElementById(calendarName).style.display = "block";
            evt.currentTarget.className += " active";

            currentCalendar = calendarName;
            calendars[calendarNames.indexOf(calendarName)].render();
        }

        function deleteEvent(id) {
            alertify.confirm('Mensaje',
                'Esto eliminará la reservación seleccionada, ¿desea continuar?',
                function () {
                    document.cookie = "id = " + id;
                    /*
                    alertify.alert('Mensaje',
                        'La reservación ha sido eliminada',
                        function () { window.location.href = 'calendario-reserva.php' }
                    ).set({ transition: 'zoom', closable: false, 'label': 'Aceptar' });
                    */
                    var style = document.createElement("style");
                    style.textContent = ".ajs-cancel { display: none; }";
                    document.head.appendChild(style);
                    setTimeout(function () {
                        alertify.confirm('Mensaje',
                            'La reservación ha sido eliminada',
                            function () { window.location.href = 'calendario-reserva.php' },
                            function () { }
                        ).set({ transition: "zoom", closable: false, labels: { ok: 'Aceptar', cancel: 'Cancelar' } }).autoOk(5);
                    }, 100);

                },
                function () { }
            ).set({ transition: "zoom", closable: false, labels: { ok: 'Si', cancel: 'No' } });
        }

        // Get the element with id="defaultOpen" and click on it
        document.getElementById("defaultOpen").click();

    </script>

    </html>

    <?php
} else {
    header("Location: ../index.php");
    exit();
}
?>