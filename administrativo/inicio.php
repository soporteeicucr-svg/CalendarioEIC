<!DOCTYPE html>
<html lang='es'>

<?php
session_start();

if (isset($_SESSION['usuario']) && isset($_SESSION['tipo'])) {
    if ($_SESSION['tipo'] == 'administrativo') {
        ?>

        <head>
            <meta charset='UTF-8'>
            <meta http-equiv='X-UA-Compatible' content='ie=edge'>
            <title>Sistema de reservas administrativo</title>
            <link rel='stylesheet' type='text/css' href='../shared/lib/bootstrap/bootstrap.min.css'>
            <link rel='stylesheet' type='text/css' href='../shared/css/home.css'>
            <script src='../shared/lib/jquery/jquery-3.6.0.min.js'></script>
            <script src='../shared/lib/bootstrap/bootstrap.min.js'></script>
        </head>

        <body>
            <div>
                <div class="center">
                    <img src="../shared/img/logoEIC.png" alt="Logo Escuela de Ingeniería Civil UCR">
                    <h1>Sistema administrativo de reserva de espacios</h1>
                </div>

                <!-- Botones con bootstrap cards -->
                <div class="container-fluid text-center">
                    <div class="row d-flex justify-content-center px-5">

                        <!-- Card de lista de registros -->
                        <div class="col-md-3">
                            <div class="card w-100 h-100">
                                <div class="card-header">
                                    <h3 class="mb-0">Lista de registros</h3>
                                </div>
                                <div class="card-body card-body d-flex justify-content-center align-content-between flex-wrap">
                                    <div class="w-100">
                                        <h4 class="card-title">Lista de registros para uso de salas
                                        </h4>
                                    </div>
                                    <img class="img-fluid" width="50%" src="../shared/img/llave.png" alt="Llave">
                                </div>
                                <div class="card-footer">
                                    <a href="registro-accesos.php" class="btn btn-primary">Ver lista</a>
                                </div>
                            </div>
                        </div>

                        <!-- Card de solicitudes de reserva -->
                        <div class="col-md-3">
                            <div class="card w-100 h-100">
                                <div class="card-header">
                                    <h3 class="mb-0">Solicitudes de reserva</h3>
                                </div>
                                <div class="card-body card-body d-flex justify-content-center align-content-between flex-wrap">
                                    <div class="w-100">
                                        <h4 class="card-title">Solicitudes de reserva de espacio</h4>
                                    </div>
                                    <img class="img-fluid" width="50%" src="../shared/img/formulario.png" alt="Formulario">
                                </div>
                                <div class="card-footer">
                                    <a href="administrar-solicitudes.php" class="btn btn-primary">Administrar solicitudes</a>
                                </div>
                            </div>
                        </div>

                        <!-- Card de creación de reserva -->
                        <div class="col-md-3">
                            <div class="card w-100 h-100">
                                <div class="card-header">
                                    <h3 class="mb-0">Creación de reserva</h3>
                                </div>
                                <div class="card-body card-body d-flex justify-content-center align-content-between flex-wrap">
                                    <div class="w-100">
                                        <h4 class="card-title">Creación de reserva de espacio</h4>
                                    </div>
                                    <img class="img-fluid" width="50%" src="../shared/img/mas.png" alt="Formulario">
                                </div>
                                <div class="card-footer">
                                    <a href="crear-reserva.php" class="btn btn-primary">Realizar reservación</a>
                                </div>
                            </div>
                        </div>

                        <!-- Card de visualizar reservas -->
                        <div class="col-md-3">
                            <div class="card w-100 h-100">
                                <div class="card-header">
                                    <h3 class="mb-0">Visualizar reservas</h3>
                                </div>
                                <div class="card-body card-body d-flex justify-content-center align-content-between flex-wrap">
                                    <div class="w-100">
                                        <h4 class="card-title">Calendario de reservaciones aprobadas</h4>
                                    </div>
                                    <img class="img-fluid" width="50%" src="../shared/img/calendario.png" alt="Calendario">
                                </div>
                                <div class="card-footer">
                                    <a href="calendario-reserva.php" class="btn btn-primary">Ver calendario</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row d-flex justify-content-center">
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
        header("Location: index.php");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
?>