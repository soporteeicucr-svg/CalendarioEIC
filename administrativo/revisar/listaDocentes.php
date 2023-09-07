<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista Estudiantes</title>
    <link rel="stylesheet" type="text/css" href="lista.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

</head>

<body style="margin: 50px;">
    <div>
        <h1>Registro de profesores</h1>
        <button onclick="location.href='home.php'">Inicio</button>
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
                <th>Solicitud HDMI</th>
                <th>Fecha y hora de ingreso</th>
            </tr>
        </thead>

        <tbody>
            <?php
            $servername = "localhost";
            $username = "root";
            $password = "";
            $database = "test";

            $connection = new mysqli($servername, $username, $password, $database);

            if ($connection->connect_error) {
                die("Connection failed: " . $connection->connect_error);
            }

            $sql = "SELECT * FROM registrodocente";
            $result = $connection->query($sql);

            if (!$result) {
                die("Invalid query: " . $connection->error);
            }

            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>" . $row["id"] . "</td>
                    <td>" . $row["nombre"] . "</td>
                    <td>" . $row["correo"] . "</td>
                    <td>" . $row["cedula"] . "</td>
                    <td>" . $row["celular"] . "</td>
                    <td>" . $row["siglaCurso"] . "</td>
                    <td>" . $row["espacio"] . "</td>
                    <td>" . $row["hdmi"] . "</td>
                    <td>" . $row["fechaIngreso"] . "</td>
                </tr>";
            }

            $connection->close();
            ?>
        </tbody>
    </table>
</body>

</html>