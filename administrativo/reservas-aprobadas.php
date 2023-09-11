<?php
include '../shared/utils/db-conn.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Lista Estudiantes</title>
    <link rel="stylesheet" type="text/css" href="../shared/css/lista.css">
    <link rel="stylesheet" type="text/css" href="../shared/css/bootstrap.min.css">
</head>

<body style="margin: 50px;">
    <div>
        <h1>Reservas aprobadas</h1>
        <button class="buttonHome" onclick="location.href='inicio.php'">Inicio</button>
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
</body>

</html>