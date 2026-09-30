<!DOCTYPE html>
<html lang='es'>

<head>
    <meta charset='UTF-8'>
    <meta http-equiv='X-UA-Compatible' content='ie=edge'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendario EIC</title>
    <link rel='stylesheet' type='text/css' href='../shared/lib/fontawesome/css/all.css'>
    <link rel='stylesheet' type='text/css' href='../shared/css/login.css'>
</head>

<body>
    <?php
    $error = isset($_GET['error']) ? htmlspecialchars($_GET['error'], ENT_QUOTES, 'UTF-8') : '';
    $intentos = isset($_GET['intentos']) ? (int) $_GET['intentos'] : 0;
    ?>
    <div class="auth-shell">
        <div class="auth-main">
        <div class="auth-card">
            <div class="auth-header">
                <img src="../shared/img/logo-calendario-eic.jpeg" alt="Calendario - Escuela de Ingeniería Civil UCR" class="auth-header-logo">
                <h1>Sistema Registro Laboratorios</h1>
                <span class="role-badge"><i class="fa-solid fa-user-tie"></i> Docentes</span>
            </div>
            <hr class="auth-divider">

            <div id="loginForm" class="auth-form-wrap" <?php echo $error ? 'hidden' : ''; ?>>
                <form action="../shared/iniciar-sesion-adds.php" method="post" onsubmit="return validateUsuario()">
                    <label for="usuario"><i class="fa-solid fa-user"></i> Usuario</label>
                    <div class="text-container" id="usuario-container">
                        <input type="text" name="usuario" id="usuario" placeholder="usuario@ucr.ac.cr" required
                            oninput="forceUppercase(this); clearUsuarioError();" onblur="validateUsuario()">
                    </div>
                    <div id="error_usuario" class="auth-field-error"></div>

                    <label for="contrasena"><i class="fa-solid fa-lock"></i> Contraseña</label>
                    <div class="password-container">
                        <input type="password" name="contrasena" id="contrasena" placeholder="Ingrese su contraseña" required>
                        <i class="fa-solid fa-eye-slash eye-slash-padding" style="user-select: none" id="eye"
                            onclick="passwordToggle()"></i>
                    </div>
                    <input type="hidden" id="tipo" name="tipo" value="docente">
                    <input type="submit" value="Ingresar">
                </form>
            </div>

            <div id="errorState" class="auth-error-wrap" <?php echo $error ? '' : 'hidden'; ?>>
                <div class="auth-error-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
                <p class="auth-error-message"><?php echo $error ?: 'Credenciales incorrectas'; ?></p>
                <?php if ($intentos > 0) { ?>
                    <p class="auth-error-attempts">Intentos fallidos: <?php echo $intentos; ?></p>
                <?php } ?>
                <div class="auth-error-actions">
                    <button type="button" class="btn btn-outline" onclick="showForm(false)">Volver a intentar</button>
                    <button type="button" class="btn" onclick="showForm(true)">Cambiar de cuenta</button>
                </div>
            </div>

            <button type="button" class="btn-back" onclick="window.location.href='../index.php'">Regresar</button>
        </div>
        </div>
        <footer class="auth-footer">
            <div class="auth-footer-inner">
                <div class="auth-footer-logos">
                    <img src="../shared/img/EIC.png" alt="Escuela de Ingeniería Civil">
                    <span class="auth-footer-divider"></span>
                    <img src="../shared/img/UCR.png" alt="Universidad de Costa Rica">
                </div>
                <div class="auth-footer-text">
                    <p class="auth-footer-line-primary">&copy; <span id="footerYear"></span> Escuela de Ingeniería Civil</p>
                    <p class="auth-footer-line-secondary">Universidad de Costa Rica</p>
                    <p><a href="mailto:ingenieria.civil@ucr.ac.cr">ingenieria.civil@ucr.ac.cr</a></p>
                </div>
            </div>
        </footer>
    </div>

    <script>
        document.getElementById('footerYear').textContent = new Date().getFullYear();

        // El AD de la EIC espera el usuario en mayúsculas (NOMBRE.APELLIDO),
        // pero el dominio "@ucr.ac.cr" debe quedar en minúsculas (así lo
        // reconoce el AD). Se convierte automáticamente para que no dependa
        // de cómo lo tipee cada persona.
        function forceUppercase(input) {
            let p = input.selectionStart;
            let value = input.value;
            let at = value.indexOf('@');
            if (at === -1) {
                value = value.toUpperCase();
            } else {
                value = value.slice(0, at).toUpperCase() + '@' + value.slice(at + 1).toLowerCase();
            }
            input.value = value;
            input.setSelectionRange(p, p);
        }

        function passwordToggle() {
            let x = document.getElementById("contrasena");
            let eyeIcon = document.getElementById("eye");
            if (x.type === "password") {
                x.type = "text";
                x.className = "text-to-password";
                eyeIcon.className = "fa-solid fa-eye eye-padding";
            } else {
                x.type = "password";
                x.className = "";
                eyeIcon.className = "fa-solid fa-eye-slash eye-slash-padding";
            }
        }

        // Verifica que, si se escribió un dominio, sea @ucr.ac.cr (el AD no
        // reconoce cuentas de otro dominio)
        function validateUsuario() {
            let usuario = document.getElementById('usuario').value.trim();
            let arroba = usuario.indexOf('@');
            if (arroba !== -1 && usuario.slice(arroba + 1).toLowerCase() !== 'ucr.ac.cr') {
                document.getElementById('error_usuario').textContent = 'El usuario debe usar el dominio @ucr.ac.cr';
                document.getElementById('usuario-container').classList.add('input-error');
                return false;
            }
            clearUsuarioError();
            return true;
        }

        function clearUsuarioError() {
            document.getElementById('error_usuario').textContent = '';
            document.getElementById('usuario-container').classList.remove('input-error');
        }

        function showForm(clearUser) {
            document.getElementById('errorState').hidden = true;
            document.getElementById('loginForm').hidden = false;
            if (clearUser) {
                document.getElementById('usuario').value = '';
            }
            document.getElementById('usuario').focus();
        }
    </script>
</body>

</html>
