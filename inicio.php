<!DOCTYPE html>
<html lang='es'>

<head>
    <meta charset='UTF-8'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Calendario EIC</title>
    <link rel='stylesheet' type='text/css' href='./shared/lib/fontawesome/css/all.css'>
    <link rel='stylesheet' type='text/css' href="./shared/css/login.css">
</head>

<body>
    <div class="auth-shell">
        <div class="auth-main">
        <div class="auth-card">
            <div class="auth-header">
                <img src="./shared/img/logo-calendario-eic.jpeg" alt="Calendario - Escuela de Ingeniería Civil UCR" class="auth-header-logo">
                <h1>Sistema Registro Laboratorios</h1>
            </div>
            <hr class="auth-divider">

            <!-- Botón para plataforma para estudiantes -->
            <div class="selector-group">
                <button type="button" class="btn btn-custom" onclick="window.location.href='./estudiante/'">
                    <div class="selector-row">
                        <i class="fa-solid fa-user-graduate"></i>
                        <span>Estudiantes</span>
                    </div>
                </button>
            </div>

            <!-- Botón para plataforma para docentes -->
            <div class="selector-group">
                <button type="button" class="btn btn-custom" onclick="window.location.href='./docente/'">
                    <div class="selector-row">
                        <i class="fa-solid fa-user-tie"></i>
                        <span>Docentes</span>
                    </div>
                </button>
            </div>

            <!-- Acceso para administrativos -->
            <div class="admin-access">
                <a href="./administrativo/">Acceso Administrativos</a>
            </div>
        </div>
        </div>
        <footer class="auth-footer">
            <div class="auth-footer-inner">
                <div class="auth-footer-logos">
                    <img src="./shared/img/EIC.png" alt="Escuela de Ingeniería Civil">
                    <span class="auth-footer-divider"></span>
                    <img src="./shared/img/UCR.png" alt="Universidad de Costa Rica">
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
    </script>
</body>

</html>
