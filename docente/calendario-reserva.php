<!DOCTYPE html>
<html lang='es'>

<?php
include '../shared/utils/db-conn.php';
include '../shared/utils/translate-date.php';
include '../shared/utils/get-spaces.php';

session_start();

if (isset($_SESSION['usuario']) && isset($_SESSION['tipo'])) {
    if ($_SESSION['tipo'] == 'docente') {
        ?>

        <head>
            <meta charset='UTF-8'>
            <meta http-equiv='X-UA-Compatible' content='IE=edge'>
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Calendario EIC</title>
            <link rel='stylesheet' type='text/css' href='../shared/lib/alertify/css/alertify.css'>
            <link rel='stylesheet' type='text/css' href='../shared/lib/alertify/css/themes/bootstrap.css'>
            <link rel='stylesheet' type='text/css' href='../shared/lib/bootstrap/bootstrap.min.css'>
            <link rel='stylesheet' type='text/css' href='../shared/lib/fontawesome/css/all.css'>
            <link rel='stylesheet' type='text/css' href='../shared/lib/fullcalendar/lib/main.min.css'>
            <link rel='stylesheet' type='text/css' href='../shared/css/calendario.css'>
            <script src='../shared/lib/alertify/alertify.min.js'></script>
            <script src='../shared/lib/bootstrap/bootstrap.min.js'></script>
            <script src='../shared/lib/fullcalendar/index.global.min.js'></script>
            <script src='../shared/lib/fullcalendar/lib/main.min.js'></script>
            <script src='../shared/lib/jquery/jquery-3.6.0.min.js'></script>
        </head>

        <body>
            <div class="cal-header">
                <div class="cal-header-brand">
                    <img src="../shared/img/EIC.png" alt="Logo Escuela de Ingeniería Civil UCR">
                    <h1>Calendario de reservas</h1>
                </div>
                <button type="button" class="btn-back" onclick="location.href='inicio.php'">
                    <i class="fa-solid fa-arrow-left"></i> Inicio
                </button>
            </div>

            <div class="calendar-card">
                <!-- Pestañas: una por cada espacio del catálogo
                     (administrativo/gestionar-catalogos.php) -->
                <div class="calendar-tabs">
                    <?php foreach ($spaces as $i => $espacio) { ?>
                        <button class="cal-tab" onclick="openCalendar(event, '<?php echo htmlspecialchars($espacio, ENT_QUOTES); ?>')"
                            <?php echo $i == 0 ? 'id="defaultOpen"' : ''; ?>><?php echo htmlspecialchars($espacio); ?></button>
                    <?php } ?>
                </div>

                <!-- Contenido de las tabs -->
                <!-- Elementos donde se insertan los calendarios cuando se renderizan -->
                <?php foreach ($spaces as $espacio) { ?>
                    <div id="<?php echo htmlspecialchars($espacio, ENT_QUOTES); ?>" class="tabcontent cal-fade">
                        <div id="calendar <?php echo htmlspecialchars($espacio, ENT_QUOTES); ?>"></div>
                    </div>
                <?php } ?>
            </div>

            <!-- Modal con información que se muestra al hacer click en una reservación -->
            <div class="modal fade" tabindex="-1" data-bs-backdrop="static" id="event-details-modal">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Detalles de reservación</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <dl class="cal-modal-info">
                                <dt>Información</dt>
                                <dd id="title"></dd>
                                <dt>Inicio</dt>
                                <dd id="start"></dd>
                                <dt>Finalización</dt>
                                <dd id="end"></dd>
                            </dl>
                        </div>
                        <div class="modal-footer">
                            <!-- Botón para cerrar el modal -->
                            <button type="button" class="cal-modal-close" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            // Lectura de reservaciones aprobadas en la base de datos
            $schedules = $conn->query("SELECT * FROM `lista_horarios` WHERE estado = 'aprobado'");
            $sched_res = [];
            foreach ($schedules->fetch_all(MYSQLI_ASSOC) as $row) {
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
            var scheds = <?= json_encode($sched_res, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
            var calendarNames = <?= json_encode($spaces, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
            var calendars = [];

            // Carga de reservaciones en el calendario
            for (i in calendarNames) {
                var events = [];
                if (!!scheds) {
                    Object.keys(scheds).map((k) => {
                        if (scheds[k].espacio == calendarNames[i]) {
                            var row = scheds[k];
                            events.push({
                                id: row.id,
                                title: "Reservado",
                                start: row.hora_inicio,
                                end: row.hora_fin,
                            });
                        }
                    });
                }

                // Generación de la vista del calendario
                var calendar = new FullCalendar.Calendar(document.getElementById("calendar " + calendarNames[i]), {
                    headerToolbar: {
                        left: "prev,next today",
                        right: "timeGridWeek,dayGridMonth",
                        center: "title"
                    },
                    initialView: 'timeGridWeek',
                    slotMinTime: "07:00:00",
                    slotMaxTime: "22:00:00",
                    slotLabelFormat: { hour: 'numeric', minute: '2-digit', hour12: true },
                    eventTimeFormat: { hour: 'numeric', minute: '2-digit', hour12: true },
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
                            _details.find("#start").text(scheds[id].sdate);
                            _details.find("#end").text(scheds[id].edate);
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

            // Función para cambiar de calendario
            function openCalendar(evt, calendarName) {
                var i, tabcontent, tablinks;

                // Busca todos los elementos con class="tabcontent" y los esconde
                tabcontent = document.getElementsByClassName("tabcontent");
                for (i = 0; i < tabcontent.length; i++) {
                    tabcontent[i].style.display = "none";
                }

                // Busca todos los elementos con class="cal-tab" y borra la class "active"
                tablinks = document.getElementsByClassName("cal-tab");
                for (i = 0; i < tablinks.length; i++) {
                    tablinks[i].className = tablinks[i].className.replace(" active", "");
                }

                // Muestra la tab elegida y agrega la class "active" al botón
                document.getElementById(calendarName).style.display = "block";
                evt.currentTarget.className += " active";

                // Agrega el calendario a la página
                calendars[calendarNames.indexOf(calendarName)].render();
            }

            // Busca el elemento con id="defaultOpen" y lo abre al entrar a la página
            document.getElementById("defaultOpen").click();

        </script>

        </html>

        <?php
    } else {
        header("Location: ../index.php");
        exit();
    }
} else {
    header("Location: ../index.php");
    exit();
}
?>
