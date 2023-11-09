<?php
include '../shared/utils/db-conn.php';

session_start();

$schedules = [];

if (isset($_SESSION['usuario']) && isset($_SESSION['tipo'])) {
    if ($_SESSION['tipo'] == 'administrativo') {
        ?>

        <!DOCTYPE html>
        <html lang='es'>

        <head>
            <meta charset='UTF-8'>
            <meta http-equiv='X-UA-Compatible' content='IE=edge'>
            <title>Administración de solicitudes de reserva</title>
            <link rel='stylesheet' type='text/css' href='../shared/lib/alertify/css/alertify.css'>
            <link rel='stylesheet' type='text/css' href='../shared/lib/alertify/css/themes/bootstrap.css'>
            <link rel='stylesheet' type='text/css' href='../shared/lib/bootstrap/bootstrap.min.css'>
            <link rel='stylesheet' type='text/css' href='../shared/css/reserva.css'>
            <link rel='stylesheet' type='text/css' href='../shared/css/tabs.css'>
            <script src='../shared/lib/alertify/alertify.min.js'></script>
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
                            <h1>Solicitudes de reserva</h1>
                        </div>

                        <!-- Tabs -->
                        <div class="tab d-flex justify-content-center">
                            <button class="tablinks" onclick="openTab(event, 'Pendientes')" id="defaultOpen">Pendientes</button>
                            <button class="tablinks" onclick="openTab(event, 'Aprobadas')">Aprobadas</button>
                            <button class="tablinks" onclick="openTab(event, 'Rechazadas')">Rechazadas</button>
                        </div>

                        <!-- Contenido de las tabs -->
                        <!-- Solicitudes pendientes de revisión -->
                        <div id="Pendientes" class="tabcontent">
                            <div id="tab Pendientes">

                                <div class="d-flex align-items-center justify-content-center mb-1 gap-3">
                                    <input class="form-control"
                                        style="font-size: 16px; font-weight: bold; border: 4px solid rgb(0, 151, 255); padding: 12px"
                                        id="searchPending" type="text" placeholder="Buscar...">
                                    <p class="h4 mb-0">Descargar:</p>
                                    <button type="button" class="btn download-excel-btn" id="exportToExcel"
                                        onclick="exportToExcel('tablePending', 'searchPending')">
                                    </button>
                                    <button type="button" class="btn download-pdf-btn" id="exportToPDF"
                                        onclick="exportToPDF('tablePending', 'searchPending')">
                                    </button>
                                </div>

                                <table class="table" id="tablePending" style="margin-bottom: 0;">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Sala/Aula</th>
                                            <th>Sigla</th>
                                            <th>Curso</th>
                                            <th>Solicitante</th>
                                            <th>Correo</th>
                                            <th>Inicio</th>
                                            <th>Finalización</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>

                                    <tbody id="tablePendingBody">
                                        <?php
                                        $query = "SELECT * FROM `lista_horarios` WHERE estado = 'pendiente' ORDER BY id DESC";
                                        $result = mysqli_query($conn, $query);
                                        while ($row = mysqli_fetch_array($result)) {
                                            $schedules[$row['id']] = $row;
                                            ?>
                                            <tr class="filter-row">
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
                                                    <?php echo mb_convert_case(mb_strtolower($row['solicitante'], 'UTF-8'), MB_CASE_TITLE); ?>
                                                </td>
                                                <td>
                                                    <?php echo mb_convert_case(mb_strtolower($row['correo'], 'UTF-8'), MB_CASE_LOWER); ?>
                                                </td>
                                                <td>
                                                    <?php echo $row['hora_inicio']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $row['hora_fin']; ?>
                                                </td>
                                                <td class="d-flex align-items-center justify-content-center">
                                                    <form action="../shared/utils/manage-schedule.php" method="POST">
                                                        <input type="hidden" name="id" id="<?php echo $row['id']; ?>"
                                                            value="<?php echo $row['id']; ?>" />
                                                        <input type="hidden" name="action" value="aprobar" />
                                                        <input type="button" value="Aprobar" onclick="sendForm(this.form);" />
                                                    </form>
                                                    <form action="../shared/utils/manage-schedule.php" method="POST">
                                                        <input type="hidden" name="id" id="<?php echo $row['id']; ?>"
                                                            value="<?php echo $row['id']; ?>" />
                                                        <input type="hidden" name="action" value="rechazar" />
                                                        <input type="button" value="Rechazar" onclick="sendForm(this.form);" />
                                                    </form>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Solicitudes aprobadas -->
                        <div id="Aprobadas" class="tabcontent">
                            <div id="tab Aprobadas">

                                <div class="d-flex align-items-center justify-content-center mb-1 gap-3">
                                    <input class="form-control"
                                        style="font-size: 16px; font-weight: bold; border: 4px solid rgb(0, 151, 255); padding: 12px"
                                        id="searchApproved" type="text" placeholder="Buscar...">
                                    <p class="h4 mb-0">Descargar:</p>
                                    <button type="button" class="btn download-excel-btn" id="exportToExcel"
                                        onclick="exportToExcel('tableApproved', 'searchApproved')">
                                    </button>
                                    <button type="button" class="btn download-pdf-btn" id="exportToPDF"
                                        onclick="exportToPDF('tableApproved', 'searchApproved')">
                                    </button>
                                </div>

                                <table class="table" id="tableApproved" style="margin-bottom: 0;">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Sala/Aula</th>
                                            <th>Sigla</th>
                                            <th>Curso</th>
                                            <th>Solicitante</th>
                                            <th>Correo</th>
                                            <th>Inicio</th>
                                            <th>Finalización</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>

                                    <tbody id="tableApprovedBody">
                                        <?php
                                        $query = "SELECT * FROM `lista_horarios` WHERE estado = 'aprobado' ORDER BY id DESC";
                                        $result = $conn->query($query);
                                        while ($row = mysqli_fetch_array($result)) {
                                            $schedules[$row['id']] = $row;
                                            ?>
                                            <tr class="filter-row">
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
                                                    <?php echo mb_convert_case(mb_strtolower($row['solicitante'], 'UTF-8'), MB_CASE_TITLE); ?>
                                                </td>
                                                <td>
                                                    <?php echo mb_convert_case(mb_strtolower($row['correo'], 'UTF-8'), MB_CASE_LOWER); ?>
                                                </td>
                                                <td>
                                                    <?php echo $row['hora_inicio']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $row['hora_fin']; ?>
                                                </td>
                                                <td class="d-flex align-items-center justify-content-center">
                                                    <form action="../shared/utils/manage-schedule.php" method="POST">
                                                        <input type="hidden" name="id" id="<?php echo $row['id']; ?>"
                                                            value="<?php echo $row['id']; ?>" />
                                                        <input type="hidden" name="action" value="cancelar" />
                                                        <input type="button" value="Cancelar" onclick="sendForm(this.form);" />
                                                    </form>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Solicitudes rechazadas -->
                        <div id="Rechazadas" class="tabcontent">
                            <div id="tab Rechazadas">

                                <div class="d-flex align-items-center justify-content-center mb-1 gap-3">
                                    <input class="form-control"
                                        style="font-size: 16px; font-weight: bold; border: 4px solid rgb(0, 151, 255); padding: 12px"
                                        id="searchRejected" type="text" placeholder="Buscar...">
                                    <p class="h4 mb-0">Descargar:</p>
                                    <button type="button" class="btn download-excel-btn" id="exportToExcel"
                                        onclick="exportToExcel('tableRejected', 'searchRejected')">
                                    </button>
                                    <button type="button" class="btn download-pdf-btn" id="exportToPDF"
                                        onclick="exportToPDF('tableRejected', 'searchRejected')">
                                    </button>
                                </div>

                                <table class="table" id="tableRejected" style="margin-bottom: 0;">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Sala/Aula</th>
                                            <th>Sigla</th>
                                            <th>Curso</th>
                                            <th>Solicitante</th>
                                            <th>Correo</th>
                                            <th>Inicio</th>
                                            <th>Finalización</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tableRejectedBody">
                                        <?php
                                        $query = "SELECT * FROM `lista_horarios` WHERE estado = 'rechazado' ORDER BY id DESC";
                                        $result = $conn->query($query);
                                        while ($row = mysqli_fetch_array($result)) {
                                            $schedules[$row['id']] = $row;
                                            ?>
                                            <tr class="filter-row">
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
                                                    <?php echo mb_convert_case(mb_strtolower($row['solicitante'], 'UTF-8'), MB_CASE_TITLE); ?>
                                                </td>
                                                <td>
                                                    <?php echo mb_convert_case(mb_strtolower($row['correo'], 'UTF-8'), MB_CASE_LOWER); ?>
                                                </td>
                                                <td>
                                                    <?php echo $row['hora_inicio']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $row['hora_fin']; ?>
                                                </td>
                                                <td class="d-flex align-items-center justify-content-center">
                                                    <form action="../shared/utils/manage-schedule.php" method="POST">
                                                        <input type="hidden" name="id" id="<?php echo $row['id']; ?>"
                                                            value="<?php echo $row['id']; ?>" />
                                                        <input type="hidden" name="action" value="aprobar" />
                                                        <input type="button" value="Aprobar" onclick="sendForm(this.form);" />
                                                    </form>
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
            let activeTab = "";

            // Función para cambiar de tab
            function openTab(evt, tabName) {
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

                // Establece la tab elegida como tab activa para manejo interno
                activeTab = tabName;
            }

            // JQuery para filtrar la tabla de solicitudes pendientes
            $(document).ready(function () {
                $("#searchPending").on("keyup", function () {
                    var value = $(this).val().toLowerCase();
                    $("#tablePendingBody tr").each(function () {
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

            // JQuery para filtrar la tabla de solicitudes aprobadas
            $(document).ready(function () {
                $("#searchApproved").on("keyup", function () {
                    var value = $(this).val().toLowerCase();
                    $("#tableApprovedBody tr").each(function () {
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

            // JQuery para filtrar la tabla de solicitudes rechazadas
            $(document).ready(function () {
                $("#searchRejected").on("keyup", function () {
                    var value = $(this).val().toLowerCase();
                    $("#tableRejectedBody tr").each(function () {
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

            // Lista con todas las reservaciones, utilizada para comprobación de choques de horarios
            var scheds = $.parseJSON('<?= json_encode($schedules) ?>');

            // Función para enviar la información al servidor
            function sendForm(form) {
                var schedule = scheds[form["id"].value]; // Reservación elegida
                var overlapping = [];

                // Busca si otro horario pendiente o ya aprobado choca con la reservación elegida
                for (i in scheds) {
                    if (schedule['hora_inicio'] < scheds[i]['hora_fin'] && schedule['hora_fin'] > scheds[i]['hora_inicio'] && schedule['espacio'] == scheds[i]['espacio'] && schedule['id'] != scheds[i]['id'] && scheds[i]['estado'] != "rechazado") {
                        overlapping.push(scheds[i]);
                    }
                };

                // Verbos para mostrar en mensajes
                let action = "";
                let result = "";
                if (form["action"].value == "aprobar") {
                    action = "aprobará";
                    result = "aprobada"
                } else if (form["action"].value == "cancelar") {
                    action = "cancelará";
                    result = "cancelada"
                } else if (form["action"].value == "rechazar") {
                    action = "rechazará";
                    result = "rechazada"
                }

                if (overlapping.length == 0) { // Caso de que ninguna otra reserva choque con la elegida
                    alertify.confirm('Mensaje',
                        'Esto ' + action + ' la reservación seleccionada, ¿desea continuar?',
                        function () {
                            var style = document.createElement("style");
                            style.textContent = ".ajs-cancel { display: none; }";
                            document.head.appendChild(style);
                            setTimeout(function () {
                                alertify.confirm('Mensaje',
                                    'La reservación ha sido ' + result,
                                    function () { form.submit() },
                                    function () { }
                                ).set({ transition: "zoom", closable: false, labels: { ok: 'Aceptar', cancel: 'Cancelar' } }).autoOk(5);
                            }, 100);
                        },
                        function () { }
                    ).set({ transition: "zoom", closable: false, labels: { ok: 'Si', cancel: 'No' } });
                } else if (overlapping.length == 1) { // Caso de que una reserva choque
                    if (activeTab == "Pendientes") {
                        result = "rechazada"
                    } else if (activeTab == "Rechazadas") {
                        result = "cancelada"
                    }
                    alertify.confirm('Mensaje',
                        'La reservación con ID ' + overlapping[0]['id'] + ' entre las horas ' + overlapping[0]['hora_inicio'] + ' y ' + overlapping[0]['hora_fin'] + ' será ' + result + ', ¿desea continuar?',
                        function () {
                            var style = document.createElement("style");
                            style.textContent = ".ajs-cancel { display: none; }";
                            document.head.appendChild(style);
                            setTimeout(function () {
                                alertify.confirm('Mensaje',
                                    'La reservación ha sido aprobada',
                                    function () { form.submit() },
                                    function () { }
                                ).set({ transition: "zoom", closable: false, labels: { ok: 'Aceptar', cancel: 'Cancelar' } }).autoOk(5);
                            }, 100);
                        },
                        function () { }
                    ).set({ transition: "zoom", closable: false, "labels": { ok: "Aceptar", cancel: "Cancelar" } });
                } else { // Caso de que varias reservas choquen
                    if (activeTab == "Pendientes") {
                        result = "rechazadas"
                    } else if (activeTab == "Rechazadas") {
                        result = "canceladas"
                    }
                    var overlappingMessage = "";
                    for (i in overlapping) {
                        overlappingMessage = overlappingMessage + 'ID: ' + overlapping[i]['id'] + ', Inicio: ' + overlapping[i]['hora_inicio'] + ', Finalización: ' + overlapping[i]['hora_fin'] + '<br>';
                    };
                    alertify.confirm('Mensaje',
                        'Las reservaciones:<br>' + overlappingMessage + 'serán ' + result + ', ¿desea continuar?',
                        function () {
                            var style = document.createElement("style");
                            style.textContent = ".ajs-cancel { display: none; }";
                            document.head.appendChild(style);
                            setTimeout(function () {
                                alertify.confirm('Mensaje',
                                    'La reservación ha sido aprobada',
                                    function () { form.submit() },
                                    function () { }
                                ).set({ transition: "zoom", closable: false, labels: { ok: 'Aceptar', cancel: 'Cancelar' } }).autoOk(5);
                            }, 100);
                        },
                        function () { }
                    ).set({ transition: "zoom", closable: false, "labels": { ok: "Aceptar", cancel: "Cancelar" } });
                }
            }

            // Busca el elemento con id="defaultOpen" y lo abre al entrar a la página
            document.getElementById("defaultOpen").click();

            // Función para exportar a Excel
            function exportToExcel(tableName, searchBarName) {
                var table = document.getElementById(tableName);

                // Convierte la tabla HTML en un objecto con solo los elementos visibles
                var data = XLSX.utils.table_to_sheet(table, { display: true });

                // Convierte el objeto en una matriz
                var aoa = XLSX.utils.sheet_to_json(data, { header: 1, defval: "" });

                for (i = 1; i < aoa.length; i++) {
                    aoa[i][6] = new Date(aoa[i][6]).toTimeString().split(' ')[0];
                    aoa[i][7] = new Date(aoa[i][7]).toTimeString().split(' ')[0];
                }

                console.log(aoa);

                // Elimina la columna "Acción"
                aoa[0].pop();

                // Vuelve a convertir la matriz a objeto
                var ws = XLSX.utils.aoa_to_sheet(aoa);

                // Exporta el objeto en formato XLSX
                var wb = XLSX.utils.book_new();

                // Contenido de la barra de búsqueda para utilizarlo al nombrar el archivo
                var searchBar = document.getElementById(searchBarName);
                search = searchBar.value.trim();

                // Establece el nombre del archivo y lo descarga
                var tab = '';
                if (tableName == 'tablePending') {
                    tab = 'pendientes';
                } else if (tableName == 'tableApproved') {
                    tab = 'aprobadas';
                } else {
                    tab = 'rechazadas';
                }
                if (search != '') {
                    XLSX.utils.book_append_sheet(wb, ws, 'Solicitudes ' + tab);
                    XLSX.writeFile(wb, 'Solicitudes de reserva ' + tab + ' - ' + search + '.xlsx');
                } else {
                    XLSX.utils.book_append_sheet(wb, ws, 'Solicitudes ' + tab);
                    XLSX.writeFile(wb, 'Solicitudes de reserva: ' + tab + '.xlsx');
                }
            }

            // Función para exportar a PDF
            function exportToPDF(tableName, searchBarName) {
                var table = document.getElementById(tableName);
                var tableData = [];

                // Filtra las filas visibles utilizando "filter-row"
                var visibleRows = Array.from(table.getElementsByClassName('filter-row'));

                var tableHeader = table.querySelector('thead tr');

                // Obtiene los encabezados de las columnas y excluye la columna "Acción"
                if (tableHeader) {
                    var headerRow = Array.from(tableHeader.cells).slice(0, -1).map(function (cell) {
                        return cell.textContent;
                    });
                } else {
                    console.warn("Table header row not found.");
                    var headerRow = [];
                }

                // Convierte las filas visibles en una matriz
                var data = visibleRows.map(function (row) {
                    return Array.from(row.cells).slice(0, -1).map(function (cell) {
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
                if (tableName == 'tablePending') {
                    tab = 'pendientes';
                } else if (tableName == 'tableApproved') {
                    tab = 'aprobadas';
                } else {
                    tab = 'rechazadas';
                }
                if (search != '') {
                    pdfMake.createPdf(docDefinition).download('Solicitudes de reserva ' + tab + ' - ' + search + '.pdf');
                } else {
                    pdfMake.createPdf(docDefinition).download('Solicitudes de reserva ' + tab + '.pdf');
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