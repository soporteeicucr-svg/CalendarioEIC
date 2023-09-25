<?php
include '../shared/utils/get-courses.php';

session_start();

if (isset($_SESSION['id']) && isset($_SESSION['usuario'])) {
    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Sistema Reserva EIC</title>
        <link rel="stylesheet" type="text/css" href="../shared/lib/fontawesome/css/all.css">
        <link rel="stylesheet" type="text/css" href="../shared/css/formulario.css">
        <!-- Date and time picker -->
        <link rel="stylesheet" type="text/css" href="../shared/lib/flatpickr/flatpickr.min.css">
        <script src="../shared/lib/flatpickr/flatpickr.js"></script>
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
            <label for="espacio">Espacio a reservar (Sujeto a disponibilidad)</label>
            <div class="selectOption">
                <select name="espacio" id="espacio" onchange="updateDate()" required>
                    <option value="">Seleccione un espacio</option>
                    <option value="Laboratorio 1">Laboratorio 1</option>
                    <option value="Laboratorio 2">Laboratorio 2</option>
                    <option value="Laboratorio 3">Laboratorio 3</option>
                    <option value="Aula PEC">Aula PEC (Requiere visto bueno de la dirección)</option>
                    <option value="Sala Interna">Sala Interna (Requiere visto bueno de la dirección)</option>
                    <option value="Sala Externa">Sala Externa</option>
                </select>
            </div>
            <br>
            <label for="sigla_curso">Sigla del curso</label>
            <input type="text" name="sigla_curso" id="sigla_curso"
                placeholder="Ingrese la sigla del curso (ejemplo: IC-0101)" oninput="searchCourse()">
            <label for="nombre_curso">Nombre del curso</label>
            <input type="text" name="nombre_curso" id="nombre_curso" placeholder="Ingrese la sigla del curso primero"
                readonly>
            <label for="solicitante">Nombre del solicitante</label>
            <input type="text" name="solicitante" id="solicitante" placeholder="Ingrese el nombre del solicitante"
                value="<?php echo $_SESSION['nombre']; ?>" required>
            <label for="fecha" class="control-label">Día</label>
            <input type="text" name="fecha" id="fecha" placeholder="Seleccione la hora de inicio" required
                onchange="updateDate()">
            <label for="hora_inicio" class="control-label">Hora de inicio</label>
            <input type="text" name="hora_inicio" id="hora_inicio" placeholder="Seleccione la hora de inicio" required
                onchange="updateTime('hora_inicio')">
            <div id="error_hora_inicio"></div>
            <label for="hora_fin" class="control-label">Hora de finalización</label>
            <input type="text" name="hora_fin" id="hora_fin" placeholder="Seleccione la hora de finalización" required
                onchange="updateTime('hora_fin')">
            <div id="error_hora_fin"></div>
            <div id="hora_fin_mayor"></div>
            <input type="submit" id="submit" value="Enviar solicitud" disabled>
        </form>

        <div class="center">
            <button id="btn5" onclick="location.href='inicio.php'">Cancelar</button>
        </div>

        <?php
        $schedules = $conn->query("SELECT * FROM `lista_horarios` WHERE estado = 'aprobado'");
        $sched_res = [];
        foreach ($schedules->fetch_all(MYSQLI_ASSOC) as $row) {
            $sched_res[$row['id']] = $row;
        }
        ?>
    </body>
    <script>
        let listaCursos = <?php echo json_encode($courses); ?>;
        let listaReservas = <?php echo json_encode($sched_res); ?>;
        let foundCourse = false;
        let validDate = false;
        let validStartTime = false;
        let validEndTime = false;
        let validStartEndTime = false

        // Flatpickr is a library to create date and time input fields
        minDate = new Date();
        flatpickr('#fecha', {
            position: "above",
            "minDate": minDate,
            "disable": [
                function (date) {
                    return (date.getDay() === 0); // Disable Sundays
                }
            ],
            "locale": {
                "firstDayOfWeek": 1 // Start week on Monday
            }
        });

        flatpickr('#hora_inicio', {
            position: "above",
            noCalendar: true,
            enableTime: true,
            minuteIncrement: 30
        });

        flatpickr('#hora_fin', {
            position: "above",
            noCalendar: true,
            enableTime: true,
            minuteIncrement: 30,
        });

        function checkFilled() {
            if (foundCourse == true && validDate == true && validStartTime == true && validEndTime == true && validStartEndTime == true) {
                document.getElementById('submit').disabled = false;
            } else {
                document.getElementById('submit').disabled = true;
            }
        }

        function searchCourse() {
            let p = document.getElementById('sigla_curso').selectionStart;
            document.getElementById('sigla_curso').value = document.getElementById('sigla_curso').value.toUpperCase();
            document.getElementById('sigla_curso').setSelectionRange(p, p);

            let curso = listaCursos.find((element) => element.sigla_curso == document.getElementById('sigla_curso').value);
            if (curso) {
                document.getElementById('nombre_curso').setAttribute("value", curso.nombre_curso);
                foundCourse = true;
            } else {
                document.getElementById('nombre_curso').setAttribute("value", "");
                foundCourse = false;
            }
            checkFilled();
        }

        function updateDate() {
            validDate = true;
            if (document.getElementById("hora_inicio").value != "") {
                updateTime("hora_inicio");
            }
            if (document.getElementById("hora_fin").value != "") {
                updateTime("hora_fin");
            }
        }

        function updateTime(input) {
            let selectedDate = document.getElementById("fecha").value + " " + document.getElementById(input).value + ":00";
            let conflictFound = false; // Variable to track conflicts

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
                                conflictFound = true; // Conflict found, set the flag to true
                                break; // Exit the loop early
                            }
                        } else {
                            if (selectedDate != startDateTime) {
                                document.getElementById("error_hora_fin").innerHTML = "<h5 style='color: red; margin-top: 0;'>Error: la fecha y hora elegida ya está reservada</h5>"
                                validEndTime = false;
                                conflictFound = true; // Conflict found, set the flag to true
                                break; // Exit the loop early
                            }
                        }
                    }
                }

                if (!conflictFound) {
                    document.getElementById("error_" + input).innerHTML = ""; // Clear the error message
                    if (input == "hora_inicio") {
                        validStartTime = true;
                    } else {
                        validEndTime = true;
                    }
                }

                if (document.getElementById("hora_fin").value != "" && document.getElementById("hora_inicio").value >= document.getElementById("hora_fin").value) {
                    document.getElementById("hora_fin_mayor").innerHTML = "<h5 style='color: red; margin-top: 0;'>Error: la hora de finalización no puede ser menor ni igual a la hora de inicio</h5>"
                    validStartEndTime = false;
                } else {
                    document.getElementById("hora_fin_mayor").innerHTML = ""; // Clear the error message
                    validStartEndTime = true;
                }

                // Code to use two date and time pickers instead
                /*
                if (input == "hora_inicio") {
                    flatpickr('#hora_fin', {
                        "minDate": new Date(document.getElementById("hora_inicio").value),
                        enableTime: true,
                        "disable": [
                            function (date) {
                                // return true to disable
                                return (date.getDay() === 0);
                            }
                        ],
                        "locale": {
                            "firstDayOfWeek": 1 // start week on Monday
                        }
                    });
                } else {
                    flatpickr('#hora_inicio', {
                        "minDate": minDate,
                        "maxDate": new Date(document.getElementById("hora_fin").value),
                        enableTime: true,
                        "disable": [
                            function (date) {
                                // return true to disable
                                return (date.getDay() === 0);
                            }
                        ],
                        "locale": {
                            "firstDayOfWeek": 1 // start week on Monday
                        }
                    });
                }
                */

            } else {
                document.getElementById("error_" + input).innerHTML = "<h5 style='color: red; margin-top: 0;'>Error: la fecha y hora elegida no está disponible para reservas</h5>";
                document.getElementById("hora_fin_mayor").innerHTML = "";
                if (input == "hora_inicio") {
                    validStartTime = false;
                } else {
                    validEndTime = false;
                }
            }

            checkFilled();
        }
    </script>

    </html>

    <?php
} else {
    header("Location: ../index.php");
    exit();
}
?>