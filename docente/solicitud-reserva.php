<?php
include '../shared/utils/db-conn.php'
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Reserva EIC</title>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
        integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
    <link rel="stylesheet" href="../shared/css/bootstrap.min.css">
    <link rel="stylesheet" href="../shared/fullcalendar/lib/main.min.css">
    <link rel="stylesheet" type="text/css" href="../shared/css/reserva.css">
    <script src="../shared/js/jquery-3.6.0.min.js"></script>
    <script src="../shared/js/bootstrap.min.js"></script>
    <script src="../shared/fullcalendar/lib/main.min.js"></script>
    <!-- Componente de selección de fecha y hora -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
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
                Escuela de Ingeniería Civil | Sistema de reserva
            </a>
            <div>
                <button class="buttonHome" onclick="location.href='inicio.php'">Inicio</button>
            </div>
        </div>
    </nav>
    <div class="container py-5" id="page-container" style="margin: auto; width: 50%; padding: 10px;">
        <div class="cardt rounded-0 shadow">
            <div class="card-header bg-gradient bg-primary text-light">
                <h5 class="card-title">Formulario de solicitud de reserva</h5>
            </div>
            <div class="card-body">
                <div class="container-fluid">
                    <form action="../shared/utils/save-schedule.php" method="post" id="schedule-form">
                        <input type="hidden" name="id" value="">
                        <div class="form-group mb-2">
                            <label for="espacio" class="control-label mb-1">Espacio a reservar</label>
                            <select name="espacio" id="espacio" class="form-select" aria-label="Default select example">
                                <option selected>Selección espacio</option>
                                <option value="Laboratorio 1">Laboratorio 1</option>
                                <option value="Laboratorio 2">Laboratorio 2</option>
                                <option value="Laboratorio 3">Laboratorio 3</option>
                                <option value="Aula PEC">Aula PEC (Requiere visto bueno de la dirección)</option>
                                <option value="Sala Interna">Sala Interna (Requiere visto bueno de la dirección)</option>
                                <option value="Sala Externa">Sala Externa</option>
                            </select>
                        </div>
                        <div class="form-group mb-2">
                            <label for="sigla_curso" class="control-label">Sigla del curso</label>
                            <input type="text" class="form-control form-control-sm rounded-0" name="sigla_curso"
                                id="sigla_curso" required>
                        </div>
                        <div class="form-group mb-2">
                            <label for="nombre_curso" class="control-label">Nombre del curso</label>
                            <input type="text" class="form-control form-control-sm rounded-0" name="nombre_curso"
                                id="nombre_curso" required>
                        </div>
                        <div class="form-group mb-2">
                            <label for="solicitante" class="control-label">Nombre solicitante</label>
                            <input type="text" class="form-control form-control-sm rounded-0" name="solicitante"
                                id="solicitante" required>
                        </div>
                        <!--
                        <div class="form-group mb-2">
                            <label for="repeticion" class="control-label mb-1">Repetición reserva</label>
                            <select name="repeticion" id="repeticion" class="form-select"
                                aria-label="Default select example">
                                <option selected>Seleccione la repetición</option>
                                <option value="Solo por el día">Solo por el día</option>
                                <option value="Semanal">Semanal</option>
                                <option value="Mensual">Mensual</option>
                                <option value="Semestral">Semestral</option>
                            </select>
                        </div>
                        -->
                        <div class="form-group mb-2">
                            <label for="hora_inicio" class="control-label">Inicio</label>
                            <input type="text" class="form-control form-control-sm rounded-0" name="hora_inicio"
                                id="hora_inicio" required onchange="updateDatePickers('hora_inicio')">
                        </div>
                        <div class="form-group mb-2">
                            <label for="hora_fin" class="control-label">Finalización</label>
                            <input type="text" class="form-control form-control-sm rounded-0" name="hora_fin"
                                id="hora_fin" required onchange="updateDatePickers('hora_fin')">
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-footer">
                <div class="text-center">
                    <button class="btn btn-primary btn-sm rounded-0" type="submit" form="schedule-form"> Enviar
                        solicitud</button>
                </div>
            </div>
        </div>
    </div>
    <?php
    $schedules = $conn->query("SELECT * FROM `lista_horarios` WHERE estado = 'aprobado'");
    $sched_res = [];
    foreach ($schedules->fetch_all(MYSQLI_ASSOC) as $row) {
        $row['sdate'] = date("F d, Y h:i A", strtotime($row['hora_inicio']));
        $row['edate'] = date("F d, Y h:i A", strtotime($row['hora_fin']));
        $sched_res[$row['id']] = $row;
    }

    if (isset($conn)) {
        $conn->close();
    }
    ?>
</body>
<script>
    // flatpickr es una biblioteca para crear inputs de selección de fecha y hora
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