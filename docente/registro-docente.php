<!DOCTYPE html>
<html lang='es'>

<?php
include '../shared/utils/get-courses.php';
include '../shared/utils/get-spaces.php';
include '../shared/utils/nombre-correo.php';

session_start();

if (isset($_SESSION['usuario']) && isset($_SESSION['tipo'])) {
    if ($_SESSION['tipo'] == 'docente') {
        // Si el AD no trae el nombre (cn), se deriva del correo institucional
        $nombreMostrar = !empty($_SESSION['nombre'])
            ? mb_convert_case(mb_strtolower($_SESSION['nombre'], 'UTF-8'), MB_CASE_TITLE)
            : nombreDesdeCorreo($_SESSION['usuario']);
        ?>

        <head>
            <meta charset='UTF-8'>
            <meta http-equiv='X-UA-Compatible' content='ie=edge'>
            <title>Calendario EIC</title>
            <link rel='stylesheet' type='text/css' href='../shared/lib/alertify/css/alertify.css'>
            <link rel='stylesheet' type='text/css' href='../shared/lib/alertify/css/themes/bootstrap.css'>
            <link rel='stylesheet' type='text/css' href='../shared/lib/fontawesome/css/all.css'>
            <link rel='stylesheet' type='text/css' href='../shared/css/formulario.css'>
            <link rel='stylesheet' type='text/css' href='../shared/css/confirm-modal.css'>
            <script src='../shared/lib/alertify/alertify.min.js'></script>
        </head>

        <body>
            <div>
                <div class="center">
                    <img src="../shared/img/EIC.png" alt="Logo Escuela de Ingeniería Civil UCR">
                    <h1>Sistema Registro Laboratorios</h1>
                </div>
                <form class="form-wide" action="utils/db-registro-docente.php" method="post">
                    <!-- Correo institucional: viene de la sesión (ya validado por el AD
                         al iniciar sesión). Se muestra como texto simple, no como un
                         campo editable, y se envía mediante un campo oculto. -->
                    <div class="form-static-field">
                        <i class="fa-solid fa-envelope"></i>
                        <span><?php echo mb_convert_case(mb_strtolower($_SESSION['usuario'], 'UTF-8'), MB_CASE_LOWER); ?></span>
                    </div>
                    <input type="hidden" name="correo" id="correo"
                        value="<?php echo mb_convert_case(mb_strtolower($_SESSION['usuario'], 'UTF-8'), MB_CASE_LOWER); ?>">

                    <div class="form-grid">
                        <div class="form-col">
                            <h2 class="subDatos">Datos personales</h2>

                            <!-- Nombre completo: fijo, igual que el correo (no se
                                 vuelve a escribir; se envía por un campo oculto) -->
                            <label for="nombre">Nombre completo</label>
                            <div class="form-static-value"><?php echo $nombreMostrar; ?></div>
                            <input type="hidden" name="nombre" id="nombre" value="<?php echo $nombreMostrar; ?>">

                            <!-- Cédula de identidad -->
                            <label for="cedula">Cédula de identidad</label>
                            <input type="text" name="cedula" id="cedula" placeholder="Ingrese su cédula de identidad" value="<?php if (isset($_SESSION['id'])) {
                                echo $_SESSION['id'];
                            } ?>" oninput="checkFilled()">

                            <!-- Número de teléfono -->
                            <label for="celular">Número de teléfono</label>
                            <input type="text" name="celular" id="celular" placeholder="Ingrese su número de teléfono"
                                oninput="checkFilled()">
                        </div>

                        <div class="form-col">
                            <h2 class="subDatos">Datos de reservación</h2>

                            <!-- Sigla y nombre del curso: se autocompletan entre sí
                                 con los cursos existentes, pero tambien se puede
                                 escribir uno nuevo libremente (no se guarda en la
                                 lista de cursos, solo queda en este registro) -->
                            <label for="sigla_curso">Sigla del curso</label>
                            <input type="text" name="sigla_curso" id="sigla_curso" list="lista-siglas"
                                placeholder="Ingrese o elija la sigla del curso" oninput="onSiglaInput()"
                                autocomplete="off">
                            <datalist id="lista-siglas"></datalist>

                            <label for="nombre_curso">Nombre del curso</label>
                            <input type="text" name="nombre_curso" id="nombre_curso" list="lista-nombres"
                                placeholder="Ingrese o elija el nombre del curso" oninput="onNombreInput()"
                                autocomplete="off">
                            <datalist id="lista-nombres"></datalist>

                            <!-- Selección de espacio a utilizar -->
                            <label for="espacio">Espacio a utilizar</label>
                            <div class="selectContainer" id="espacio-container">
                                <select class="selectValue" name="espacio" id="espacio" onchange="updateSpace()" required>
                                    <option value="">Seleccione un espacio</option>
                                    <?php foreach ($spaces as $espacio) { ?>
                                        <option value="<?php echo htmlspecialchars($espacio); ?>">
                                            <?php echo htmlspecialchars($espacio); ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>

                            <!-- Fecha y hora de ingreso: no se muestra, pero se sigue
                                 registrando en segundo plano al momento del envío -->
                            <input type="hidden" name="fecha_ingreso" id="fecha_ingreso" />
                        </div>
                    </div>

                    <!-- Continuar o cerrar sesión, por defecto intenta cerrar la sesión -->
                    <input type="hidden" name="continue" id="continue" value="no" />

                    <!-- Botón para enviar los datos al servidor. No queda deshabilitado
                         de verdad (para poder resaltar los campos faltantes al hacer
                         clic), solo se ve apagado mientras falten datos. -->
                    <input type="button" id="btnSubmit" class="btn-incomplete" value="Registrar"
                        onclick="attemptSubmit(this.form);" />

                    <!-- Botón para cancelar la solicitud: ahora dentro de la misma tarjeta -->
                    <button type="button" id="btn5" onclick="location.href='inicio.php'">Cancelar</button>
                </form>
            </div>
        </body>
        <script>
            let listaCursos = <?php echo json_encode($courses); ?>;
            let spaceSelected = false;
            let validTimeRange = false;
            let date = new Date();

            // Llena las listas de sugerencias (datalist) de sigla y nombre con
            // los cursos existentes; no limitan lo que se puede escribir.
            function populateCourseDatalists() {
                let listaSiglas = document.getElementById('lista-siglas');
                let listaNombres = document.getElementById('lista-nombres');
                listaCursos.forEach(function (curso) {
                    let opcionSigla = document.createElement('option');
                    opcionSigla.value = curso.sigla_curso;
                    listaSiglas.appendChild(opcionSigla);

                    let opcionNombre = document.createElement('option');
                    opcionNombre.value = curso.nombre_curso;
                    listaNombres.appendChild(opcionNombre);
                });
            }

            // Si lo escrito/elegido coincide con un curso conocido, rellena el
            // otro campo; si no coincide (curso nuevo, no registrado), no toca
            // el otro campo para no sobrescribir lo que la persona ya escribió.
            function onSiglaInput() {
                let valor = document.getElementById('sigla_curso').value.trim().toUpperCase();
                let curso = listaCursos.find((element) => element.sigla_curso.toUpperCase() == valor);
                if (curso) {
                    document.getElementById('nombre_curso').value = curso.nombre_curso;
                }
                checkFilled();
            }

            function onNombreInput() {
                let valor = document.getElementById('nombre_curso').value.trim().toUpperCase();
                let curso = listaCursos.find((element) => element.nombre_curso.toUpperCase() == valor);
                if (curso) {
                    document.getElementById('sigla_curso').value = curso.sigla_curso;
                }
                checkFilled();
            }

            // Limpia el resaltado en rojo de los campos que ya se completaron, y
            // actualiza el aspecto (apagado/activo) del botón de enviar
            function checkFilled() {
                ['cedula', 'celular', 'sigla_curso', 'nombre_curso'].forEach(function (id) {
                    let el = document.getElementById(id);
                    if (el.value.trim() !== "") {
                        el.classList.remove('input-error');
                    }
                });
                if (document.getElementById('espacio').value !== "") {
                    document.getElementById('espacio-container').classList.remove('input-error');
                }

                let complete = document.getElementById('cedula').value.trim() != "" &&
                    document.getElementById('celular').value.trim() != "" &&
                    document.getElementById('sigla_curso').value != "" &&
                    spaceSelected && validTimeRange;
                document.getElementById('btnSubmit').classList.toggle('btn-incomplete', !complete);
            }

            // Verifica que se haya elegido un espacio
            function updateSpace() {
                if (document.getElementById("espacio").value != "") {
                    spaceSelected = true;
                } else {
                    spaceSelected = false;
                }
                checkFilled();
            }

            // Función para obtener la hora actual al entrar a la página
            function getDate() {
                date = new Date();
                dateString = date.getFullYear() + '-' +
                    ('00' + (date.getMonth() + 1)).slice(-2) + '-' +
                    ('00' + date.getDate()).slice(-2) + ' ' +
                    ('00' + date.getHours()).slice(-2) + ':' +
                    ('00' + date.getMinutes()).slice(-2) + ':' +
                    ('00' + date.getSeconds()).slice(-2);
                document.getElementById('fecha_ingreso').setAttribute("value", dateString);

                if (date.getHours() >= 7 && date.getHours() <= 23) {
                    validTimeRange = true;
                } else {
                    validTimeRange = false;
                }
                checkFilled();
            }

            // Resalta en rojo los campos obligatorios que aún estén vacíos
            function highlightMissing() {
                let ok = true;
                ['cedula', 'celular', 'sigla_curso', 'nombre_curso'].forEach(function (id) {
                    let el = document.getElementById(id);
                    if (el.value.trim() === "") {
                        el.classList.add('input-error');
                        ok = false;
                    } else {
                        el.classList.remove('input-error');
                    }
                });

                let espacioContainer = document.getElementById('espacio-container');
                if (document.getElementById('espacio').value === "") {
                    espacioContainer.classList.add('input-error');
                    ok = false;
                } else {
                    espacioContainer.classList.remove('input-error');
                }
                return ok;
            }

            // Al hacer clic en "Registrar": si falta algo lo resalta, si no, envía
            function attemptSubmit(form) {
                let fieldsOk = highlightMissing();
                if (fieldsOk && validTimeRange) {
                    sendForm(form);
                }
            }

            // Escapa texto antes de insertarlo como HTML en el recuadro de detalles
            function escapeHtml(texto) {
                let div = document.createElement('div');
                div.textContent = texto;
                return div.innerHTML;
            }

            // Función para enviar la información al servidor
            function sendForm(form) {
                let detalles =
                    '<div class="confirm-space-badge"><i class="fa-solid fa-building"></i><span>' +
                    escapeHtml(document.getElementById('espacio').value) + '</span></div>' +
                    '<div class="confirm-details">' +
                    '<p><span>Nombre:</span> ' + escapeHtml(document.getElementById('nombre').value) + '</p>' +
                    '<p><span>Cédula:</span> ' + escapeHtml(document.getElementById('cedula').value) + '</p>' +
                    '<p><span>Teléfono:</span> ' + escapeHtml(document.getElementById('celular').value) + '</p>' +
                    '<p><span>Sigla del curso:</span> ' + escapeHtml(document.getElementById('sigla_curso').value) + '</p>' +
                    '<p><span>Nombre del curso:</span> ' + escapeHtml(document.getElementById('nombre_curso').value) + '</p>' +
                    '</div>' +
                    '<p>¿Desea cerrar la sesión?</p>';

                alertify.confirm('Mensaje',
                    detalles,
                    function () { form.submit() },
                    function () {
                        document.getElementById("continue").setAttribute("value", "yes");
                        form.submit();
                    }
                ).set({ transition: "zoom", closable: false, labels: { ok: 'Si', cancel: 'No' } }).autoOk(10);
            }

            populateCourseDatalists();
            getDate();

            // Actualiza la hora cada segundo
            let refreshDate = setInterval(getDate, 1000);
        </script>

        </html>

        <?php
    } else {
        header("Location: ../index.php");
        exit();
    }
} else {
    header("Location: ../index.php");
    exit();
}
?>
