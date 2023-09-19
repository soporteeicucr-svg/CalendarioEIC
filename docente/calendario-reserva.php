<?php
include '../shared/utils/db-conn.php';
include '../shared/utils/translate-date.php';

session_start();

if (isset($_SESSION['id']) && isset($_SESSION['usuario'])) {
    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Reserva docente</title>
        <link rel="stylesheet" type="text/css" href="../shared/lib/fontawesome/css/all.css">
        <link rel="stylesheet" type="text/css" href="../shared/lib/bootstrap/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="../shared/lib/fullcalendar/lib/main.min.css">
        <link rel="stylesheet" type="text/css" href="../shared/css/reserva.css">
        <link rel="stylesheet" type="text/css" href="../shared/css/tabs.css">
        <script src="../shared/lib/jquery/jquery-3.6.0.min.js"></script>
        <script src="../shared/lib/bootstrap/bootstrap.min.js"></script>
        <script src='../shared/lib/fullcalendar/index.global.min.js'></script>
        <script src="../shared/lib/fullcalendar/lib/main.min.js"></script>
        <style>
            :root {
                --bs-success-rgb: 71, 222, 152 !important;
            }

            html,
            body {
                height: 100%;
                width: 100%;
                font-family: Noto Sans, sans-serif;
            }

            .btn-info.text-light:hover,
            .btn-info.text-light:focus {
                background: #000;
            }

            table,
            tbody,
            td,
            tfoot,
            th,
            thead,
            tr {
                border-color: #ededed !important;
                border-style: solid;
                border-width: 1px !important;
            }
        </style>
    </head>

    <body class="bg-light">
        <nav class="navbar navbar-expand-lg navbar-dark bg-gradient bg-primary" id="topNavBar">
            <div class="container">
                <a class="navbar-brand">
                    Escuela de Ingeniería Civil | Sistema de reserva docente
                </a>
                <div>
                    <button class="buttonHome" onclick="location.href='inicio.php'">Inicio</button>
                </div>
            </div>
        </nav>
        <div class="container py-5" id="page-container">
            <div class="row">
                <div class="col-md-14">

                    <!-- Tab links -->
                    <div class="tab">
                        <button class="tablinks" onclick="openCalendar(event, 'Laboratorio 1')">Laboratorio 1</button>
                        <button class="tablinks" onclick="openCalendar(event, 'Laboratorio 2')">Laboratorio 2</button>
                        <button class="tablinks" onclick="openCalendar(event, 'Laboratorio 3')">Laboratorio 3</button>
                        <button class="tablinks" onclick="openCalendar(event, 'Aula PEC')">Aula PEC</button>
                        <button class="tablinks" onclick="openCalendar(event, 'Sala Interna')">Sala Interna</button>
                        <button class="tablinks" onclick="openCalendar(event, 'Sala Externa')">Sala Externa</button>
                    </div>

                    <!-- Tab content -->
                    <div id="Laboratorio 1" class="tabcontent">
                        <h3>Laboratorio 1</h3>
                        <div id="calendar Laboratorio 1"></div>
                    </div>

                    <div id="Laboratorio 2" class="tabcontent">
                        <h3>Laboratorio 2</h3>
                        <div id="calendar Laboratorio 2"></div>
                    </div>

                    <div id="Laboratorio 3" class="tabcontent">
                        <h3>Laboratorio 3</h3>
                        <div id="calendar Laboratorio 3"></div>
                    </div>

                    <div id="Aula PEC" class="tabcontent">
                        <h3>Aula PEC</h3>
                        <div id="calendar Aula PEC"></div>
                    </div>

                    <div id="Sala Interna" class="tabcontent">
                        <h3>Sala Interna</h3>
                        <div id="calendar Sala Interna"></div>
                    </div>

                    <div id="Sala Externa" class="tabcontent">
                        <h3>Sala Externa</h3>
                        <div id="calendar Sala Externa"></div>
                    </div>

                </div>
            </div>
        </div>
        <div class="modal fade" tabindex="-1" data-bs-backdrop="static" id="event-details-modal">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-0">
                    <div class="modal-header rounded-0">
                        <h5 class="modal-title">Detalles Reserva</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body rounded-0">
                        <div class="container-fluid">
                            <dl>
                                <dt class="text-muted">Espacio Reservado</dt>
                                <dd id="title" class="fw-bold fs-4"></dd>
                                <dt class="text-muted">Sigla del curso y nombre docente</dt>
                                <dd id="description" class=""></dd>
                                <dt class="text-muted">Inicio Reserva</dt>
                                <dd id="start" class=""></dd>
                                <dt class="text-muted">Finalización Reserva</dt>
                                <dd id="end" class=""></dd>
                                <!-- Botones de editar y borrar irían aquí -->
                            </dl>
                        </div>
                    </div>
                    <div class="modal-footer rounded-0">
                        <div class="text-end">
                            <button type="button" class="btn btn-secondary btn-sm rounded-0"
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
        <?php
        if (isset($conn))
            $conn->close();
        ?>
    </body>
    <script>
        var scheds = $.parseJSON('<?= json_encode($sched_res) ?>');
        var calendarNames = ["Laboratorio 1", "Laboratorio 2", "Laboratorio 3", "Aula PEC", "Sala Interna", "Sala Externa"];
        var calendars = [];

        for (i in calendarNames) {
            var events = [];
            if (!!scheds) {
                Object.keys(scheds).map((k) => {
                    if (scheds[k].espacio == calendarNames[i]) {
                        var row = scheds[k];
                        events.push({
                            id: row.id,
                            title: row.espacio,
                            start: row.hora_inicio,
                            end: row.hora_fin,
                        });
                    }
                });
            }

            var calendar = new FullCalendar.Calendar(document.getElementById("calendar " + calendarNames[i]), {
                headerToolbar: {
                    left: "prev,next today",
                    right: "timeGridWeek,dayGridMonth,list",
                    center: "title"
                },
                allDaySlot: false,
                locale: "es",
                selectable: true,
                hiddenDays: [0],
                themeSystem: "bootstrap",
                buttonText: {
                    today: 'Hoy', month: 'Mes', week: 'Semana', list: 'Lista'
                },
                events: events,
                eventClick: function (info) {
                    var _details = $("#event-details-modal");
                    var id = info.event.id;
                    if (!!scheds[id]) {
                        _details.find("#title").text(scheds[id].espacio);
                        _details.find("#description").text(scheds[id].sigla_curso + " | " + scheds[id].solicitante);
                        _details.find("#start").text(scheds[id].sdate);
                        _details.find("#end").text(scheds[id].edate);
                        _details.find("#edit,#delete").attr("data-id", id);
                        _details.modal("show");
                    } else {
                        alert("Evento sin parámetros");
                    }
                },
                eventDidMount: function (info) {
                    // Do Something after events mounted
                },
                editable: true,
            });

            calendar.changeView('timeGridWeek');
            calendars.push(calendar);

            /*
            // Form reset listener
            $("#schedule-form").on("reset", function () {
                $(this).find("input:hidden").val("");
                $(this).find("input:visible").first().focus();
            });

            // Edit Button
            $("#edit").click(function () {
                var id = $(this).attr("data-id");
                if (!!scheds[id]) {
                    var _form = $("#schedule-form");
                    console.log(
                        String(scheds[id].start_datetime),
                        String(scheds[id].start_datetime).replace(" ", "\\t")
                    );
                    _form.find('[name="id"]').val(id);
                    _form.find('[name="title"]').val(scheds[id].title);
                    _form.find('[name="description"]').val(scheds[id].description);
                    _form
                        .find('[name="start_datetime"]')
                        .val(String(scheds[id].start_datetime).replace(" ", "T"));
                    _form
                        .find('[name="end_datetime"]')
                        .val(String(scheds[id].end_datetime).replace(" ", "T"));
                    $("#event-details-modal").modal("hide");
                    _form.find('[name="title"]').focus();
                } else {
                    alert("Event is undefined");
                }
            });

            // Delete Button / Deleting an Event
            $("#delete").click(function () {
                var id = $(this).attr("data-id");
                if (!!scheds[id]) {
                    var _conf = confirm("¿Está seguro que desea eliminar esta reserva?");
                    if (_conf === true) {
                        location.href = "../utils/delete-schedule.php?id=" + id;
                    }
                } else {
                    alert("Event is undefined");
                }
            });
            */
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

            calendars[calendarNames.indexOf('calendarName')].render; // Error
        }

    </script>

    </html>

    <?php
} else {
    header("Location: ../index.php");
    exit();
}
?>