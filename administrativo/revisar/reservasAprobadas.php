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
        <h1>Reservas aprobadas</h1>
        <button onclick="location.href='home.php'">Inicio</button>
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
                <th>Repetición</th>
                <th>Inicio</th>
                <th>Finalización</th>
            </tr>
        </thead>

        <tbody>
            <?php
            $servername = "localhost";
            $username = "root";
            $password = "";
            $database = "dummy_db";

            $connection = new mysqli($servername, $username, $password, $database);

            if ($connection->connect_error) {
                die("Connection failed: " . $connection->connect_error);
            }

            $sql = "SELECT * FROM `schedule_list` WHERE status = 'aprobado'";
            $result = $connection->query($sql);

            if (!$result) {
                die("Invalid query: " . $connection->error);
            }

            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>" . $row["id"] . "</td>
                    <td>" . $row["title"] . "</td>
                    <td>" . $row["description"] . "</td>
                    <td>" . $row["curso"] . "</td>
                    <td>" . $row["solicitante"] . "</td>
                    <td>" . $row["repeticion"] . "</td>
                    <td>" . $row["start_datetime"] . "</td>
                    <td>" . $row["end_datetime"] . "</td>
                </tr>";
            }

            $connection->close();
            ?>
        </tbody>
    </table>
</body>

</html>