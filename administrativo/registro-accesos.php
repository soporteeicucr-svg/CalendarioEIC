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
        <title>Registro de accesos a salas y espacios</title>
        <link rel="stylesheet" type="text/css" href="../shared/lib/bootstrap/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="../shared/css/reserva.css">
        <link rel="stylesheet" type="text/css" href="../shared/css/tabs.css">
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
            <div class="row">
                <div class="col-md-14">

                    <div class="text-center">
                        <h1>Registro de accesos a salas y espacios</h1>
                    </div>

                    <!-- Tab links -->
                    <div class="tab d-flex justify-content-center">
                        <button class="tablinks" onclick="openTab(event, 'Estudiantes')"
                            id="defaultOpen">Estudiantes</button>
                        <button class="tablinks" onclick="openTab(event, 'Docentes')">Docentes</button>
                    </div>

                    <!-- Tab content -->
                    <div id="Estudiantes" class="tabcontent">
                        <div id="tab Estudiantes">

                            <table class="table" style="margin-bottom: 0;">
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
                                    $query = "SELECT * FROM registro_estudiante ORDER BY id DESC";
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
                        </div>
                    </div>

                    <div id="Docentes" class="tabcontent">
                        <div id="tab Docentes">
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

                                <tbody>
                                    <?php
                                    $query = "SELECT * FROM registro_docente ORDER BY id DESC";
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <script>
        function openTab(evt, tabName) {
            // Declare all variables
            var i, tabcontent, tablinks;

            // Get all elements with class="tabcontent" and hide them
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }

            // Get all elements with class="tablinks" and remove the class "active"
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }

            // Show the current tab, and add an "active" class to the button that opened the tab
            document.getElementById(tabName).style.display = "block";
            evt.currentTarget.className += " active";
        }

        // Get the element with id="defaultOpen" and click on it
        document.getElementById("defaultOpen").click();
    </script>

    </html>

    <?php
} else {
    header("Location: index.php");
    exit();
}
?>