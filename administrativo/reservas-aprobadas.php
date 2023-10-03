<?php
include '../shared/utils/db-conn.php';

session_start();

if (isset($_SESSION['id']) && isset($_SESSION['usuario'])) {
    ?>

    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Reservaciones aprobadas</title>
        <link rel="stylesheet" type="text/css" href="../shared/lib/bootstrap/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="../shared/css/reserva.css">
        <style>
            :root {
                --bs-success-rgb: 71, 222, 152 !important;
            }
        </style>
    </head>

    <body class="bg-light">
        <nav class="navbar navbar-expand-lg navbar-dark bg-gradient bg-primary" id="topNavBar">
            <div class="container">
                <a class="navbar-brand">
                    Escuela de Ingeniería Civil
                </a>
                <div>
                    <button class="buttonHome" onclick="location.href='inicio.php'">Inicio</button>
                </div>
            </div>
        </nav>
        <div class="container py-3" id="page-container">
            <div class="text-center">
                <h1>Reservaciones aprobadas</h1>
            </div>
            <br>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Sala/Aula</th>
                        <th>Sigla</th>
                        <th>Curso</th>
                        <th>Solicitante</th>
                        <th>Inicio</th>
                        <th>Finalización</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    $query = "SELECT * FROM `lista_horarios` WHERE estado = 'aprobado'";
                    $result = $conn->query($query);
                    while ($row = mysqli_fetch_array($result)) {
                        ?>
                        <tr>
                            <td>
                                <?php echo $row['id']; ?>
                            </td>
                            <td>
                                <?php echo $row['espacio']; ?>
                            </td>
                            <td>
                                <?php echo $row['sigla_curso']; ?>
                            </td>
                            <td>
                                <?php echo $row['nombre_curso']; ?>
                            </td>
                            <td>
                                <?php echo $row['solicitante']; ?>
                            </td>
                            <td>
                                <?php echo $row['hora_inicio']; ?>
                            </td>
                            <td>
                                <?php echo $row['hora_fin']; ?>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </body>

    </html>

    <?php
} else {
    header("Location: index.php");
    exit();
}
?>