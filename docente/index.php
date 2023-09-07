<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Inicio de sesión</title>
    <link rel="stylesheet" href="../shared/css/login.css">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
</head>

<body>
    <div class="center">
        <img src="../img/logoEIC.png" alt="Logo Escuela de Ingeniería Civil UCR">
        <h1>Reserva de laboratorios y salas para docentes</h1>
    </div>

    <form action="../shared/iniciar-sesion.php" method="post">
        <?php if (isset($_GET['error'])) { ?>
            <p class="error">
                <?php echo $_GET['error']; ?>
            </p>
        <?php } ?>
        <label for="usuario">Usuario<i class="fa-solid fa-user"></i></label>
        <input type="text" name="usuario" id="usuario" placeholder="Ingrese su correo institucional" required>
        <label for="contrasena">Contraseña</label>
        <input type="password" name="contrasena" id="contrasena" placeholder="Ingrese su contraseña" required>
        <input type="hidden" id="tipo" name="tipo" value="docente">
        <input type="submit" value="Ingresar">
    </form>
</body>

</html>