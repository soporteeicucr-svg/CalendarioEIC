<!DOCTYPE html>
<html lang='es'>

<?php
include '../shared/utils/db-conn.php';
include '../shared/utils/translate-date.php';
include '../shared/utils/send-mail.php';
include '../shared/utils/get-spaces.php';

session_start();

if (isset($_SESSION['usuario']) && isset($_SESSION['tipo'])) {
    if ($_SESSION['tipo'] == 'administrativo') {

        // Búsqueda de cookie en caso de activar el proceso de cancelar de una reservación
        if (isset($_COOKIE['id'])) {
            $id = $_COOKIE['id'];
            unset($_COOKIE['id']);
            echo '<script>document.cookie = "id=' . $id . '; expires=Thu, 01 Jan 1970 00:00:00 UTC";</script>';

            // Búsqueda de la reservación a cancelar
            $select = "SELECT * FROM `lista_horarios` WHERE id = '$id'";
            $result = mysqli_query($conn, $select);
            $schedule = mysqli_fetch_assoc($result);

            // Cancelación de la reserva
            $select = "UPDATE `lista_horarios` SET estado = 'rechazado' WHERE id = '$id'";
            $result = mysqli_query($conn, $select);

            // Obtención de datos de la reserva para avisar sobre cancelación
            $id = $schedule['id'];
            $espacio = $schedule['espacio'];
            $siglaCurso = $schedule['sigla_curso'];
            $nombreCurso = $schedule['nombre_curso'];
            $solicitante = $schedule['solicitante'];
            $correo = $schedule['correo'];
            $horaInicio = $schedule['hora_inicio'];
            $horaFin = $schedule['hora_fin'];

            // Envío de correo avisando sobre cancelación
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
            sendMail($correo, 'Su reservación ha sido cancelada', $message, '<h3>No contestar a este correo</h3>');

            // Recarga la página al terminar
            echo '<script type = "text/javascript">';
            echo 'window.location.href = "calendario-reserva.php";';
            echo '</script>';
            die();
        }

        // Búsqueda de cookie en caso de activar el proceso de cancelar una
        // serie completa de reservaciones repetidas
        if (isset($_COOKIE['serie'])) {
            // Solo se aceptan los caracteres hexadecimales con los que se
            // genera el serie_id (ver save-schedule.php), para no usar el
            // valor de la cookie sin filtrar dentro de la consulta.
            $serieId = preg_replace('/[^a-f0-9]/', '', $_COOKIE['serie']);
            unset($_COOKIE['serie']);
            echo '<script>document.cookie = "serie=' . $serieId . '; expires=Thu, 01 Jan 1970 00:00:00 UTC";</script>';

            // Búsqueda de las reservaciones aprobadas de la serie a cancelar
            $select = "SELECT * FROM `lista_horarios` WHERE serie_id = '$serieId' AND estado = 'aprobado'";
            $result = mysqli_query($conn, $select);
            $serieSchedules = $result->fetch_all(MYSQLI_ASSOC);

            // Cancelación de toda la serie
            $select = "UPDATE `lista_horarios` SET estado = 'rechazado' WHERE serie_id = '$serieId'";
            $result = mysqli_query($conn, $select);

            if (count($serieSchedules) > 0) {
                $primera = $serieSchedules[0];
                $espacio = $primera['espacio'];
                $siglaCurso = $primera['sigla_curso'];
                $nombreCurso = $primera['nombre_curso'];
                $solicitante = $primera['solicitante'];
                $correo = $primera['correo'];

                $fechas = '';
                foreach ($serieSchedules as $reserva) {
                    $fechas = $fechas . 'Desde: ' . $reserva['hora_inicio'] . ' hasta: ' . $reserva['hora_fin'] . '<br>';
                }

                // Envío de correo avisando sobre la cancelación de la serie
                $message = "<p>Su reservación repetida ha sido cancelada por completo, a continuación se muestran los detalles de la misma.</p>
                <p>Espacio reservado: <b>$espacio</b></p>
                <p>Sigla del curso: <b>$siglaCurso</b></p>
                <p>Nombre del curso: <b>$nombreCurso</b></p>
                <p>Nombre del solicitante: <b>$solicitante</b></p>
                <p>Correo del solicitante: <b>$correo</b></p>
                <p>Fechas y horas canceladas:</p>
                <p>$fechas</p>
                <p>Puede que otra reservación haya sido solicitada con anterioridad en el horario especificado, si aún requiere de una reservación, por favor solicítela nuevamente o póngase en contacto con la dirección de la escuela, gracias.</p>";
                sendMail($correo, 'Su reservación repetida ha sido cancelada', $message, '<h3>No contestar a este correo</h3>');
            }

            // Recarga la página al terminar
            echo '<script type = "text/javascript">';
            echo 'window.location.href = "calendario-reserva.php";';
            echo '</script>';
            die();
        }
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
            <link rel='stylesheet' type='text/css' href='../shared/css/confirm-modal-dual.css'>
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
                                <dt id="details"></dt>
                                <dd id="description"></dd>
                                <dt id="reservedBy"></dt>
                                <dd id="name"></dd>
                                <dt>Inicio</dt>
                                <dd id="start"></dd>
                                <dt>Finalización</dt>
                                <dd id="end"></dd>
                            </dl>
                        </div>
                        <div class="modal-footer">
                            <p class="cal-modal-reserveid" id="reserveID"></p>
                            <div class="cal-modal-actions">
                                <!-- Botón de cancelación de toda la serie repetida: solo se
                                     muestra cuando la reservación pertenece a una serie -->
                                <button type="button" class="cal-modal-danger-outline" id="btnCancelSeries" hidden
                                    onclick="deleteSeries(this.getAttribute('data-serie'))" data-bs-dismiss="modal">Cancelar
                                    toda la serie</button>

                                <!-- Botón de cancelación de esta sola reservación -->
                                <button type="button" class="cal-modal-danger"
                                    onclick="deleteEvent(document.getElementById('reserveID').innerHTML.split(': ')[1])"
                                    data-bs-dismiss="modal">Cancelar esta reserva</button>

                                <!-- Botón para cerrar el modal -->
                                <button type="button" class="cal-modal-close" data-bs-dismiss="modal">Cerrar</button>
                            </div>
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

                            var btnCancelSeries = document.getElementById('btnCancelSeries');
                            if (scheds[id].serie_id) {
                                btnCancelSeries.hidden = false;
                                btnCancelSeries.setAttribute('data-serie', scheds[id].serie_id);
                            } else {
                                btnCancelSeries.hidden = true;
                                btnCancelSeries.removeAttribute('data-serie');
                            }

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

            // Función para cancelar una reservación
            function deleteEvent(id) {

                // Mensaje de confirmación
                alertify.confirm('Mensaje',
                    'Esto cancelará la reservación seleccionada, ¿desea continuar?',
                    function () {

                        // Creación de cookie para enviar el número de reservación al servidor
                        document.cookie = "id = " + id;
                        var style = document.createElement("style");
                        style.textContent = ".ajs-cancel { display: none; }";
                        document.head.appendChild(style);
                        setTimeout(function () {

                            // Mensaje de cancelación exitosa
                            alertify.confirm('Mensaje',
                                'La reservación ha sido cancelada',
                                function () { window.location.href = 'calendario-reserva.php' },
                                function () { }
                            ).set({ transition: "zoom", closable: false, labels: { ok: 'Aceptar', cancel: 'Cancelar' } }).autoOk(5);
                        }, 100);
                    },
                    function () { }
                ).set({ transition: "zoom", closable: false, labels: { ok: 'Si', cancel: 'No' } });
            }

            // Función para cancelar toda una serie de reservaciones repetidas
            function deleteSeries(serieId) {

                // Mensaje de confirmación
                alertify.confirm('Mensaje',
                    'Esto cancelará TODAS las reservaciones repetidas de esta serie, ¿desea continuar?',
                    function () {

                        // Creación de cookie para enviar el identificador de la serie al servidor
                        document.cookie = "serie = " + serieId;
                        var style = document.createElement("style");
                        style.textContent = ".ajs-cancel { display: none; }";
                        document.head.appendChild(style);
                        setTimeout(function () {

                            // Mensaje de cancelación exitosa
                            alertify.confirm('Mensaje',
                                'La serie de reservaciones ha sido cancelada',
                                function () { window.location.href = 'calendario-reserva.php' },
                                function () { }
                            ).set({ transition: "zoom", closable: false, labels: { ok: 'Aceptar', cancel: 'Cancelar' } }).autoOk(5);
                        }, 100);
                    },
                    function () { }
                ).set({ transition: "zoom", closable: false, labels: { ok: 'Si', cancel: 'No' } });
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
