<?php
include '../shared/utils/db-conn.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de docentes</title>
    <link rel="stylesheet" type="text/css" href="../shared/css/lista.css">
    <link rel="stylesheet" type="text/css" href="../shared/css/bootstrap.min.css">
</head>

<body style="margin: 50px;">
    <div>
        <h1>Registro de accesos a laboratorios por docentes</h1>
        <button class="buttonHome" onclick="location.href='inicio.php'">Inicio</button>
    </div>
    <br>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Cédula</th>
                <th>Teléfono</th>
                <th>Sigla curso</th>
                <th>Espacio reservado</th>
                <th>Fecha y hora de ingreso</th>
            </tr>
        </thead>

        <?php
            $query = "SELECT * FROM registro_docente";
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
                        <?php echo $row['cedula']; ?>
                    </td>
                    <td>
                        <?php echo $row['celular']; ?>
                    </td>
                    <td>
                        <?php echo $row['sigla_curso']; ?>
                    </td>
                    <td>
                        <?php echo $row['espacio']; ?>
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