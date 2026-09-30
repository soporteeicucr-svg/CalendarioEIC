<!DOCTYPE html>
<html lang='es'>

<?php
session_start();

if (isset($_SESSION['usuario']) && isset($_SESSION['tipo'])) {
    if ($_SESSION['tipo'] == 'docente') {
        ?>

        <head>
            <meta charset='UTF-8'>
            <meta http-equiv='X-UA-Compatible' content='ie=edge'>
            <title>Calendario EIC</title>
            <link rel='stylesheet' type='text/css' href='../shared/lib/bootstrap/bootstrap.min.css'>
            <link rel='stylesheet' type='text/css' href='../shared/css/home.css'>
            <script src='../shared/lib/jquery/jquery-3.6.0.min.js'></script>
            <script src='../shared/lib/bootstrap/bootstrap.min.js'></script>
        </head>

        <body>
            <div>
                <div class="center">
                    <img src="../shared/img/EIC.png" alt="Logo Escuela de Ingeniería Civil UCR">
                    <h1>Sistema Registro Laboratorios</h1>
                </div>

                <!-- Botones con bootstrap cards -->
                <div class="container-fluid text-center">
                    <div class="row d-flex justify-content-center">

                        <!-- Card de registro -->
                        <div class="col-md-3">
                            <div class="card w-100 h-100">
                                <div class="card-header">
                                    <h3 class="mb-0">Registro</h3>
                                </div>
                                <div class="card-body card-body d-flex justify-content-center align-content-between flex-wrap">
                                    <div class="w-100">
                                        <h4 class="card-title">Registro y retiro de cartuchera para espacio reservado
                                        </h4>
                                    </div>
                                    <img class="img-fluid" width="50%" src="../shared/img/llave.png" alt="Llave">
                                </div>
                                <div class="card-footer">
                                    <a href="registro-docente.php" class="btn btn-primary">Realizar
                                        registro</a>
                                </div>
                            </div>
                        </div>

                        <!-- Card de calendario -->
                        <div class="col-md-3">
                            <div class="card w-100 h-100">
                                <div class="card-header">
                                    <h3 class="mb-0">Calendario</h3>
                                </div>
                                <div class="card-body card-body d-flex justify-content-center align-content-between flex-wrap">
                                    <div class="w-100">
                                        <h4 class="card-title">Calendario de reservaciones</h4>
                                    </div>
                                    <img class="img-fluid" width="50%" src="../shared/img/calendario.png" alt="Calendario">
                                </div>
                                <div class="card-footer">
                                    <a href="calendario-reserva.php" class="btn btn-primary">Ver calendario</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row d-flex justify-content-center spacingLogout">
                        <div class="center">
                            <button id="btn3" onclick="location.href='../shared/cerrar-sesion.php'"
                                class="btn btn-danger">Cerrar sesión</button>
                        </div>
                    </div>
                </div>

            </div>
        </body>

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