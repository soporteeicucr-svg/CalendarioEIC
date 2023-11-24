<!DOCTYPE html>
<html lang='es'>

<?php
include '../shared/utils/get-courses.php';

session_start();

if (isset($_SESSION['usuario']) && isset($_SESSION['tipo'])) {
    if ($_SESSION['tipo'] == 'docente') {
        ?>

        <head>
            <meta charset='UTF-8'>
            <meta http-equiv='X-UA-Compatible' content='IE=edge'>
            <title>Solicitud de reserva</title>
            <link rel='stylesheet' type='text/css' href='../shared/lib/alertify/css/alertify.css'>
            <link rel='stylesheet' type='text/css' href='../shared/lib/alertify/css/themes/bootstrap.css'>
            <link rel='stylesheet' type='text/css' href='../shared/lib/flatpickr/flatpickr.min.css'>
            <link rel='stylesheet' type='text/css' href='../shared/lib/fontawesome/css/all.css'>
            <link rel='stylesheet' type='text/css' href='../shared/css/formulario.css'>
            <script src='../shared/lib/alertify/alertify.min.js'></script>
            <script src='../shared/lib/flatpickr/flatpickr.js'></script>
        </head>

        <body>
            <div>
                <div class="center">
                    <img src="../shared/img/logoEIC.png" alt="Logo Escuela de Ingeniería Civil UCR">
                    <h1>Sistema de reserva docente</h1>
                </div>
            </div>
            <form action="../shared/utils/save-schedule.php" method="post" id="schedule-form">
                <input type="hidden" name="id" value="">

                <!-- Selección de espacio -->
                <label for="espacio"><i class="fa-solid fa-star-of-life fa-2xs" style="color: #ff0000;"></i> Espacio a reservar
                    (Sujeto a disponibilidad)</label>
                <div class="selectContainer">
                    <select class="selectValue" name="espacio" id="espacio" onchange="updateSpace()" required>
                        <option value="">Seleccione un espacio</option>
                        <option value="Laboratorio 1">Laboratorio 1</option>
                        <option value="Laboratorio 2">Laboratorio 2</option>
                        <option value="Laboratorio 3">Laboratorio 3</option>
                        <option value="Aula PEC">Aula PEC (Requiere visto bueno de la dirección)</option>
                        <option value="Sala Interna">Sala Interna (Requiere visto bueno de la dirección)</option>
                        <option value="Sala Externa">Sala Externa</option>
                    </select>
                </div>

                <!-- Sigla del curso -->
                <label for="sigla_curso"><i class="fa-solid fa-star-of-life fa-2xs" style="color: #ff0000;"></i> Sigla del
                    curso</label>
                <input type="text" name="sigla_curso" id="sigla_curso"
                    placeholder="Ingrese la sigla del curso (ejemplos: IC-0101, IC9500)" oninput="searchCourse()">

                <!-- Nombre del curso (se autorrellena) -->
                <label for="nombre_curso">Nombre del curso</label>
                <input type="text" name="nombre_curso" id="nombre_curso" placeholder="Ingrese la sigla del curso primero"
                    readonly>

                <!-- Nombre del solicitante -->
                <label for="solicitante"><i class="fa-solid fa-star-of-life fa-2xs" style="color: #ff0000;"></i> Nombre del
                    solicitante</label>
                <input type="text" name="solicitante" id="solicitante" placeholder="Ingrese el nombre del solicitante"
                    value="<?php echo mb_convert_case(mb_strtolower($_SESSION['nombre'], 'UTF-8'), MB_CASE_TITLE); ?>" required>

                <!-- Correo institucional -->
                <label for="correo"><i class="fa-solid fa-star-of-life fa-2xs" style="color: #ff0000;"></i> Correo
                    institucional</label>
                <input type="text" name="correo" id="correo" placeholder="Ingrese su correo institucional" value=<?php echo mb_convert_case(mb_strtolower($_SESSION['usuario'], 'UTF-8'), MB_CASE_LOWER); ?> oninput="checkMail()">
                <div id="error_correo"></div>

                <!-- Selección de día -->
                <label for="fecha" class="control-label"><i class="fa-solid fa-star-of-life fa-2xs" style="color: #ff0000;"></i>
                    Día</label>
                <input type="text" name="fecha" id="fecha" placeholder="Seleccione fecha" required onchange="updateDate()">

                <!-- Selección de hora de inicio -->
                <label for="hora_inicio" class="control-label"><i class="fa-solid fa-star-of-life fa-2xs"
                        style="color: #ff0000;"></i>
                    Hora de inicio</label>
                <div class="selectContainer">
                    <select class="selectValue" name="hora_inicio" id="hora_inicio" onchange="updateTime('hora_inicio')"
                        required>
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
                <label for="hora_fin" class="control-label"><i class="fa-solid fa-star-of-life fa-2xs"
                        style="color: #ff0000;"></i>
                    Hora de finalización</label>
                <div class="selectContainer">
                    <select class="selectValue" name="hora_fin" id="hora_fin" onchange="updateTime('hora_fin')" required>
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

                <!-- Botón para enviar los datos al servidor -->
                <input type="button" id="btnSubmit" value="Enviar solicitud" onclick="sendForm(this.form);" disabled />
            </form>

            <!-- Botón para cancelar la solicitud -->
            <div class="center">
                <button id="btn5" onclick="location.href='inicio.php'">Cancelar</button>
            </div>

            <?php
            // Obtiene las reservaciones ya existentes que puedan chocar con la nueva
            $schedules = $conn->query("SELECT * FROM `lista_horarios` WHERE estado = 'aprobado' AND hora_inicio > CURRENT_DATE");
            $sched_res = [];
            foreach ($schedules->fetch_all(MYSQLI_ASSOC) as $row) {
                $sched_res[$row['id']] = $row;
            }
            ?>
        </body>
        <script>
            let listaCursos = <?php echo json_encode($courses); ?>;
            let listaReservas = <?php echo json_encode($sched_res); ?>;
            let validMail = false;
            let spaceSelected = false;
            let foundCourse = false;
            let validDate = false;
            let validStartTime = false;
            let validEndTime = false;
            let validStartEndTime = false;
            let validTimeRange = false;

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

            // Revisa que todos los campos estén correctos y activa el botón para enviar los datos
            function checkFilled() {
                if (validMail && spaceSelected && foundCourse && validDate && validStartTime && validEndTime && validStartEndTime && validTimeRange) {
                    document.getElementById('btnSubmit').disabled = false;
                } else {
                    document.getElementById('btnSubmit').disabled = true;
                }
            }

            // Verifica que el correo termine con @ucr.ac.cr
            function checkMail() {
                if (document.getElementById('correo').value.toLowerCase().endsWith("@ucr.ac.cr")) {
                    document.getElementById('error_correo').innerHTML = '';
                    validMail = true;
                } else {
                    document.getElementById('error_correo').innerHTML = "<h5 style='color: red; margin-top: 0;'>Error: el correo debe finalizar con @ucr.ac.cr</h5>";
                    validMail = false;
                }
                checkFilled();
            }

            // Compara el código introducido por el usuario con los códigos de los cursos en la base de datos y rellena el campo de nombre si encuentra un match
            function searchCourse() {
                let p = document.getElementById('sigla_curso').selectionStart;
                document.getElementById('sigla_curso').value = document.getElementById('sigla_curso').value.toUpperCase();
                document.getElementById('sigla_curso').setSelectionRange(p, p);

                // Asegura que el formato sea [Letra][Letra]-[Número][Número][Número][Número]
                let regex = /[A-Z][A-Z]-[0-9][0-9][0-9][0-9]/;
                let input = document.getElementById('sigla_curso').value;
                if (!regex.test(input)) { // Agrega el guión si el usuario no lo introduce manualmente
                    regex = /[A-Z][A-Z][0-9][0-9][0-9][0-9]/;
                    if (regex.test(input)) {
                        input = input.slice(0, 2) + "-" + input.slice(2);
                    }
                }
                let curso = listaCursos.find((element) => element.sigla_curso == input);

                // Si encuentra el curso obtiene el nombre
                if (curso) {
                    document.getElementById('sigla_curso').value = curso.sigla_curso;
                    document.getElementById('nombre_curso').setAttribute("value", curso.nombre_curso);
                    foundCourse = true;
                } else {
                    document.getElementById('nombre_curso').setAttribute("value", "");
                    foundCourse = false;
                }
                checkFilled();
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
            }

            // Verifica que el día y las horas elegidas no choquen con otros horarios
            function updateTime(input) {
                if (document.getElementById(input).value != "") {
                    let selectedDate = document.getElementById("fecha").value + " " + document.getElementById(input).value + ":00";
                    let conflictFound = false; // Variable para comprobar conflictos
                    let overlapFound = false;

                    if ((input == "hora_inicio" && document.getElementById(input).value >= "07:00" && document.getElementById(input).value < "22:00") || (input == "hora_fin" && document.getElementById(input).value > "07:00" && document.getElementById(input).value <= "22:00")) {
                        for (i in listaReservas) {
                            let startDateTime = listaReservas[i]["hora_inicio"];
                            let endDateTime = listaReservas[i]["hora_fin"];
                            let space = listaReservas[i]["espacio"];
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
                                if (newReservationStart < endDateTime && startDateTime < newReservationEnd && document.getElementById("espacio").value == space) {
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

            // Función para enviar la información al servidor
            function sendForm(form) {
                alertify.confirm('Mensaje',
                    'Solicitud de reserva enviada de manera exitosa, ¿desea cerrar la sesión?',
                    function () { form.submit() },
                    function () {
                        document.getElementById("continue").setAttribute("value", "yes");
                        form.submit();
                    }
                ).set({ transition: "zoom", closable: false, labels: { ok: 'Si', cancel: 'No' } }).autoOk(10);
            }

            checkMail();
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