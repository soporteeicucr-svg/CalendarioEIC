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
        <title>Lista de estudiantes</title>
        <link rel="stylesheet" type="text/css" href="../shared/css/lista.css">
        <link rel="stylesheet" type="text/css" href="../shared/lib/bootstrap/bootstrap.min.css">
    </head>

    <body style="margin: 50px;">
        <div>
            <h1>Registro de accesos a laboratorios por estudiantes</h1>
            <button class="buttonHome" onclick="location.href='inicio.php'">Inicio</button>
        </div>
        <br>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Carnet</th>
                    <th>Teléfono</th>
                    <th>Laboratorio</th>
                    <th>Fecha y hora de ingreso</th>
                </tr>
            </thead>

            <tbody>
                <?php
                $query = "SELECT * FROM registro_estudiante";
                $result = $conn->query($query);
                while ($row = mysqli_fetch_array($result)) {
                    ?>
                    <tr>
                        <td>
                            <?php echo $row['id']; ?>
                        </td>
                        <td>
                            <?php echo $row['nombre']; ?>
                        </td>
                        <td>
                            <?php echo $row['correo']; ?>
                        </td>
                        <td>
                            <?php echo $row['carnet']; ?>
                        </td>
                        <td>
                            <?php echo $row['celular']; ?>
                        </td>
                        <td>
                            <?php echo $row['laboratorio']; ?>
                        </td>
                        <td>
                            <?php echo $row['fecha_ingreso']; ?>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </body>

    </html>

    <?php
} else {
    header("Location: index.php");
    exit();
}
?>