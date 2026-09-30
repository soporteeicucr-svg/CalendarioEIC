<!DOCTYPE html>
<html lang='es'>

<?php
include '../shared/utils/db-conn.php';

session_start();

if (isset($_SESSION['usuario']) && isset($_SESSION['tipo'])) {
    if ($_SESSION['tipo'] == 'administrativo') {
        $tabInicial = (isset($_GET['tab']) && $_GET['tab'] == 'salas') ? 'salas' : 'cursos';
        $error = isset($_GET['error']) ? $_GET['error'] : '';

        $result = $conn->query("SELECT * FROM cursos ORDER BY sigla_curso");
        $cursos = $result->fetch_all(MYSQLI_ASSOC);

        $result = $conn->query("SELECT * FROM espacios ORDER BY nombre");
        $espacios = $result->fetch_all(MYSQLI_ASSOC);
        ?>

        <head>
            <meta charset='UTF-8'>
            <meta http-equiv='X-UA-Compatible' content='IE=edge'>
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Calendario EIC</title>
            <link rel='stylesheet' type='text/css' href='../shared/lib/bootstrap/bootstrap.min.css'>
            <link rel='stylesheet' type='text/css' href='../shared/lib/fontawesome/css/all.css'>
            <link rel='stylesheet' type='text/css' href='../shared/lib/alertify/css/alertify.css'>
            <link rel='stylesheet' type='text/css' href='../shared/lib/alertify/css/themes/bootstrap.css'>
            <link rel='stylesheet' type='text/css'
                href='../shared/css/calendario.css?v=<?php echo filemtime('../shared/css/calendario.css'); ?>'>
            <link rel='stylesheet' type='text/css'
                href='../shared/css/tabla-moderna.css?v=<?php echo filemtime('../shared/css/tabla-moderna.css'); ?>'>
            <link rel='stylesheet' type='text/css'
                href='../shared/css/confirm-modal-dual.css?v=<?php echo filemtime('../shared/css/confirm-modal-dual.css'); ?>'>
            <!-- Líneas separadoras un poco más visibles que en el resto de la
                 app (solo en esta página): la pestaña/tabla se veían con muy
                 poco contraste de fondo. -->
            <style>
                .calendar-tabs {
                    border-bottom-color: var(--color-gray-500);
                }

                .modern-table-wrap {
                    border-color: var(--color-gray-500);
                }

                .modern-table tbody td {
                    border-top-color: var(--color-gray-300);
                }
            </style>
            <script src='../shared/lib/jquery/jquery-3.6.0.min.js'></script>
            <script src='../shared/lib/alertify/alertify.min.js'></script>
        </head>

        <body>
            <div class="cal-header">
                <div class="cal-header-brand">
                    <img src="../shared/img/EIC.png" alt="Logo Escuela de Ingeniería Civil UCR">
                    <h1>Gestión de cursos y salas</h1>
                </div>
                <button type="button" class="btn-back" onclick="location.href='inicio.php'">
                    <i class="fa-solid fa-arrow-left"></i> Inicio
                </button>
            </div>

            <div class="calendar-card">
                <!-- Pestañas -->
                <div class="calendar-tabs">
                    <button class="cal-tab" id="tabCursos" onclick="openTab(event, 'Cursos')">Cursos</button>
                    <button class="cal-tab" id="tabSalas" onclick="openTab(event, 'Salas')">Salas y laboratorios</button>
                </div>

                <!-- Contenido de la tab de cursos -->
                <div id="Cursos" class="tabcontent cal-fade">
                    <?php if ($error == 'duplicate' && $tabInicial == 'cursos') { ?>
                        <div class="catalog-alert">Ya existe un curso con esa sigla.</div>
                    <?php } elseif ($error == 'has_reservations' && $tabInicial == 'cursos') { ?>
                        <div class="catalog-alert">No se puede eliminar este curso: todavía tiene reservaciones
                            aprobadas o pendientes vigentes.</div>
                    <?php } ?>

                    <div class="access-toolbar">
                        <div class="access-search">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <input id="searchCursos" type="text" placeholder="Buscar curso...">
                        </div>
                        <button type="button" class="catalog-add-btn" onclick="openModal('modalAddCurso')">
                            <i class="fa-solid fa-plus"></i> Agregar curso
                        </button>
                    </div>

                    <div class="modern-table-wrap">
                        <table class="modern-table" id="tableCursos">
                            <thead>
                                <tr>
                                    <th>Sigla</th>
                                    <th>Nombre del curso</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="tableCursosBody">
                                <?php if (count($cursos) > 0) { ?>
                                    <?php foreach ($cursos as $row) { ?>
                                        <tr class="filter-row">
                                            <td>
                                                <?php echo htmlspecialchars($row['sigla_curso']); ?>
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars($row['nombre_curso']); ?>
                                            </td>
                                            <td>
                                                <form method="post" action="../shared/utils/manage-catalogs.php"
                                                    onsubmit="return confirmDelete(this, 'el curso', '<?php echo htmlspecialchars(addslashes($row['sigla_curso']), ENT_QUOTES); ?>');">
                                                    <input type="hidden" name="action" value="delete_curso">
                                                    <input type="hidden" name="tab" value="cursos">
                                                    <input type="hidden" name="sigla_curso"
                                                        value="<?php echo htmlspecialchars($row['sigla_curso']); ?>">
                                                    <button type="submit" class="catalog-delete-btn" title="Eliminar curso">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                <?php } else { ?>
                                    <tr>
                                        <td colspan="3" class="modern-table-empty">No hay cursos registrados</td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Contenido de la tab de salas y laboratorios -->
                <div id="Salas" class="tabcontent cal-fade">
                    <?php if ($error == 'duplicate' && $tabInicial == 'salas') { ?>
                        <div class="catalog-alert">Ya existe un espacio con ese nombre.</div>
                    <?php } elseif ($error == 'has_reservations' && $tabInicial == 'salas') { ?>
                        <div class="catalog-alert">No se puede eliminar este espacio: todavía tiene reservaciones
                            aprobadas o pendientes vigentes.</div>
                    <?php } ?>

                    <div class="access-toolbar">
                        <div class="access-search">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <input id="searchEspacios" type="text" placeholder="Buscar espacio...">
                        </div>
                        <button type="button" class="catalog-add-btn" onclick="openModal('modalAddEspacio')">
                            <i class="fa-solid fa-plus"></i> Agregar espacio
                        </button>
                    </div>

                    <div class="modern-table-wrap">
                        <table class="modern-table" id="tableEspacios">
                            <thead>
                                <tr>
                                    <th>Nombre del espacio</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="tableEspaciosBody">
                                <?php if (count($espacios) > 0) { ?>
                                    <?php foreach ($espacios as $row) { ?>
                                        <tr class="filter-row">
                                            <td>
                                                <?php echo htmlspecialchars($row['nombre']); ?>
                                            </td>
                                            <td>
                                                <form method="post" action="../shared/utils/manage-catalogs.php"
                                                    onsubmit="return confirmDelete(this, 'el espacio', '<?php echo htmlspecialchars(addslashes($row['nombre']), ENT_QUOTES); ?>');">
                                                    <input type="hidden" name="action" value="delete_espacio">
                                                    <input type="hidden" name="tab" value="salas">
                                                    <input type="hidden" name="nombre"
                                                        value="<?php echo htmlspecialchars($row['nombre']); ?>">
                                                    <button type="submit" class="catalog-delete-btn" title="Eliminar espacio">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                <?php } else { ?>
                                    <tr>
                                        <td colspan="2" class="modern-table-empty">No hay espacios registrados</td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Modal para agregar un curso -->
            <div class="catalog-modal-overlay" id="modalAddCurso" onclick="if (event.target === this) closeModal('modalAddCurso')">
                <div class="catalog-modal">
                    <div class="catalog-modal-header">Agregar curso</div>
                    <form method="post" action="../shared/utils/manage-catalogs.php" id="formAddCurso"
                        onsubmit="return validarAddCurso()">
                        <input type="hidden" name="action" value="add_curso">
                        <input type="hidden" name="tab" value="cursos">
                        <div class="catalog-modal-body">
                            <label for="inputSigla">Sigla del curso</label>
                            <input type="text" name="sigla_curso" id="inputSigla" maxlength="8"
                                placeholder="ej. IC-0512" oninput="limpiarErrorSigla()" onblur="verificarSigla()">
                            <div class="field-error-text" id="errorSigla"></div>

                            <label for="inputNombreCurso">Nombre del curso</label>
                            <input type="text" name="nombre_curso" id="inputNombreCurso" maxlength="255"
                                placeholder="Nombre del curso" oninput="limpiarErrorNombreCurso()">
                            <div class="field-error-text" id="errorNombreCurso"></div>
                        </div>
                        <div class="catalog-modal-footer">
                            <button type="button" class="catalog-modal-btn secondary"
                                onclick="closeModal('modalAddCurso')">Cancelar</button>
                            <button type="submit" class="catalog-modal-btn primary">Agregar</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Modal para agregar un espacio -->
            <div class="catalog-modal-overlay" id="modalAddEspacio" onclick="if (event.target === this) closeModal('modalAddEspacio')">
                <div class="catalog-modal">
                    <div class="catalog-modal-header">Agregar espacio</div>
                    <form method="post" action="../shared/utils/manage-catalogs.php" id="formAddEspacio"
                        onsubmit="return validarAddEspacio()">
                        <input type="hidden" name="action" value="add_espacio">
                        <input type="hidden" name="tab" value="salas">
                        <div class="catalog-modal-body">
                            <label for="inputNombreEspacio">Nombre del espacio</label>
                            <input type="text" name="nombre" id="inputNombreEspacio" maxlength="255"
                                placeholder="ej. Laboratorio 4" oninput="limpiarErrorEspacio()"
                                onblur="verificarEspacio()">
                            <div class="field-error-text" id="errorEspacio"></div>
                        </div>
                        <div class="catalog-modal-footer">
                            <button type="button" class="catalog-modal-btn secondary"
                                onclick="closeModal('modalAddEspacio')">Cancelar</button>
                            <button type="submit" class="catalog-modal-btn primary">Agregar</button>
                        </div>
                    </form>
                </div>
            </div>

            <?php
            if (isset($conn))
                $conn->close();
            ?>
        </body>
        <script>

            var siglasExistentes = <?= json_encode(array_map(fn($c) => strtoupper($c['sigla_curso']), $cursos)) ?>;
            var espaciosExistentes = <?= json_encode(array_map(fn($e) => strtolower(trim($e['nombre'])), $espacios)) ?>;

            // Función para cambiar de tab
            function openTab(evt, tabName) {
                var i, tabcontent, tablinks;

                // Busca todos los elementos con class="tabcontent" y los esconde
                tabcontent = document.getElementsByClassName("tabcontent");
                for (i = 0; i < tabcontent.length; i++) {
                    tabcontent[i].style.display = "none";
                }

                // Busca todos los elementos con class="cal-tab" y borra la class "active"
                tablinks = document.getElementsByClassName("cal-tab");
                for (i = 0; i < tablinks.length; i++) {
                    tablinks[i].className = tablinks[i].className.replace(" active", "");
                }

                // Muestra la tab elegida y agrega la class "active" al botón
                document.getElementById(tabName).style.display = "block";
                evt.currentTarget.className += " active";
            }

            // Abre la pestaña correspondiente al recargar la página después de
            // agregar o eliminar un curso/espacio (?tab=cursos o ?tab=salas)
            document.getElementById(<?php echo $tabInicial == 'salas' ? "'tabSalas'" : "'tabCursos'"; ?>).click();

            // Funciones para abrir/cerrar los modales de alta
            function openModal(id) {
                document.getElementById(id).classList.add('active');
                document.getElementById(id).querySelector('input[type="text"]').focus();
            }

            function closeModal(id) {
                document.getElementById(id).classList.remove('active');
                document.getElementById(id).querySelector('form').reset();
                document.querySelectorAll('#' + id + ' .field-error-text').forEach(function (el) { el.textContent = ''; });
                document.querySelectorAll('#' + id + ' .input-error').forEach(function (el) { el.classList.remove('input-error'); });
            }

            // Validación del formulario de agregar curso: campos vacíos y
            // sigla duplicada (verificación en vivo contra los cursos ya
            // existentes, igual que se hace en otros formularios de la app)
            function limpiarErrorSigla() {
                document.getElementById('errorSigla').textContent = '';
                document.getElementById('inputSigla').classList.remove('input-error');
            }

            function limpiarErrorNombreCurso() {
                document.getElementById('errorNombreCurso').textContent = '';
                document.getElementById('inputNombreCurso').classList.remove('input-error');
            }

            function verificarSigla() {
                var sigla = document.getElementById('inputSigla').value.trim().toUpperCase();
                if (sigla != '' && siglasExistentes.includes(sigla)) {
                    document.getElementById('errorSigla').textContent = 'Ya existe un curso con esta sigla';
                    document.getElementById('inputSigla').classList.add('input-error');
                    return false;
                }
                limpiarErrorSigla();
                return true;
            }

            function validarAddCurso() {
                var sigla = document.getElementById('inputSigla').value.trim();
                var nombre = document.getElementById('inputNombreCurso').value.trim();
                var valido = true;

                if (sigla == '') {
                    document.getElementById('errorSigla').textContent = 'Ingrese la sigla del curso';
                    document.getElementById('inputSigla').classList.add('input-error');
                    valido = false;
                } else if (!verificarSigla()) {
                    valido = false;
                }

                if (nombre == '') {
                    document.getElementById('errorNombreCurso').textContent = 'Ingrese el nombre del curso';
                    document.getElementById('inputNombreCurso').classList.add('input-error');
                    valido = false;
                }

                return valido;
            }

            // Validación del formulario de agregar espacio: campo vacío y
            // nombre duplicado
            function limpiarErrorEspacio() {
                document.getElementById('errorEspacio').textContent = '';
                document.getElementById('inputNombreEspacio').classList.remove('input-error');
            }

            function verificarEspacio() {
                var nombre = document.getElementById('inputNombreEspacio').value.trim().toLowerCase();
                if (nombre != '' && espaciosExistentes.includes(nombre)) {
                    document.getElementById('errorEspacio').textContent = 'Ya existe un espacio con este nombre';
                    document.getElementById('inputNombreEspacio').classList.add('input-error');
                    return false;
                }
                limpiarErrorEspacio();
                return true;
            }

            function validarAddEspacio() {
                var nombre = document.getElementById('inputNombreEspacio').value.trim();

                if (nombre == '') {
                    document.getElementById('errorEspacio').textContent = 'Ingrese el nombre del espacio';
                    document.getElementById('inputNombreEspacio').classList.add('input-error');
                    return false;
                }

                return verificarEspacio();
            }

            // Confirmación de eliminación con el mismo cuadro moderno
            // (fondo oscurecido, cabecera oscura) usado en el resto de la app
            function confirmDelete(form, tipo, nombre) {
                alertify.confirm('Confirmar eliminación',
                    '¿Está seguro de que desea eliminar ' + tipo + ' "' + nombre + '"? Esta acción no se puede deshacer.',
                    function () { form.submit(); },
                    function () { }
                ).set({ transition: "zoom", closable: false, labels: { ok: 'Eliminar', cancel: 'Cancelar' } });
                return false;
            }

            // Búsqueda en la tabla de cursos
            $(document).ready(function () {
                $("#searchCursos").on("keyup", function () {
                    var value = $(this).val().toLowerCase();
                    $("#tableCursosBody tr").each(function () {
                        var match = $(this).text().toLowerCase().indexOf(value) > -1;
                        $(this).toggle(match);
                    });
                });
            });

            // Búsqueda en la tabla de espacios
            $(document).ready(function () {
                $("#searchEspacios").on("keyup", function () {
                    var value = $(this).val().toLowerCase();
                    $("#tableEspaciosBody tr").each(function () {
                        var match = $(this).text().toLowerCase().indexOf(value) > -1;
                        $(this).toggle(match);
                    });
                });
            });

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
