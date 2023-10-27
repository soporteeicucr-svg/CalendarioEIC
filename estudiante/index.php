<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Inicio de sesión</title>
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
        <label for="usuario">Usuario<i class="fa-solid fa-user"></i></label>
        <input type="text" name="usuario" id="usuario" placeholder="Ingrese su correo institucional" required>
        <label for="contrasena">Contraseña</label>
        <input type="password" name="contrasena" id="contrasena" placeholder="Ingrese su contraseña" required>
        <input type="hidden" id="tipo" name="tipo" value="estudiante">
        <input type="submit" value="Ingresar">
    </form>
    <div class="center">
        <button type="button" class="btn btn-back" onclick="window.location.href='/index.php'">Regresar</button>
    </div>
</body>

</html>