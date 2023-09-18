<?php
include '../shared/utils/db-conn.php';
include '../shared/utils/get-courses.php';

session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sistema Reserva EIC</title>
    <link rel="stylesheet" type="text/css" href="../shared/lib/fontawesome/css/all.css">
    <link rel="stylesheet" type="text/css" href="../shared/css/formulario.css">
    <!-- Componente de selección de fecha y hora -->
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
            <select name="espacio" id="espacio" required>
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
            placeholder="Ingrese la sigla del curso (ejemplo: IC0101)" oninput="searchCourse()">
        <label for="nombre_curso">Nombre del curso</label>
        <input type="text" name="nombre_curso" id="nombre_curso" placeholder="Ingrese la sigla del curso primero"
            readonly>
        <label for="solicitante">Nombre del solicitante</label>
        <input type="text" name="solicitante" id="solicitante" placeholder="Ingrese el nombre del solicitante"
            value="<?php echo $_SESSION['nombre']; ?>" required>
        <!--
        <label for="repeticion">Repetición reserva</label>
        <select name="repeticion" id="repeticion" class="form-select"
            aria-label="Default select example">
            <option selected>Seleccione la repetición</option>
            <option value="Solo por el día">Solo por el día</option>
            <option value="Semanal">Semanal</option>
            <option value="Mensual">Mensual</option>
            <option value="Semestral">Semestral</option>
        </select>
        -->
        <label for="hora_inicio" class="control-label">Inicio</label>
        <input type="text" name="hora_inicio" id="hora_inicio" placeholder="Seleccione la hora de inicio" required
            onchange="updateDatePickers('hora_inicio')">
        <div id="error_hora_inicio"></div>
        <label for="hora_fin" class="control-label">Finalización</label>
        <input type="text" name="hora_fin" id="hora_fin" placeholder="Seleccione la hora de finalización" required
            onchange="updateDatePickers('hora_fin')">
        <div id="error_hora_fin"></div>
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
    let validStartDateTime = false;
    let validEndDateTime = false;

    function checkFilled() {
        if (foundCourse == true && validStartDateTime == true && validEndDateTime == true) {
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
            checkFilled();
        } else {
            document.getElementById('nombre_curso').setAttribute("value", "");
            foundCourse = false;
        }
    }

    // flatpickr is a library to create date and time input fields
    minDate = new Date();
    flatpickr('#hora_inicio', {
        "minDate": minDate,
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

    flatpickr('#hora_fin', {
        "minDate": minDate,
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

    function updateDatePickers(inputDate) {
        let selectedDate = document.getElementById(inputDate).value + ":00";
        let conflictFound = false; // Variable to track conflicts

        for (i in listaReservas) {
            let startDateTime = listaReservas[i]["hora_inicio"];
            let endDateTime = listaReservas[i]["hora_fin"];
            //-----------------------------------------------------------------------------------------------------------
            if (selectedDate > startDateTime && selectedDate < endDateTime) { // TODO: Hora inicio puede ser igual a la hora de finalización de otra reserva, hora de fin puede ser igual a la hora de inicio de otra reserva
            //-----------------------------------------------------------------------------------------------------------
                document.getElementById("error_" + inputDate).innerHTML = "<h5 style='color: red; margin-top: 0;'>Error: la fecha y hora elegida ya está reservada</h5>"
                if (inputDate == "hora_inicio") {
                    validStartDateTime = false;
                } else {
                    validEndDateTime = false;
                }
                conflictFound = true; // Conflict found, set the flag to true
                break; // Exit the loop early
            }
        }

        if (!conflictFound) {
            document.getElementById("error_" + inputDate).innerHTML = ""; // Clear the error message
            if (inputDate == "hora_inicio") {
                validStartDateTime = true;
                checkFilled();
            } else {
                validEndDateTime = true;
                checkFilled();
            }
        }

        if (inputDate == "hora_inicio") {
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
    }
</script>

</html>