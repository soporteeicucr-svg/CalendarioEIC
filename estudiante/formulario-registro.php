<!DOCTYPE html>
<html lang='es'>

<?php
include '../shared/utils/nombre-correo.php';

session_start();

if (isset($_SESSION['usuario']) && isset($_SESSION['tipo'])) {
    if ($_SESSION['tipo'] == 'estudiante') {
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

                <form class="form-wide" action="utils/db-registro-estudiante.php" method="post">
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

                            <!-- carnet -->
                            <label for="carnet">Carnet</label>
                            <input type="text" name="carnet" id="carnet" placeholder="Ingrese su carnet institucional" value="<?php if (isset($_SESSION['id'])) {
                                echo $_SESSION['id'];
                            } ?>" oninput="checkId()">
                        </div>

                        <div class="form-col">
                            <h2 class="subDatos">Datos reserva</h2>

                            <!-- Número de teléfono -->
                            <label for="celular">Número de teléfono</label>
                            <input type="text" name="celular" id="celular" placeholder="Ingrese su número de teléfono"
                                oninput="checkFilled()">

                            <!-- Selección de espacio a utilizar -->
                            <label>¿A cuál laboratorio desea asistir?</label>
                            <div class="selectorForm">
                                <label for="Laboratorio 1"><input type="radio" name="laboratorio" value="Laboratorio 1"
                                        id="Laboratorio 1" checked="checked"> Laboratorio 1 </label>
                            </div>

                            <!-- Fecha y hora de ingreso: no se muestra, pero se sigue
                                 registrando en segundo plano al momento del envío -->
                            <input type="hidden" name="fecha_ingreso" id="fecha_ingreso" />
                        </div>
                    </div>

                    <!-- Botón para enviar los datos al servidor. No queda deshabilitado
                         de verdad (para poder resaltar los campos faltantes al hacer
                         clic), solo se ve apagado mientras falten datos. -->
                    <input type="button" id="btnSubmit" class="btn-incomplete" value="Registrar"
                        onclick="attemptSubmit(this.form);" />

                    <!-- Botón para cerrar sesión: ahora dentro de la misma tarjeta -->
                    <button type="button" id="btn3" onclick="location.href='../shared/cerrar-sesion.php'">Cerrar
                        sesión</button>
                </form>
            </div>
        </body>
        <script>
            let validDateTime = false;
            let validId = false;

            // Limpia el resaltado en rojo de los campos que ya se completaron, y
            // actualiza el aspecto (apagado/activo) del botón de enviar
            function checkFilled() {
                ['carnet', 'celular'].forEach(function (id) {
                    let el = document.getElementById(id);
                    if (el.value.trim() !== "") {
                        el.classList.remove('input-error');
                    }
                });

                let complete = validId &&
                    document.getElementById('celular').value.trim() != "" && validDateTime;
                document.getElementById('btnSubmit').classList.toggle('btn-incomplete', !complete);
            }

            // Asegura que el carnet solo acepte letras mayúsculas y números, y verifica que no esté vacío
            function checkId() {
                let p = document.getElementById('carnet').selectionStart;
                document.getElementById('carnet').value = document.getElementById('carnet').value.toUpperCase();
                document.getElementById('carnet').setSelectionRange(p, p);

                validId = document.getElementById('carnet').value.trim() != "";
                checkFilled();
            }

            // Función para obtener la hora actual al entrar a la página
            function getDate() {
                let date = new Date();
                dateString = date.getFullYear() + '-' +
                    ('00' + (date.getMonth() + 1)).slice(-2) + '-' +
                    ('00' + date.getDate()).slice(-2) + ' ' +
                    ('00' + date.getHours()).slice(-2) + ':' +
                    ('00' + date.getMinutes()).slice(-2) + ':' +
                    ('00' + date.getSeconds()).slice(-2);
                document.getElementById('fecha_ingreso').setAttribute("value", dateString);

                if (date.getHours() >= 7 && date.getHours() <= 23) {
                    validDateTime = true;
                } else {
                    validDateTime = false;
                }
                checkFilled();
            }

            // Resalta en rojo los campos obligatorios que aún estén vacíos
            function highlightMissing() {
                let ok = true;
                ['carnet', 'celular'].forEach(function (id) {
                    let el = document.getElementById(id);
                    if (el.value.trim() === "") {
                        el.classList.add('input-error');
                        ok = false;
                    } else {
                        el.classList.remove('input-error');
                    }
                });
                return ok;
            }

            // Al hacer clic en "Registrar": si falta algo lo resalta, si no, envía
            function attemptSubmit(form) {
                let fieldsOk = highlightMissing();
                if (fieldsOk && validDateTime) {
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
                let laboratorio = document.querySelector('input[name="laboratorio"]:checked').value;
                let detalles = '<div class="confirm-space-badge"><i class="fa-solid fa-building"></i><span>' +
                    escapeHtml(laboratorio) + '</span></div>' +
                    '<div class="confirm-details">' +
                    '<p><span>Nombre:</span> ' + escapeHtml(document.getElementById('nombre').value) + '</p>' +
                    '<p><span>Carnet:</span> ' + escapeHtml(document.getElementById('carnet').value) + '</p>' +
                    '<p><span>Teléfono:</span> ' + escapeHtml(document.getElementById('celular').value) + '</p>' +
                    '</div>' +
                    '<p>¿Desea cerrar la sesión?</p>';

                alertify.confirm('Mensaje',
                    detalles,
                    function () { form.submit() },
                    function () { }
                ).set({ transition: "zoom", closable: false, labels: { ok: "Aceptar", cancel: "Cancelar" } }).autoOk(10);
            }

            checkId();
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
