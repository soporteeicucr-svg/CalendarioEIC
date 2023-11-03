<?php
include '../shared/utils/db-conn.php';

session_start();

if (isset($_SESSION['usuario']) && isset($_SESSION['tipo'])) {
    if ($_SESSION['tipo'] == 'administrativo') {
        ?>

        <!DOCTYPE html>
        <html lang="es">

        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <title>Registro de accesos a salas y espacios</title>
            <link rel="stylesheet" type="text/css" href="../shared/lib/bootstrap/bootstrap.min.css">
            <link rel="stylesheet" type="text/css" href="../shared/lib/fontawesome/css/all.css">
            <link rel="stylesheet" type="text/css" href="../shared/css/reserva.css">
            <link rel="stylesheet" type="text/css" href="../shared/css/tabs.css">
            <script src="../shared/lib/jquery/jquery-3.6.0.min.js"></script>
            <script src="../shared/lib/sheetjs/xlsx.full.min.js"></script>
            <script src="../shared/lib/pdfmake/pdfmake.min.js"></script>
            <script src="../shared/lib/pdfmake/vfs_fonts.js"></script>
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

                        <!-- Tabs -->
                        <div class="tab d-flex justify-content-center">
                            <button class="tablinks" onclick="openTab(event, 'Estudiantes')"
                                id="defaultOpen">Estudiantes</button>
                            <button class="tablinks" onclick="openTab(event, 'Docentes')">Docentes</button>
                        </div>

                        <!-- Contenido de las tabs -->
                        <div id="Estudiantes" class="tabcontent">
                            <div id="tab Estudiantes">

                                <div class="d-flex align-items-center justify-content-center mb-1 gap-3">
                                    <input class="form-control" style="background-color: #f1f1f1" id="searchStudent" type="text" placeholder="Buscar...">
                                    <p class="h4 mb-0">Descargar:</p>
                                    <button type="button" class="btn download-excel-btn" id="exportToExcel"
                                        onclick="exportToExcel('tableStudents')">
                                    </button>
                                    <button type="button" class="btn download-pdf-btn" id="exportToPDF"
                                        onclick="exportToPDF('tableStudents')">
                                    </button>
                                </div>

                                <table class="table" id="tableStudents" style="margin-bottom: 0;">
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

                                    <tbody id="tableStudentsBody">
                                        <?php
                                        $query = "SELECT * FROM registro_estudiante ORDER BY id DESC";
                                        $result = $conn->query($query);
                                        while ($row = mysqli_fetch_array($result)) {
                                            ?>
                                            <tr class="filter-row">
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

                                <div class="d-flex align-items-center justify-content-center mb-1 gap-3">
                                    <input class="form-control" id="searchProfessor" type="text" placeholder="Buscar...">
                                    <p class="h4 mb-0">Descargar:</p>
                                    <button type="button" class="btn download-excel-btn" id="exportToExcel"
                                        onclick="exportToExcel('tableProfessors')">
                                    </button>
                                    <button type="button" class="btn download-pdf-btn" id="exportToPDF"
                                        onclick="exportToPDF('tableProfessors')">
                                    </button>
                                </div>

                                <table class="table" id="tableProfessors" style="margin-bottom: 0;">
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

                                    <tbody id="tableProfessorsBody">
                                        <?php
                                        $query = "SELECT * FROM registro_docente ORDER BY id DESC";
                                        $result = $conn->query($query);
                                        while ($row = mysqli_fetch_array($result)) {
                                            ?>
                                            <tr class="filter-row">
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

            $(document).ready(function () {
                $("#searchStudent").on("keyup", function () {
                    var value = $(this).val().toLowerCase();
                    $("#tableStudentsBody tr").each(function () {
                        // Check if the row matches the filter criteria
                        var match = $(this).text().toLowerCase().indexOf(value) > -1;

                        // Add or remove the "filter-row" class based on the match
                        if (match) {
                            $(this).addClass("filter-row");
                        } else {
                            $(this).removeClass("filter-row");
                        }

                        $(this).toggle(match);
                    });
                });
            });
            $(document).ready(function () {
                $("#searchProfessor").on("keyup", function () {
                    var value = $(this).val().toLowerCase();
                    $("#tableProfessorsBody tr").each(function () {
                        // Check if the row matches the filter criteria
                        var match = $(this).text().toLowerCase().indexOf(value) > -1;

                        // Add or remove the "filter-row" class based on the match
                        if (match) {
                            $(this).addClass("filter-row");
                        } else {
                            $(this).removeClass("filter-row");
                        }

                        $(this).toggle(match);
                    });
                });
            });

            // Add JavaScript to handle exporting to Excel
            function exportToExcel(tableName) {
                var table = document.getElementById(tableName);
                var ws = XLSX.utils.table_to_sheet(table);
                var wb = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(wb, ws, 'Lista de accesos');
                XLSX.writeFile(wb, 'Lista de accesos.xlsx');
            }

            // Add JavaScript to handle exporting to PDF
            function exportToPDF(tableName) {
                var table = document.getElementById(tableName);
                var tableData = [];

                // Filter the visible rows with class "filter-row"
                var visibleRows = Array.from(table.getElementsByClassName('filter-row'));

                // Extract the table header row
                var tableHeader = table.querySelector('thead tr');

                if (tableHeader) {
                    var headerRow = Array.from(tableHeader.cells).map(function (cell) {
                        return cell.textContent;
                    });
                } else {
                    console.warn("Table header row not found.");
                    var headerRow = [];
                }

                // Convert the visible rows to an array of arrays (rows and cells)
                var data = visibleRows.map(function (row) {
                    return Array.from(row.cells).map(function (cell) {
                        return cell.textContent;
                    });
                });

                // Combine header and data arrays
                var tableData = [headerRow].concat(data);

                // Calculate page size based on the table content
                var pageSize = 'A4'; // Default page size

                var docDefinition = {
                    content: [
                        {
                            layout: 'noBorders',
                            table: {
                                body: tableData
                            }
                        }
                    ],
                    pageOrientation: 'landscape', // Use landscape orientation
                    pageSize: pageSize, // Set the calculated page size
                    defaultStyle: {
                        fontSize: 10 // Set the font size (adjust as needed)
                    },
                    pageMargins: [80, 40, 80, 40], // Adjust page margins for centering
                };

                pdfMake.createPdf(docDefinition).download('Lista de accesos.pdf');
            }
        </script>

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