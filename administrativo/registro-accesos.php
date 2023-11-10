<!DOCTYPE html>
<html lang='es'>

<?php
include '../shared/utils/db-conn.php';

session_start();

if (isset($_SESSION['usuario']) && isset($_SESSION['tipo'])) {
    if ($_SESSION['tipo'] == 'administrativo') {
        ?>

        <head>
            <meta charset='UTF-8'>
            <meta http-equiv='X-UA-Compatible' content='IE=edge'>
            <title>Registro de accesos a salas y espacios</title>
            <link rel='stylesheet' type='text/css' href='../shared/lib/bootstrap/bootstrap.min.css'>
            <link rel='stylesheet' type='text/css' href='../shared/css/reserva.css'>
            <link rel='stylesheet' type='text/css' href='../shared/css/tabs.css'>
            <script src='../shared/lib/jquery/jquery-3.6.0.min.js'></script>
            <script src='../shared/lib/pdfmake/pdfmake.min.js'></script>
            <script src='../shared/lib/pdfmake/vfs_fonts.js'></script>
            <script src='../shared/lib/sheetjs/xlsx.full.min.js'></script>
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
            <div class="container-fluid py-3 tabSize" id="page-container">
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
                                    <input class="form-control"
                                        style="font-size: 16px; font-weight: bold; border: 4px solid rgb(0, 151, 255); padding: 12px"
                                        id="searchStudent" type="text" placeholder="Buscar...">
                                    <p class="h4 mb-0">Descargar:</p>
                                    <button type="button" class="btn download-excel-btn" id="exportToExcel"
                                        onclick="exportToExcel('tableStudents', 'searchStudent')">
                                    </button>
                                    <button type="button" class="btn download-pdf-btn" id="exportToPDF"
                                        onclick="exportToPDF('tableStudents', 'searchStudent')">
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
                                            <th>Fecha</th>
                                            <th>Hora</th>
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
                                                    <?php echo mb_convert_case(mb_strtolower($row['nombre'], 'UTF-8'), MB_CASE_TITLE); ?>
                                                </td>
                                                <td>
                                                    <?php echo mb_convert_case(mb_strtolower($row['correo'], 'UTF-8'), MB_CASE_LOWER); ?>
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
                                                    <?php echo explode(" ", $row['fecha_ingreso'])[0]; ?>
                                                </td>
                                                <td>
                                                    <?php echo explode(" ", $row['fecha_ingreso'])[1]; ?>
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
                                    <input class="form-control"
                                        style="font-size: 16px; font-weight: bold; border: 4px solid rgb(0, 151, 255); padding: 12px"
                                        id="searchProfessor" type="text" placeholder="Buscar...">
                                    <p class="h4 mb-0">Descargar:</p>
                                    <button type="button" class="btn download-excel-btn" id="exportToExcel"
                                        onclick="exportToExcel('tableProfessors', 'searchProfessor')">
                                    </button>
                                    <button type="button" class="btn download-pdf-btn" id="exportToPDF"
                                        onclick="exportToPDF('tableProfessors', 'searchProfessor')">
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
                                            <th>Sigla del curso</th>
                                            <th>Espacio reservado</th>
                                            <th>Fecha</th>
                                            <th>Hora</th>
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
                                                    <?php echo mb_convert_case(mb_strtolower($row['nombre'], 'UTF-8'), MB_CASE_TITLE); ?>
                                                </td>
                                                <td>
                                                    <?php echo mb_convert_case(mb_strtolower($row['correo'], 'UTF-8'), MB_CASE_LOWER); ?>
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
                                                    <?php echo explode(" ", $row['fecha_ingreso'])[0]; ?>
                                                </td>
                                                <td>
                                                    <?php echo explode(" ", $row['fecha_ingreso'])[1]; ?>
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

            // Función para cambiar de tab
            function openTab(evt, tabName) {
                // Declare all variables
                var i, tabcontent, tablinks;

                // Busca todos los elementos con class="tabcontent" y los esconde
                tabcontent = document.getElementsByClassName("tabcontent");
                for (i = 0; i < tabcontent.length; i++) {
                    tabcontent[i].style.display = "none";
                }

                // Busca todos los elementos con class="tablinks" y borra la class "active"
                tablinks = document.getElementsByClassName("tablinks");
                for (i = 0; i < tablinks.length; i++) {
                    tablinks[i].className = tablinks[i].className.replace(" active", "");
                }

                // Muestra la tab elegida y agrega la class "active" al botón
                document.getElementById(tabName).style.display = "block";
                evt.currentTarget.className += " active";
            }

            // Busca el elemento con id="defaultOpen" y lo abre al entrar a la página
            document.getElementById("defaultOpen").click();

            // JQuery para filtrar la tabla de estudiantes
            $(document).ready(function () {
                $("#searchStudent").on("keyup", function () {
                    var value = $(this).val().toLowerCase();
                    $("#tableStudentsBody tr").each(function () {
                        var match = $(this).text().toLowerCase().indexOf(value) > -1;

                        if (match) {
                            $(this).addClass("filter-row");
                        } else {
                            $(this).removeClass("filter-row");
                        }

                        $(this).toggle(match);
                    });
                });
            });

            // JQuery para filtrar la tabla de profesores
            $(document).ready(function () {
                $("#searchProfessor").on("keyup", function () {
                    var value = $(this).val().toLowerCase();
                    $("#tableProfessorsBody tr").each(function () {
                        var match = $(this).text().toLowerCase().indexOf(value) > -1;

                        if (match) {
                            $(this).addClass("filter-row");
                        } else {
                            $(this).removeClass("filter-row");
                        }

                        $(this).toggle(match);
                    });
                });
            });

            // Función para exportar a Excel
            function exportToExcel(tableName, searchBarName) {
                var table = document.getElementById(tableName);
                var ws = XLSX.utils.table_to_sheet(table, { raw: true });
                var wb = XLSX.utils.book_new();

                // Contenido de la barra de búsqueda para utilizarlo al nombrar el archivo
                var searchBar = document.getElementById(searchBarName);
                search = searchBar.value.trim();

                // Establece el nombre del archivo y lo descarga
                var tab = '';
                if (tableName == 'tableStudents') {
                    tab = 'estudiantes';
                } else {
                    tab = 'profesores';
                }
                if (search != '') {
                    XLSX.utils.book_append_sheet(wb, ws, 'Accesos de ' + tab);
                    XLSX.writeFile(wb, 'Lista de accesos de ' + tab + ' - ' + search + '.xlsx');
                } else {
                    XLSX.utils.book_append_sheet(wb, ws, 'Accesos de ' + tab);
                    XLSX.writeFile(wb, 'Lista de accesos de ' + tab + '.xlsx');
                }
            }

            // Función para exportar a PDF
            function exportToPDF(tableName, searchBarName) {
                var table = document.getElementById(tableName);
                var tableData = [];

                // Filtra las filas visibles utilizando "filter-row"
                var visibleRows = Array.from(table.getElementsByClassName('filter-row'));

                var tableHeader = table.querySelector('thead tr');

                // Obtiene los encabezados de las columnas
                if (tableHeader) {
                    var headerRow = Array.from(tableHeader.cells).map(function (cell) {
                        return cell.textContent;
                    });
                } else {
                    console.warn("Table header row not found.");
                    var headerRow = [];
                }

                // Convierte las filas visibles en una matriz
                var data = visibleRows.map(function (row) {
                    return Array.from(row.cells).map(function (cell) {
                        return cell.textContent;
                    });
                });

                var tableData = [headerRow].concat(data);

                //Configuración de la página
                var pageSize = 'A4';
                var docDefinition = {
                    content: [
                        {
                            layout: 'noBorders',
                            table: {
                                body: tableData
                            }
                        }
                    ],
                    pageOrientation: 'landscape',
                    pageSize: pageSize,
                    defaultStyle: {
                        fontSize: 10
                    },
                    pageMargins: [80, 40, 80, 40],
                };

                // Contenido de la barra de búsqueda para utilizarlo al nombrar el archivo
                var searchBar = document.getElementById(searchBarName);
                search = searchBar.value.trim();

                // Establece el nombre del archivo y lo descarga
                var tab = '';
                if (tableName == 'tableStudents') {
                    tab = 'estudiantes';
                } else {
                    tab = 'profesores';
                }
                if (search != '') {
                    pdfMake.createPdf(docDefinition).download('Lista de accesos de ' + tab + ' - ' + search + '.pdf');
                } else {
                    pdfMake.createPdf(docDefinition).download('Lista de accesos de ' + tab + '.pdf');
                }
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