<!DOCTYPE html>
<html lang='es'>

<?php
include '../shared/utils/get-courses.php';
include '../shared/utils/get-spaces.php';

session_start();

if (isset($_SESSION['usuario']) && isset($_SESSION['tipo'])) {
    if ($_SESSION['tipo'] == 'administrativo') {
        ?>

        <head>
            <meta charset='UTF-8'>
            <meta http-equiv='X-UA-Compatible' content='IE=edge'>
            <title>Calendario EIC</title>
            <link rel='stylesheet' type='text/css' href='../shared/lib/alertify/css/alertify.css'>
            <link rel='stylesheet' type='text/css' href='../shared/lib/alertify/css/themes/bootstrap.css'>
            <link rel='stylesheet' type='text/css' href='../shared/lib/flatpickr/flatpickr.min.css'>
            <link rel='stylesheet' type='text/css' href='../shared/lib/fontawesome/css/all.css'>
            <link rel='stylesheet' type='text/css' href='../shared/css/formulario.css'>
            <link rel='stylesheet' type='text/css' href='../shared/css/confirm-modal-dual.css'>
            <script src='../shared/lib/alertify/alertify.min.js'></script>
            <script src='../shared/lib/flatpickr/flatpickr.js'></script>
        </head>

        <body>
            <div>
                <div class="center">
                    <img src="../shared/img/EIC.png" alt="Logo Escuela de Ingeniería Civil UCR">
                    <h1>Sistema Registro Laboratorios</h1>
                </div>
            </div>
            <form class="form-wide" action="../shared/utils/save-schedule.php" method="post" id="schedule-form">
                <input type="hidden" name="id" value="">

                <div class="form-grid">
                    <div class="form-col">
                        <h2 class="subDatos">Datos del solicitante</h2>

                        <!-- Nombre del solicitante: aqui si se debe escribir, ya que
                             quien reserva es un administrativo a nombre de otra
                             persona (no hay sesion de esa persona de la cual tomarlo) -->
                        <label for="solicitante">Nombre del solicitante</label>
                        <input type="text" name="solicitante" id="solicitante" placeholder="Ingrese el nombre del solicitante"
                            oninput="checkFilled()">

                        <!-- Correo institucional: por la misma razon, tambien se
                             escribe manualmente. Igual que en las pantallas de
                             inicio de sesión: lo que va antes del @ se pasa a
                             mayúsculas y el dominio a minúsculas al escribir, y
                             el dominio @ucr.ac.cr solo se valida hasta que se
                             deja de escribir (blur), no en cada tecla. -->
                        <label for="correo">Correo institucional</label>
                        <input type="text" name="correo" id="correo" placeholder="Ingrese el correo institucional del solicitante"
                            oninput="forceUppercase(this); clearMailError(); checkFilled();" onblur="checkMail()">
                        <div id="error_correo" class="field-error-text"></div>
                    </div>

                    <div class="form-col">
                        <h2 class="subDatos">Datos de reservación</h2>

                        <!-- Selección de espacio -->
                        <label for="espacio">Espacio a reservar (Sujeto a disponibilidad)</label>
                        <div class="selectContainer" id="espacio-container">
                            <select class="selectValue" name="espacio" id="espacio" onchange="updateSpace()">
                                <option value="">Seleccione un espacio</option>
                                <?php foreach ($spaces as $espacio) { ?>
                                    <option value="<?php echo htmlspecialchars($espacio); ?>">
                                        <?php echo htmlspecialchars($espacio); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <!-- Sigla y nombre del curso: se autocompletan entre sí
                             con los cursos existentes, pero tambien se puede
                             escribir uno nuevo o un motivo libre si no aplica a
                             un curso (no se guarda en la lista de cursos, solo
                             queda en esta reserva) -->
                        <label for="sigla_curso">Sigla del curso</label>
                        <input type="text" name="sigla_curso" id="sigla_curso" list="lista-siglas"
                            placeholder="Ingrese o elija la sigla del curso, o 'NA' si no aplica" oninput="onSiglaInput()"
                            autocomplete="off">
                        <datalist id="lista-siglas"></datalist>

                        <label for="nombre_curso">Nombre del curso</label>
                        <input type="text" name="nombre_curso" id="nombre_curso" list="lista-nombres"
                            placeholder="Ingrese o elija el nombre del curso, o el motivo de la reserva" oninput="onNombreInput()"
                            autocomplete="off">
                        <datalist id="lista-nombres"></datalist>
                    </div>
                </div>

                <!-- Activar repeticiópn de reserva -->
                <h4 style="margin: 0px">¿Desea repetir esta reserva?</h4>
                <div class="selectorForm">
                    <label for="si">
                        <input type="radio" name="repetir" value="si" id="si" onchange="changeRepetition('si');">Si
                    </label>
                    <label for="no">
                        <input type="radio" name="repetir" value="no" id="no" onchange="changeRepetition('no');" checked>No
                    </label>
                </div>

                <!-- Selección de día -->
                <div id="dia">
                    <label for="fecha" class="control-label">Fecha</label>
                    <input type="text" name="fecha" id="fecha" placeholder="Seleccione la fecha"
                        onchange="updateDate()">
                </div>

                <!-- Selección de rango de días -->
                <div id="rango">
                    <label for="numero_dia" class="control-label">Día</label>
                    <div class="selectContainer" id="numero-dia-container">
                        <select class="selectValue" name="numero_dia" id="numero_dia" onchange="updateDay()">
                            <option value="">Seleccione el día</option>
                            <option value="1">Lunes</option>
                            <option value="2">Martes</option>
                            <option value="3">Miércoles</option>
                            <option value="4">Jueves</option>
                            <option value="5">Viernes</option>
                            <option value="6">Sábado</option>
                        </select>
                    </div>
                    <label for="rango_inicio" class="control-label">Inicio</label>
                    <input type="text" name="rango_inicio" id="rango_inicio" placeholder="Seleccione la fecha de inicio"
                        onchange="updateDatePickers('rango_inicio')">
                    <label for="rango_fin" class="control-label">Finalización</label>
                    <input type="text" name="rango_fin" id="rango_fin" placeholder="Seleccione la fecha de finalización"
                        onchange="updateDatePickers('rango_fin')">
                </div>

                <!-- Selección de hora de inicio -->
                <label for="hora_inicio" class="control-label">Hora de inicio</label>
                <div class="selectContainer" id="hora-inicio-container">
                    <select class="selectValue" name="hora_inicio" id="hora_inicio"
                        onchange="updateTime('hora_inicio'); updateEndTimeOptions();">
                        <option value="">Seleccione la hora de inicio</option>
                        <option value="07:00">07:00</option>
                        <option value="07:30">07:30</option>
                        <option value="08:00">08:00</option>
                        <option value="08:30">08:30</option>
                        <option value="09:00">09:00</option>
                        <option value="09:30">09:30</option>
                        <option value="10:00">10:00</option>
                        <option value="10:30">10:30</option>
                        <option value="11:00">11:00</option>
                        <option value="11:30">11:30</option>
                        <option value="12:00">12:00</option>
                        <option value="12:30">12:30</option>
                        <option value="13:00">13:00</option>
                        <option value="13:30">13:30</option>
                        <option value="14:00">14:00</option>
                        <option value="14:30">14:30</option>
                        <option value="15:00">15:00</option>
                        <option value="15:30">15:30</option>
                        <option value="16:00">16:00</option>
                        <option value="16:30">16:30</option>
                        <option value="17:00">17:00</option>
                        <option value="17:30">17:30</option>
                        <option value="18:00">18:00</option>
                        <option value="18:30">18:30</option>
                        <option value="19:00">19:00</option>
                        <option value="19:30">19:30</option>
                        <option value="20:00">20:00</option>
                        <option value="20:30">20:30</option>
                        <option value="21:00">21:00</option>
                        <option value="21:30">21:30</option>
                    </select>
                </div>
                <div id="error_hora_inicio"></div>

                <!-- Selección de hora de finalización -->
                <label for="hora_fin" class="control-label">Hora de finalización</label>
                <div class="selectContainer" id="hora-fin-container">
                    <select class="selectValue" name="hora_fin" id="hora_fin" onchange="updateTime('hora_fin')">
                        <option value="">Seleccione la hora de finalización</option>
                        <option value="07:30">07:30</option>
                        <option value="08:00">08:00</option>
                        <option value="08:30">08:30</option>
                        <option value="09:00">09:00</option>
                        <option value="09:30">09:30</option>
                        <option value="10:00">10:00</option>
                        <option value="10:30">10:30</option>
                        <option value="11:00">11:00</option>
                        <option value="11:30">11:30</option>
                        <option value="12:00">12:00</option>
                        <option value="12:30">12:30</option>
                        <option value="13:00">13:00</option>
                        <option value="13:30">13:30</option>
                        <option value="14:00">14:00</option>
                        <option value="14:30">14:30</option>
                        <option value="15:00">15:00</option>
                        <option value="15:30">15:30</option>
                        <option value="16:00">16:00</option>
                        <option value="16:30">16:30</option>
                        <option value="17:00">17:00</option>
                        <option value="17:30">17:30</option>
                        <option value="18:00">18:00</option>
                        <option value="18:30">18:30</option>
                        <option value="19:00">19:00</option>
                        <option value="19:30">19:30</option>
                        <option value="20:00">20:00</option>
                        <option value="20:30">20:30</option>
                        <option value="21:00">21:00</option>
                        <option value="21:30">21:30</option>
                        <option value="22:00">22:00</option>
                    </select>
                </div>
                <div id="error_hora_fin"></div>
                <div id="hora_fin_mayor"></div>
                <div id="error_reserva_concurrente"></div>

                <!-- Continuar o cerrar sesión, por defecto intenta cerrar la sesión -->
                <input type="hidden" name="continue" id="continue" value="no" />

                <!-- Botón para enviar los datos al servidor. No queda deshabilitado
                     de verdad (para poder resaltar los campos faltantes al hacer
                     clic), solo se ve apagado mientras falten datos. -->
                <input type="button" id="btnSubmit" class="btn-incomplete" value="Enviar solicitud"
                    onclick="attemptSubmit(this.form);" />

                <!-- Botón para cancelar la solicitud: dentro de la misma tarjeta -->
                <button type="button" id="btn5" onclick="location.href='inicio.php'">Cancelar</button>
            </form>

            <?php
            // Obtiene las reservaciones ya existentes que puedan chocar con la nueva
            $approvedSchedsSelect = $conn->query("SELECT * FROM `lista_horarios` WHERE estado = 'aprobado' AND hora_inicio > CURRENT_DATE");
            $approvedScheds = [];
            foreach ($approvedSchedsSelect->fetch_all(MYSQLI_ASSOC) as $row) {
                $approvedScheds[$row['id']] = $row;
            }

            // Obtiene las reservaciones creadas pero sin aprobar que puedan chocar con la nueva
            $pendingSchedsSelect = $conn->query("SELECT * FROM `lista_horarios` WHERE estado = 'pendiente' AND hora_inicio > CURRENT_DATE");
            $pendingScheds = [];
            foreach ($pendingSchedsSelect->fetch_all(MYSQLI_ASSOC) as $row) {
                $pendingScheds[$row['id']] = $row;
            }
            ?>
        </body>
        <script>
            let listaCursos = <?php echo json_encode($courses); ?>;
            let listaReservasAprobadas = <?php echo json_encode($approvedScheds); ?>;
            let listaReservasPendientes = <?php echo json_encode($pendingScheds); ?>;
            let spaceSelected = false;
            let validMail = false;
            let validDate = false;
            let validStartTime = false;
            let validEndTime = false;
            let validStartEndTime = false;
            let validTimeRange = false;
            let validDay = false;
            let validRangeStart = false;
            let validRangeEnd = false;

            // Creación de un campo de selección de fecha con flatpickr
            minDate = new Date();
            flatpickr('#fecha', {
                position: "above",
                "minDate": minDate,
                "disable": [
                    function (date) {
                        return (date.getDay() === 0); // Desactiva los domingos
                    }
                ],
                "locale": {
                    "firstDayOfWeek": 1 // Empieza la semana en lunes
                }
            });

            // Revisa que todos los campos estén correctos y actualiza el aspecto
            // (apagado/activo) del botón de enviar
            function checkFilled() {
                let complete = document.getElementById('solicitante').value.trim() != "" && validMail &&
                    document.getElementById('sigla_curso').value.trim() != "" &&
                    document.getElementById('nombre_curso').value.trim() != "" &&
                    spaceSelected && validDate && validStartTime && validEndTime && validStartEndTime && validTimeRange && validDay && validRangeStart && validRangeEnd;
                document.getElementById('btnSubmit').classList.toggle('btn-incomplete', !complete);
            }

            // Llena las listas de sugerencias (datalist) de sigla y nombre con
            // los cursos existentes; no limitan lo que se puede escribir.
            function populateCourseDatalists() {
                let listaSiglas = document.getElementById('lista-siglas');
                let listaNombres = document.getElementById('lista-nombres');
                listaCursos.forEach(function (curso) {
                    let opcionSigla = document.createElement('option');
                    opcionSigla.value = curso.sigla_curso;
                    listaSiglas.appendChild(opcionSigla);

                    let opcionNombre = document.createElement('option');
                    opcionNombre.value = curso.nombre_curso;
                    listaNombres.appendChild(opcionNombre);
                });
            }

            // El AD de la EIC espera el usuario en mayúsculas (NOMBRE.APELLIDO),
            // pero el dominio "@ucr.ac.cr" debe quedar en minúsculas (así lo
            // reconoce el AD), igual que en las pantallas de inicio de sesión.
            function forceUppercase(input) {
                let p = input.selectionStart;
                let value = input.value;
                let at = value.indexOf('@');
                if (at === -1) {
                    value = value.toUpperCase();
                } else {
                    value = value.slice(0, at).toUpperCase() + '@' + value.slice(at + 1).toLowerCase();
                }
                input.value = value;
                input.setSelectionRange(p, p);
            }

            // Limpia el mensaje/resaltado de error del correo (al escribir, sin
            // validar todavía: la validación del dominio se hace hasta que se
            // deja de escribir, ver checkMail())
            function clearMailError() {
                document.getElementById('error_correo').textContent = '';
                document.getElementById('correo').classList.remove('input-error');
            }

            // Verifica que el correo termine con @ucr.ac.cr; se llama al salir
            // del campo (blur), no en cada tecla, para no mostrar el error
            // apenas se empieza a escribir
            function checkMail() {
                let correo = document.getElementById('correo').value.trim();
                if (correo === "") {
                    clearMailError();
                    validMail = false;
                } else if (!correo.toLowerCase().endsWith("@ucr.ac.cr")) {
                    document.getElementById('error_correo').textContent = 'El correo debe usar el dominio @ucr.ac.cr';
                    document.getElementById('correo').classList.add('input-error');
                    validMail = false;
                } else {
                    clearMailError();
                    validMail = true;
                }
                checkFilled();
            }

            // Si lo escrito/elegido coincide con un curso conocido, rellena el
            // otro campo; si no coincide (curso nuevo o motivo libre, no
            // registrado), no toca el otro campo.
            function onSiglaInput() {
                let valor = document.getElementById('sigla_curso').value.trim().toUpperCase();
                let curso = listaCursos.find((element) => element.sigla_curso.toUpperCase() == valor);
                if (curso) {
                    document.getElementById('nombre_curso').value = curso.nombre_curso;
                }
                checkFilled();
            }

            function onNombreInput() {
                let valor = document.getElementById('nombre_curso').value.trim().toUpperCase();
                let curso = listaCursos.find((element) => element.nombre_curso.toUpperCase() == valor);
                if (curso) {
                    document.getElementById('sigla_curso').value = curso.sigla_curso;
                }
                checkFilled();
            }

            // Convierte una hora "HH:MM" (24 horas) a formato de 12 horas para
            // mostrarla en las opciones; el "value" interno de cada opción se
            // mantiene en 24 horas para que las comparaciones (">=", "<=") sigan
            // funcionando igual.
            function formatHour12(value) {
                let [h, m] = value.split(':').map(Number);
                let date = new Date(2000, 0, 1, h, m);
                return date.toLocaleTimeString('es-CR', { hour: 'numeric', minute: '2-digit', hour12: true });
            }

            function formatTimeOptions() {
                ['hora_inicio', 'hora_fin'].forEach(function (id) {
                    document.querySelectorAll('#' + id + ' option[value]:not([value=""])').forEach(function (opt) {
                        opt.textContent = formatHour12(opt.value);
                    });
                });
            }

            // Oculta en "Hora de finalización" las horas que no son posteriores
            // a la hora de inicio elegida, para evitar seleccionar un rango
            // inválido
            function updateEndTimeOptions() {
                let inicio = document.getElementById('hora_inicio').value;
                let finSelect = document.getElementById('hora_fin');
                let seleccionOculta = false;

                Array.from(finSelect.options).forEach(function (opt) {
                    if (opt.value === '') {
                        return;
                    }
                    let oculta = inicio !== '' && opt.value <= inicio;
                    opt.hidden = oculta;
                    if (oculta && finSelect.value === opt.value) {
                        seleccionOculta = true;
                    }
                });

                if (seleccionOculta) {
                    finSelect.value = '';
                    updateTime('hora_fin');
                }
            }

            // Verifica que se haya elegido un espacio
            function updateSpace() {
                if (document.getElementById("espacio").value != "") {
                    spaceSelected = true;
                } else {
                    spaceSelected = false;
                }
                updateDate()
            }

            function updateDay() {
                if (document.getElementById("numero_dia").value != "") {
                    validDay = true;
                } else {
                    validDay = false;
                }
                checkFilled();
            }

            function changeRepetition(option) {
                if (option == 'si') {
                    document.getElementById('dia').setAttribute("hidden", "hidden");
                    document.getElementById('rango').removeAttribute("hidden");
                    document.getElementById('fecha').value = "";
                    validDate = true;
                    validDay = false;
                    validRangeStart = false;
                    validRangeEnd = false;
                } else {
                    document.getElementById('dia').removeAttribute("hidden");
                    document.getElementById('rango').setAttribute("hidden", "hidden");
                    document.getElementById('numero_dia').value = "";
                    document.getElementById('rango_inicio').value = "";
                    document.getElementById('rango_fin').value = "";
                    validDate = false;
                    validDay = true;
                    validRangeStart = true;
                    validRangeEnd = true;
                }
                updateDatePickers("");
            }

            // Verifica que se haya elegido un día
            function updateDate() {
                if (document.getElementById("fecha").value != "") {
                    validDate = true;
                } else {
                    validDate = false;
                }

                if (document.getElementById("hora_inicio").value != "") {
                    updateTime("hora_inicio");
                }
                if (document.getElementById("hora_fin").value != "") {
                    updateTime("hora_fin");
                }

                checkFilled();
            }

            // Verifica que el día y las horas elegidas no choquen con otros horarios
            function updateTime(input) {
                if (document.getElementById(input).value != "") {
                    let selectedDate = document.getElementById("fecha").value + " " + document.getElementById(input).value + ":00";
                    let conflictFound = false; // Variable para comprobar conflictos
                    let overlapFound = false;

                    if ((input == "hora_inicio" && document.getElementById(input).value >= "07:00" && document.getElementById(input).value < "22:00") || (input == "hora_fin" && document.getElementById(input).value > "07:00" && document.getElementById(input).value <= "22:00")) {
                        for (i in listaReservasAprobadas) {
                            let startDateTime = listaReservasAprobadas[i]["hora_inicio"];
                            let endDateTime = listaReservasAprobadas[i]["hora_fin"];
                            let space = listaReservasAprobadas[i]["espacio"];
                            if (selectedDate >= startDateTime && selectedDate <= endDateTime && document.getElementById("espacio").value == space) {
                                if (input == "hora_inicio") {
                                    if (selectedDate != endDateTime) {
                                        document.getElementById("error_hora_inicio").innerHTML = "<h5 style='color: red; margin-top: 0;'>Error: la fecha y hora elegida ya está reservada</h5>"
                                        validStartTime = false;
                                        conflictFound = true; // Activa la bandera si encuentra un conflicto
                                        break; // Termina el ciclo
                                    }
                                } else {
                                    if (selectedDate != startDateTime) {
                                        document.getElementById("error_hora_fin").innerHTML = "<h5 style='color: red; margin-top: 0;'>Error: la fecha y hora elegida ya está reservada</h5>"
                                        validEndTime = false;
                                        conflictFound = true; // Activa la bandera si encuentra un conflicto
                                        break; // Termina el ciclo
                                    }
                                }
                            }

                            let newReservationStart = "";
                            let newReservationEnd = "";

                            // Muestra un error si el tiempo elegido contiene otra reservación
                            if (document.getElementById("fecha").value != "" && document.getElementById("hora_inicio").value != "" && document.getElementById("hora_fin").value != "") {
                                newReservationStart = document.getElementById("fecha").value + " " + document.getElementById("hora_inicio").value + ":00";
                                newReservationEnd = document.getElementById("fecha").value + " " + document.getElementById("hora_fin").value + ":00";
                                if (newReservationStart < startDateTime && newReservationEnd > endDateTime && document.getElementById("espacio").value == space) {
                                    document.getElementById("error_reserva_concurrente").innerHTML = "<h5 style='color: red; margin-top: 0;'>Error: el rango de horas elegido hace conflicto con otra reserva</h5>";
                                    validTimeRange = false;
                                    overlapFound = true;
                                    break;
                                }
                            }
                        }

                        // Si no encuentra ningún choque de horarios limpia el mensaje de error
                        if (!conflictFound) {
                            document.getElementById("error_" + input).innerHTML = "";
                            if (input == "hora_inicio") {
                                validStartTime = true;
                            } else {
                                validEndTime = true;
                            }
                        }

                        // Si el horario elegido no contiene otro horario limpia el mensaje de error
                        if (!overlapFound) {
                            document.getElementById("error_reserva_concurrente").innerHTML = "";
                            validTimeRange = true;
                        }

                        if (document.getElementById("hora_fin").value != "" && document.getElementById("hora_inicio").value >= document.getElementById("hora_fin").value) {
                            document.getElementById("hora_fin_mayor").innerHTML = "<h5 style='color: red; margin-top: 0;'>Error: la hora de finalización no puede ser menor ni igual a la hora de inicio</h5>"
                            validStartEndTime = false;
                        } else {
                            document.getElementById("hora_fin_mayor").innerHTML = "";
                            validStartEndTime = true;
                        }

                        if (document.getElementById("error_hora_fin").innerHTML != "") {
                            document.getElementById("error_reserva_concurrente").innerHTML = "";
                        }

                    } else {
                        document.getElementById("error_" + input).innerHTML = "<h5 style='color: red; margin-top: 0;'>Error: la fecha y hora elegida no está disponible para reservas</h5>";
                        document.getElementById("hora_fin_mayor").innerHTML = "";
                        document.getElementById("error_reserva_concurrente").innerHTML = "";
                        if (input == "hora_inicio") {
                            validStartTime = false;
                        } else {
                            validEndTime = false;
                        }
                    }
                } else {
                    if (input == "hora_inicio") {
                        validStartTime = false;
                    } else {
                        validEndTime = false;
                    }
                }
                checkFilled();
            }

            function updateDatePickers(inputDate) {
                if (inputDate == "rango_inicio") {
                    let newMinDate = new Date(document.getElementById("rango_inicio").value + "T00:00:00.00-06:00");
                    flatpickr('#rango_fin', {
                        "minDate": newMinDate,
                        "disable": [
                            function (date) {
                                return (date.getDay() === 0);
                            }
                        ],
                        "locale": {
                            "firstDayOfWeek": 1
                        }
                    });
                } else if (inputDate == "rango_fin") {
                    let newMaxDate = new Date(document.getElementById("rango_fin").value + "T00:00:00.00-06:00")
                    flatpickr('#rango_inicio', {
                        "minDate": minDate,
                        "maxDate": newMaxDate,
                        "disable": [
                            function (date) {
                                return (date.getDay() === 0);
                            }
                        ],
                        "locale": {
                            "firstDayOfWeek": 1
                        }
                    });
                } else {
                    // Rango de fechas para repetir reservas
                    flatpickr('#rango_inicio', {
                        "minDate": minDate,
                        "disable": [
                            function (date) {
                                return (date.getDay() === 0);
                            }
                        ],
                        "locale": {
                            "firstDayOfWeek": 1
                        }
                    });
                    flatpickr('#rango_fin', {
                        "minDate": minDate,
                        "disable": [
                            function (date) {
                                return (date.getDay() === 0);
                            }
                        ],
                        "locale": {
                            "firstDayOfWeek": 1
                        }
                    });
                }

                if (inputDate != "") {
                    if (document.getElementById("rango_inicio").value != "") {
                        validRangeStart = true;
                    } else {
                        validRangeStart = false;
                    }

                    if (document.getElementById("rango_fin").value != "") {
                        validRangeEnd = true;
                    } else {
                        validRangeEnd = false;
                    }
                }

                checkFilled();
            }

            // Resalta en rojo los campos obligatorios que aún estén vacíos o
            // sean inválidos (el correo, según lo que ya haya validado checkMail())
            function highlightMissing() {
                let ok = true;

                let solicitanteEl = document.getElementById('solicitante');
                if (solicitanteEl.value.trim() === "") {
                    solicitanteEl.classList.add('input-error');
                    ok = false;
                } else {
                    solicitanteEl.classList.remove('input-error');
                }

                checkMail();
                let correoEl = document.getElementById('correo');
                if (correoEl.value.trim() === "") {
                    correoEl.classList.add('input-error');
                    ok = false;
                } else if (!validMail) {
                    ok = false;
                }

                ['sigla_curso', 'nombre_curso'].forEach(function (id) {
                    let el = document.getElementById(id);
                    if (el.value.trim() === "") {
                        el.classList.add('input-error');
                        ok = false;
                    } else {
                        el.classList.remove('input-error');
                    }
                });

                let espacioContainer = document.getElementById('espacio-container');
                if (document.getElementById('espacio').value === "") {
                    espacioContainer.classList.add('input-error');
                    ok = false;
                } else {
                    espacioContainer.classList.remove('input-error');
                }

                if (document.querySelector('input[name="repetir"]:checked').value == "no") {
                    let fechaEl = document.getElementById('fecha');
                    if (fechaEl.value.trim() === "") {
                        fechaEl.classList.add('input-error');
                        ok = false;
                    } else {
                        fechaEl.classList.remove('input-error');
                    }
                } else {
                    let numeroDiaContainer = document.getElementById('numero-dia-container');
                    if (document.getElementById('numero_dia').value === "") {
                        numeroDiaContainer.classList.add('input-error');
                        ok = false;
                    } else {
                        numeroDiaContainer.classList.remove('input-error');
                    }

                    ['rango_inicio', 'rango_fin'].forEach(function (id) {
                        let el = document.getElementById(id);
                        if (el.value.trim() === "") {
                            el.classList.add('input-error');
                            ok = false;
                        } else {
                            el.classList.remove('input-error');
                        }
                    });
                }

                let horaInicioContainer = document.getElementById('hora-inicio-container');
                if (document.getElementById('hora_inicio').value === "") {
                    horaInicioContainer.classList.add('input-error');
                    ok = false;
                } else {
                    horaInicioContainer.classList.remove('input-error');
                }

                let horaFinContainer = document.getElementById('hora-fin-container');
                if (document.getElementById('hora_fin').value === "") {
                    horaFinContainer.classList.add('input-error');
                    ok = false;
                } else {
                    horaFinContainer.classList.remove('input-error');
                }

                return ok;
            }

            // Al hacer clic en "Enviar solicitud": si falta algo o el correo no es
            // válido lo resalta, si no, muestra el recuadro de confirmación con
            // los detalles antes de continuar con el envío
            function attemptSubmit(form) {
                let fieldsOk = highlightMissing();
                if (fieldsOk && validDate && validStartTime && validEndTime && validStartEndTime && validTimeRange && validDay && validRangeStart && validRangeEnd) {
                    confirmReservation(form);
                }
            }

            // Escapa texto antes de insertarlo como HTML en el recuadro de detalles
            function escapeHtml(texto) {
                let div = document.createElement('div');
                div.textContent = texto;
                return div.innerHTML;
            }

            function diaNombre(numero) {
                let dias = { '1': 'Lunes', '2': 'Martes', '3': 'Miércoles', '4': 'Jueves', '5': 'Viernes', '6': 'Sábado' };
                return dias[numero] || '';
            }

            // Recuadro de confirmación con los detalles de la reserva: hay que
            // darle clic a "Confirmar" para que se envíe de verdad (si se
            // cancela, no se crea nada y se puede seguir editando el formulario)
            function confirmReservation(form) {
                let repite = document.querySelector('input[name="repetir"]:checked').value == 'si';
                let horario = formatHour12(document.getElementById('hora_inicio').value) + ' - ' +
                    formatHour12(document.getElementById('hora_fin').value);

                let detalles = '<div class="confirm-space-badge"><i class="fa-solid fa-building"></i><span>' +
                    escapeHtml(document.getElementById('espacio').value) + '</span></div>' +
                    '<div class="confirm-details">' +
                    '<p><span>Solicitante:</span> ' + escapeHtml(document.getElementById('solicitante').value) + '</p>' +
                    '<p><span>Correo:</span> ' + escapeHtml(document.getElementById('correo').value) + '</p>' +
                    '<p><span>Sigla del curso:</span> ' + escapeHtml(document.getElementById('sigla_curso').value) + '</p>' +
                    '<p><span>Nombre del curso:</span> ' + escapeHtml(document.getElementById('nombre_curso').value) + '</p>';

                if (repite) {
                    detalles += '<p><span>Se repite:</span> Todos los ' + diaNombre(document.getElementById('numero_dia').value) + '</p>' +
                        '<p><span>Desde:</span> ' + escapeHtml(document.getElementById('rango_inicio').value) + '</p>' +
                        '<p><span>Hasta:</span> ' + escapeHtml(document.getElementById('rango_fin').value) + '</p>';
                } else {
                    detalles += '<p><span>Fecha:</span> ' + escapeHtml(document.getElementById('fecha').value) + '</p>';
                }

                detalles += '<p><span>Horario:</span> ' + horario + '</p>' +
                    '</div>' +
                    '<p>¿Confirma la creación de esta reserva?</p>';

                // sendForm() abre a su vez otro recuadro de alertify (mensaje de
                // éxito, o el chequeo de conflictos si la reserva se repite).
                // alertify.js reutiliza internamente el mismo diálogo para
                // "confirm", así que hay que esperar a que este se termine de
                // cerrar antes de abrir el siguiente; si no, el segundo
                // recuadro queda visualmente encima pero sin funcionar
                // (el botón no hace nada y la reserva nunca se guarda).
                alertify.confirm('Confirmar reserva',
                    detalles,
                    function () { setTimeout(function () { sendForm(form); }, 300); },
                    function () { }
                ).set({ transition: "zoom", closable: false, labels: { ok: 'Confirmar', cancel: 'Cancelar' } });
            }

            // Función para enviar la información al servidor
            function sendForm(form) {
                if (document.querySelector('input[name="repetir"]:checked').value == "no") {
                    alertify.confirm('Mensaje',
                        'Reservación creada de manera exitosa, ¿desea cerrar la sesión?',
                        function () { form.submit() },
                        function () {
                            document.getElementById("continue").setAttribute("value", "yes");
                            form.submit();
                        }
                    ).set({ transition: "zoom", closable: false, labels: { ok: 'Si', cancel: 'No' } }).autoOk(10);
                } else {
                    let dayNum = document.getElementById("numero_dia").value;
                    let dateRangeStart = new Date(document.getElementById("rango_inicio").value + "T00:00:00.00-06:00");
                    let dateRangeEnd = new Date(document.getElementById("rango_fin").value + "T00:00:00.00-06:00");
                    let range = dateRangeStart;
                    let dates = [];

                    while (range <= dateRangeEnd) {
                        if (range.getDay() == dayNum) {
                            dates.push(new Date(range).toISOString().split("T")[0]);
                        }
                        range.setDate(range.getDate() + 1);
                    }

                    let conflictingDatesApproved = [];
                    let conflictingDatesPending = [];

                    for (date in dates) {
                        let newReservationStart = dates[date] + " " + document.getElementById("hora_inicio").value + ":00";
                        let newReservationEnd = dates[date] + " " + document.getElementById("hora_fin").value + ":00";

                        for (i in listaReservasAprobadas) {
                            let startDateTime = listaReservasAprobadas[i]["hora_inicio"];
                            let endDateTime = listaReservasAprobadas[i]["hora_fin"];
                            let space = listaReservasAprobadas[i]["espacio"];
                            if ((newReservationStart >= startDateTime && newReservationStart <= endDateTime && document.getElementById("espacio").value == space)
                                || (newReservationEnd >= startDateTime && newReservationEnd <= endDateTime && document.getElementById("espacio").value == space)
                                || (newReservationStart < startDateTime && newReservationEnd > endDateTime && document.getElementById("espacio").value == space)) {
                                conflictingDatesApproved.push(listaReservasAprobadas[i]);
                            }
                        }

                        for (i in listaReservasPendientes) {
                            let startDateTime = listaReservasPendientes[i]["hora_inicio"];
                            let endDateTime = listaReservasPendientes[i]["hora_fin"];
                            let space = listaReservasPendientes[i]["espacio"];
                            if ((newReservationStart >= startDateTime && newReservationStart <= endDateTime && document.getElementById("espacio").value == space)
                                || (newReservationEnd >= startDateTime && newReservationEnd <= endDateTime && document.getElementById("espacio").value == space)
                                || (newReservationStart < startDateTime && newReservationEnd > endDateTime && document.getElementById("espacio").value == space)) {
                                conflictingDatesPending.push(listaReservasPendientes[i]);
                            }
                        }
                    }

                    if (conflictingDatesApproved.length > 0 || conflictingDatesPending.length > 0) {
                        var overlappingMessage = "";

                        if (conflictingDatesApproved.length > 0) {
                            overlappingMessage = overlappingMessage + 'Solicitudes de reserva aprobadas:<br>'
                            for (i in conflictingDatesApproved) {
                                overlappingMessage = overlappingMessage + 'ID: ' + conflictingDatesApproved[i]['id'] + ', Inicio: ' + conflictingDatesApproved[i]['hora_inicio'] + ', Finalización: ' + conflictingDatesApproved[i]['hora_fin'] + '<br>';
                            }
                        }

                        if (conflictingDatesApproved.length > 0 && conflictingDatesPending.length > 0) {
                            overlappingMessage = overlappingMessage + '<br>'
                        }

                        if (conflictingDatesPending.length > 0) {
                            overlappingMessage = overlappingMessage + 'Solicitudes de reserva pendientes:<br>'
                            for (i in conflictingDatesPending) {
                                overlappingMessage = overlappingMessage + 'ID: ' + conflictingDatesPending[i]['id'] + ', Inicio: ' + conflictingDatesPending[i]['hora_inicio'] + ', Finalización: ' + conflictingDatesPending[i]['hora_fin'] + '<br>';
                            }
                        }

                        alertify.confirm('Alerta',
                            'Esto cancelará/rechazará las siguientes reservaciones:<br><br>' + overlappingMessage + '<br>¿desea continuar?',
                            function () {
                                setTimeout(function () {
                                    alertify.confirm('Mensaje',
                                        'Reservaciones creadas de manera exitosa, ¿desea cerrar la sesión?',
                                        function () {
                                            let approved = [];
                                            let pending = [];

                                            for (i in conflictingDatesApproved) {
                                                approved.push(conflictingDatesApproved[i]['id']);
                                            }

                                            for (i in conflictingDatesPending) {
                                                pending.push(conflictingDatesPending[i]['id']);
                                            }

                                            var element1 = document.createElement("input");
                                            var element2 = document.createElement("input");
                                            var element3 = document.createElement("input");

                                            element1.name = "dates";
                                            element1.value = dates;
                                            form.appendChild(element1);

                                            element2.name = "approved";
                                            element2.value = approved;
                                            form.appendChild(element2);

                                            element3.name = "pending";
                                            element3.value = pending;
                                            form.appendChild(element3);

                                            form.submit();
                                        },
                                        function () {
                                            document.getElementById("continue").setAttribute("value", "yes");

                                            let approved = [];
                                            let pending = [];

                                            var element1 = document.createElement("input");
                                            var element2 = document.createElement("input");
                                            var element3 = document.createElement("input");

                                            element1.name = "dates";
                                            element1.value = dates;
                                            form.appendChild(element1);

                                            element2.name = "approved";
                                            element2.value = approved;
                                            form.appendChild(element2);

                                            element3.name = "pending";
                                            element3.value = pending;
                                            form.appendChild(element3);

                                            form.submit();
                                        }
                                    ).set({ transition: "zoom", closable: false, labels: { ok: 'Si', cancel: 'No' } }).autoOk(10);
                                }, 100);
                            },
                            function () { }
                        ).set({ transition: "zoom", closable: false, labels: { ok: 'Si', cancel: 'No' } });
                    } else {
                        alertify.confirm('Mensaje',
                            'Reservaciones creadas de manera exitosa, ¿desea cerrar la sesión?',
                            function () {
                                let approved = [];
                                let pending = [];

                                var element1 = document.createElement("input");
                                var element2 = document.createElement("input");
                                var element3 = document.createElement("input");

                                element1.name = "dates";
                                element1.value = dates;
                                form.appendChild(element1);

                                element2.name = "approved";
                                element2.value = approved;
                                form.appendChild(element2);

                                element3.name = "pending";
                                element3.value = pending;
                                form.appendChild(element3);

                                form.submit();
                            },
                            function () {
                                document.getElementById("continue").setAttribute("value", "yes");

                                let approved = [];
                                let pending = [];

                                var element1 = document.createElement("input");
                                var element2 = document.createElement("input");
                                var element3 = document.createElement("input");

                                element1.name = "dates";
                                element1.value = dates;
                                form.appendChild(element1);

                                element2.name = "approved";
                                element2.value = approved;
                                form.appendChild(element2);

                                element3.name = "pending";
                                element3.value = pending;
                                form.appendChild(element3);

                                form.submit();
                            }
                        ).set({ transition: "zoom", closable: false, labels: { ok: 'Si', cancel: 'No' } }).autoOk(10);
                    }
                }
            }

            populateCourseDatalists();
            changeRepetition('no');
            formatTimeOptions();
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