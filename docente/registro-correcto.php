<?php
session_start();

if (isset($_SESSION['id']) && isset($_SESSION['usuario'])) {
    ?>

    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Registro exitoso</title>
        <link rel="stylesheet" href="../shared/css/success.css">
    </head>

    <body>

        <h1>Formulario enviado de manera exitosa</h1>
        <p style="text-align:center">
            <a href="inicio.php">Aceptar</a>
        </p>
    </body>

    </html>

    <?php
} else {
    header("Location: ../index.php");
    exit();
}
?>