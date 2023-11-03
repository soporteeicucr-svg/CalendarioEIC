<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Inicio de sesión</title>
    <link rel="stylesheet" href="../shared/lib/fontawesome/css/all.css">
    <link rel="stylesheet" href="../shared/css/login.css">
</head>

<body>
    <div class="center">
        <img src="../shared/img/logoEIC.png" alt="Logo Escuela de Ingeniería Civil UCR">
        <h1>Registro para uso de laboratorios para estudiantes</h1>
    </div>

    <form action="../shared/iniciar-sesion-adds.php" method="post">
        <?php if (isset($_GET['error'])) { ?>
            <p class="error">
                <?php echo $_GET['error']; ?>
            </p>
        <?php } ?>
        <label for="usuario"><i class="fa-solid fa-user"></i> Usuario</label>
        <div class="password-container">
            <input type="text" name="usuario" id="usuario" placeholder="Ingrese su correo institucional" required>
        </div>
        <label for="contrasena"><i class="fa-solid fa-lock"></i> Contraseña</label>
        <div class="password-container">
            <input type="password" name="contrasena" id="contrasena" placeholder="Ingrese su contraseña" required>
            <i class="fa-solid fa-eye-slash eye-slash-padding" style="user-select: none" id="eye" onclick="passwordToggle()"></i>
        </div>
        <input type="hidden" id="tipo" name="tipo" value="estudiante">
        <input type="submit" value="Ingresar">
    </form>
    <div class="center">
        <button type="button" class="btn btn-back" onclick="window.location.href='/index.php'">Regresar</button>
    </div>
</body>
<script>
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
</script>

</html>