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
        <title>Revisión Reserva</title>
        <link rel="stylesheet" type="text/css" href="../shared/css/lista.css">
        <link rel="stylesheet" type="text/css" href="../shared/lib/bootstrap/bootstrap.min.css">
    </head>

    <body style="margin: 50px;">
        <div>
            <h1>Solicitudes de reserva</h1>
            <button class="buttonHome" onclick="location.href='inicio.php'">Inicio</button>
        </div>
        <br>
        <table class="table">
            <thead>
                <tr>
                    <th>Id</th>
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
                $query = "SELECT * FROM `lista_horarios` WHERE estado = 'pendiente'";
                $result = mysqli_query($conn, $query);
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
                        <td>
                            <form action="administrar-solicitudes.php" method="POST" style="">
                                <input type="hidden" name="id" id="<?php echo $row['id']; ?>"
                                    value="<?php echo $row['id']; ?>" />
                                <input type="submit" name="aprobar" value="Aprobar" />
                                <input type="submit" name="rechazar" value="Rechazar" />
                            </form>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </body>

    <?php
    if (isset($_POST['aprobar'])) {
        $id = $_POST['id'];

        $select = "UPDATE `lista_horarios` SET estado = 'aprobado' WHERE id = '$id'";
        $result = mysqli_query($conn, $select);

        echo '<script type = "text/javascript">';
        echo 'alert("Reserva aprobada");';
        echo 'window.location.href = "administrar-solicitudes.php";';
        echo '</script>';
    }

    if (isset($_POST['rechazar'])) {
        $id = $_POST['id'];

        $select = "UPDATE `lista_horarios` SET estado = 'rechazado' WHERE id = '$id'";
        $result = mysqli_query($conn, $select);

        echo '<script type = "text/javascript">';
        echo 'alert("Reserva rechazada");';
        echo 'window.location.href = "administrar-solicitudes.php";';
        echo '</script>';
    }
    ?>

    </html>

    <?php
} else {
    header("Location: ../index.php");
    exit();
}
?>