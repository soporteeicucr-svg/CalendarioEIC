<?php
session_start();

if (isset($_SESSION['id']) && isset($_SESSION['usuario'])) {
     ?>
     <!DOCTYPE html>
     <html>

     <head>
          <meta charset="UTF-8">
          <meta http-equiv="X-UA-Compatible" content="ie=edge">
          <title>Reserva de laboratorios y salas para docentes</title>
          <link rel="stylesheet" type="text/css" href="../shared/css/home.css">
     </head>

     <body>
          <div>
               <div class="center">
                    <img src="../img/logoEIC.png" alt="Logo Escuela de Ingeniería Civil UCR">
                    <h1>Sistema de reserva de labortorios y salas</h1>
               </div>
               <div class="center">
                    <h2>Registro y retiro de cartuchera para espacio reservado</h2>
                    <button id="btn1" onclick="location.href='registro-docente.php'">Realizar registro</button>
               </div>
               <div class="center">
                    <h2>Solicitud de reserva de espacio</h2>
                    <button id="btn2" onclick="location.href='solicitud-reserva.php'">Realizar reserva</button>
               </div>
               <div class="center">
                    <h2>Calendario de reservas</h2>
                    <button id="btn4" onclick="location.href='calendario-reserva.php'">Calendario de reservas</button>
               </div>
               <div class="center">
                    <button id="btn3" onclick="location.href='../shared/cerrar-sesion.php'">Cerrar sesión</button>
                    <!--
                    Botón para cambiar contraseña, la funcionalidad ya existe,
                    si se desea implementar simplemente descomentar:
                    <button id="btn5" onclick="location.href='../shared/cambiar-contrasena.php'">Cambiar contraseña</button>
                    -->
               </div>
          </div>
     </body>

     </html>

     <?php
} else {
     header("Location: index.php");
     exit();
}
?>