<?php
session_start();

if (isset($_SESSION['usuario']) && isset($_SESSION['tipo'])) {
    if ($_SESSION['tipo'] == 'estudiante') {
        ?>
        <!DOCTYPE html>
        <html lang='es'>

        <head>
            <meta charset='UTF-8'>
            <meta http-equiv='X-UA-Compatible' content='ie=edge'>
            <title>Registro para uso de laboratorios para estudiantes</title>
            <link rel='stylesheet' type='text/css' href='../../shared/lib/alertify/css/alertify.css'>
            <link rel='stylesheet' type='text/css' href='../../shared/lib/alertify/css/themes/bootstrap.css'>
            <link rel='stylesheet' type='text/css' href='../../shared/lib/fontawesome/css/all.css'>
            <link rel='stylesheet' type='text/css' href='../shared/css/formulario.css'>
            <script src='../../shared/lib/alertify/alertify.min.js'></script>
            <style>
                .ajs-cancel {
                    display: none;
                }
            </style>
        </head>

        <body>
            <div>
                <div class="center">
                    <img src="../shared/img/logoEIC.png" alt="Logo Escuela de Ingeniería Civil UCR">
                    <h1>Registro para uso de laboratorios</h1>
                </div>

                <form action="utils/db-registro-estudiante.php" method="post">
                    <h2 class="subDatos">Datos personales</h2>

                    <label for="nombre"><i class="fa-solid fa-star-of-life" style="color: #ff0000;"></i> Nombre completo</label>
                    <input type="text" name="nombre" id="nombre" placeholder="Ingrese su nombre"
                        value="<?php echo $_SESSION['nombre']; ?>" oninput="checkFilled()">

                    <label for="correo"><i class="fa-solid fa-star-of-life" style="color: #ff0000;"></i> Correo
                        institucional</label>
                    <input type="text" name="correo" id="correo" placeholder="Ingrese su correo institucional"
                        value="<?php echo $_SESSION['usuario']; ?>" oninput="checkMail()">

                    <label for="carnet"><i class="fa-solid fa-star-of-life" style="color: #ff0000;"></i> Carnet</label>
                    <input type="text" name="carnet" id="carnet" placeholder="Ingrese su carnet institucional" value="<?php if (isset($_SESSION['id'])) {
                        echo $_SESSION['id'];
                    } ?>" oninput="checkId()">

                    <label for="celular">Número de teléfono</label>
                    <input type="text" name="celular" id="celular" placeholder="Ingrese su número de teléfono">

                    <h2 class="subDatos">Datos reserva</h2>

                    <label>¿A cuál laboratorio desea asistir?</label>
                    <div class="selectorForm">
                        <label for="Laboratorio 1"><input type="radio" name="laboratorio" value="Laboratorio 1"
                                id="Laboratorio 1" checked="checked"> Laboratorio 1 </label>
                    </div>

                    <label for="fecha_ingreso">Fecha y hora de ingreso</label>
                    <p class="avisoHora">Si utiliza el laboratorio después de las 17:00, comuníquese con la recepción de la
                        escuela</p>
                    <input type="text" name="fecha_ingreso" id="fecha_ingreso" readonly />
                    <input type="button" id="btnSubmit" value="Registrar" onclick="sendForm(this.form);" disabled />
                </form>

                <div class="center">
                    <button id="btn3" onclick="location.href='../shared/cerrar-sesion.php'">Cerrar sesión</button>
                </div>
            </div>
        </body>
        <script>
            let validDateTime = false;
            let validMail = false;
            let validId = false;

            // Revisa que todos los campos estén correctos y activa el botón para enviar los datos
            function checkFilled() {
                if (document.getElementById('nombre').value != "" && validMail && validId && validDateTime) {
                    document.getElementById('btnSubmit').disabled = false;
                } else {
                    document.getElementById('btnSubmit').disabled = true;
                }
            }

            function checkMail() {
                if (document.getElementById('correo').value.toUpperCase().endsWith("@UCR.AC.CR")) {
                    validMail = true;
                } else {
                    validMail = false;
                }
                checkFilled();
            }

            function checkId() {
                let p = document.getElementById('carnet').selectionStart;
                document.getElementById('carnet').value = document.getElementById('carnet').value.toUpperCase();
                document.getElementById('carnet').setSelectionRange(p, p);

                let input = document.getElementById('carnet').value;
                if (input != '') {
                    validId = true;
                } else {
                    validId = false;
                }
                checkFilled();
            }

            function getDate() {
                let date = new Date();
                dateString = date.getFullYear() + '-' +
                    ('00' + (date.getMonth() + 1)).slice(-2) + '-' +
                    ('00' + date.getDate()).slice(-2) + ' ' +
                    ('00' + date.getHours()).slice(-2) + ':' +
                    ('00' + date.getMinutes()).slice(-2) + ':' +
                    ('00' + date.getSeconds()).slice(-2);
                document.getElementById('fecha_ingreso').setAttribute("value", dateString);

                if (date.getHours() >= 7 && date.getHours() < 17) {
                    validDateTime = true;
                } else {
                    validDateTime = false;
                }
                checkFilled();
            }

            // Función para enviar la información al servidor
            function sendForm(form) {
                alertify.confirm('Mensaje',
                    'Formulario enviado de manera exitosa, saliendo...',
                    function () { form.submit() },
                    function () { }
                ).set({ transition: "zoom", closable: false, labels: { ok: "Aceptar", cancel: "Cancelar" } }).autoOk(10);
            }

            checkMail();
            checkId();
            getDate();
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