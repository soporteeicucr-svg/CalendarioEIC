<!DOCTYPE html>
<html lang='es'>

<?php
include '../shared/utils/get-courses.php';

session_start();

if (isset($_SESSION['usuario']) && isset($_SESSION['tipo'])) {
    if ($_SESSION['tipo'] == 'docente') {
        ?>

        <head>
            <meta charset='UTF-8'>
            <meta http-equiv='X-UA-Compatible' content='ie=edge'>
            <title>Registro para uso de laboratorios para docentes</title>
            <link rel='stylesheet' type='text/css' href='../shared/lib/alertify/css/alertify.css'>
            <link rel='stylesheet' type='text/css' href='../shared/lib/alertify/css/themes/bootstrap.css'>
            <link rel='stylesheet' type='text/css' href='../shared/lib/fontawesome/css/all.css'>
            <link rel='stylesheet' type='text/css' href='../shared/css/formulario.css'>
            <script src='../shared/lib/alertify/alertify.min.js'></script>
        </head>

        <body>
            <div>
                <div class="center">
                    <img src="../shared/img/logoEIC.png" alt="Logo Escuela de Ingeniería Civil UCR">
                    <h1>Sistema de registro y retiro de cartuchera</h1>
                </div>
                <form action="utils/db-registro-docente.php" method="post">
                    <h2 class="subDatos">Datos personales</h2>

                    <!-- Nombre completo -->
                    <label for="nombre"><i class="fa-solid fa-star-of-life fa-2xs" style="color: #ff0000;"></i> Nombre
                        completo</label>
                    <input type="text" name="nombre" id="nombre" placeholder="Ingrese su nombre"
                        value="<?php echo mb_convert_case(mb_strtolower($_SESSION['nombre'], 'UTF-8'), MB_CASE_TITLE); ?>"
                        oninput="checkFilled()">

                    <!-- Correo institucional -->
                    <label for="correo"><i class="fa-solid fa-star-of-life fa-2xs" style="color: #ff0000;"></i> Correo
                        institucional</label>
                    <input type="text" name="correo" id="correo" placeholder="Ingrese su correo institucional"
                        value="<?php echo mb_convert_case(mb_strtolower($_SESSION['usuario'], 'UTF-8'), MB_CASE_LOWER); ?>"
                        oninput="checkMail()">
                    <div id="error_correo"></div>

                    <!-- Cédula de identidad -->
                    <label for="cedula"><i class="fa-solid fa-star-of-life fa-2xs" style="color: #ff0000;"></i> Cédula de
                        identidad</label>
                    <input type="text" name="cedula" id="cedula" placeholder="Ingrese su cédula de identidad" value="<?php if (isset($_SESSION['id'])) {
                        echo $_SESSION['id'];
                    } ?>" oninput="checkFilled()">

                    <!-- Número de teléfono -->
                    <label for="celular">Número de teléfono</label>
                    <input type="text" name="celular" id="celular" placeholder="Ingrese su número de teléfono">

                    <h2 class="subDatos">Datos de reservación</h2>

                    <!-- Sigla del curso -->
                    <label for="sigla_curso">Sigla del curso (Si aplica)</label>
                    <input type="text" name="sigla_curso" id="sigla_curso"
                        placeholder="Ingrese la sigla del curso (ejemplos: IC-0101, IC9500)" oninput="searchCourse()">

                    <!-- Nombre del curso (se autorrellena) -->
                    <label for="nombre_curso">Nombre del curso (Si aplica)</label>
                    <input type="text" name="nombre_curso" id="nombre_curso" placeholder="Ingrese la sigla del curso primero"
                        readonly>

                    <!-- Selección de espacio a utilizar -->
                    <label for="espacio"><i class="fa-solid fa-star-of-life fa-2xs" style="color: #ff0000;"></i> Espacio a
                        utilizar</label>
                    <div class="selectContainer">
                        <select class="selectValue" name="espacio" id="espacio" onchange="updateSpace()" required>
                            <option value="">Seleccione un espacio</option>
                            <option value="Laboratorio 1">Laboratorio 1</option>
                            <option value="Laboratorio 2">Laboratorio 2</option>
                            <option value="Laboratorio 3">Laboratorio 3</option>
                            <option value="Aula PEC">Aula PEC (Requiere visto bueno de la dirección)</option>
                            <option value="Sala Interna">Sala Interna (Requiere visto bueno de la dirección)</option>
                            <option value="Sala Externa">Sala Externa</option>
                        </select>
                    </div>

                    <!-- Fecha y hora de ingreso (se autorrellena) -->
                    <label for="fecha_ingreso">Fecha y hora de ingreso</label>
                    <p class="avisoHora">Si utiliza el laboratorio después de las 17:00, comuníquese con la recepción de la
                        escuela</p>
                    <input type="text" name="fecha_ingreso" id="fecha_ingreso" readonly />

                    <!-- Continuar o cerrar sesión, por defecto intenta cerrar la sesión -->
                    <input type="hidden" name="continue" id="continue" value="no" />

                    <!-- Botón para enviar los datos al servidor -->
                    <input type="button" id="btnSubmit" value="Registrar" onclick="sendForm(this.form);" />
                </form>

                <!-- Botón para cancelar la solicitud -->
                <div class="center">
                    <button id="btn5" onclick="location.href='inicio.php'">Cancelar</button>
                </div>
            </div>
        </body>
        <script>
            let listaCursos = <?php echo json_encode($courses); ?>;
            let validMail = false;
            let spaceSelected = false;
            let foundCourse = false;
            let validTimeRange = false;
            let date = new Date();

            // Revisa que todos los campos estén correctos y activa el botón para enviar los datos
            function checkFilled() {
                if (document.getElementById('nombre').value != "" && validMail && document.getElementById('cedula').value != "" && spaceSelected && (document.getElementById('sigla_curso').value == "" || foundCourse) && validTimeRange) {
                    document.getElementById('btnSubmit').disabled = false;
                } else {
                    document.getElementById('btnSubmit').disabled = true;
                }
            }

            // Verifica que el correo termine con @ucr.ac.cr
            function checkMail() {
                if (document.getElementById('correo').value.toLowerCase().endsWith("@ucr.ac.cr")) {
                    document.getElementById('error_correo').innerHTML = '';
                    validMail = true;
                } else {
                    document.getElementById('error_correo').innerHTML = "<h5 style='color: red; margin-top: 0;'>Error: el correo debe finalizar con @ucr.ac.cr</h5>";
                    validMail = false;
                }
                checkFilled();
            }

            // Compara el código introducido por el usuario con los códigos de los cursos en la base de datos y rellena el campo de nombre si encuentra un match
            function searchCourse() {
                let p = document.getElementById('sigla_curso').selectionStart;
                document.getElementById('sigla_curso').value = document.getElementById('sigla_curso').value.toUpperCase();
                document.getElementById('sigla_curso').setSelectionRange(p, p);

                // Asegura que el formato sea [Letra][Letra]-[Número][Número][Número][Número]
                let regex = /[A-Z][A-Z]-[0-9][0-9][0-9][0-9]/;
                let input = document.getElementById('sigla_curso').value;
                if (!regex.test(input)) {

                    // Agrega el guión si el usuario no lo introduce manualmente
                    regex = /[A-Z][A-Z][0-9][0-9][0-9][0-9]/;
                    if (regex.test(input)) {
                        input = input.slice(0, 2) + "-" + input.slice(2);
                    }
                }
                let curso = listaCursos.find((element) => element.sigla_curso == input);

                // Si encuentra el curso obtiene el nombre
                if (curso) {
                    document.getElementById('sigla_curso').value = curso.sigla_curso;
                    document.getElementById('nombre_curso').setAttribute("value", curso.nombre_curso);
                    foundCourse = true;
                } else {
                    document.getElementById('nombre_curso').setAttribute("value", "");
                    foundCourse = false;
                }
                checkFilled();
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

                if (date.getHours() >= 7 && date.getHours() < 17) {
                    validTimeRange = true;
                } else {
                    validTimeRange = false;
                }
                checkFilled();
            }

            // Función para enviar la información al servidor
            function sendForm(form) {
                alertify.confirm('Mensaje',
                    'Formulario enviado de manera exitosa, ¿desea cerrar la sesión?',
                    function () { form.submit() },
                    function () {
                        document.getElementById("continue").setAttribute("value", "yes");
                        form.submit();
                    }
                ).set({ transition: "zoom", closable: false, labels: { ok: 'Si', cancel: 'No' } }).autoOk(10);
            }

            checkMail();
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