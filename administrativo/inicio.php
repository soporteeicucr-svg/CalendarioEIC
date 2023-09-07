<?php
session_start();

if (isset($_SESSION['id']) && isset($_SESSION['usuario'])) {
     ?>
     <!DOCTYPE html>
     <html>

     <head>
          <title>Sistema administrativo de reservas</title>
          <link rel="stylesheet" type="text/css" href="../shared/css/home.css">
     </head>

     <body>
          <div>
               <div class="center">
                    <img src="../img/logoEIC.png" alt="Logo Escuela de Ingeniería Civil UCR">
                    <h1>Sistema administrativo de reserva de labortorios y salas</h1>
               </div>
               <div class="center">
                    <h2>Lista registros</h2>
                    <button id="btn2" onclick="location.href='lista-docentes.php'">Docentes</button>
                    <button id="btn2" onclick="location.href='lista-estudiantes.php'">Estudiantes</button>
               </div>
               <div class="center">
                    <h2>Solicitudes de reserva</h2>
                    <button id="btn4" onclick="location.href='administrar-solicitudes.php'">Ver solicitudes</button>
               </div>
               <div class="center">
                    <h2>Reservas</h2>
                    <button id="btn1" onclick="location.href='reservas-aprobadas.php'">Reservaciones activas</button>
                    <button id="btn1" onclick="location.href='realizar-reserva.php'">Realizar reservación</button>
                    <button id="btn1" onclick="location.href='calendario-reserva.php'">Calendario de reservaciones</button>
               </div>
               <div class="center">
               <button id="btn3" onclick="location.href='../shared/cerrar-sesion.php'">Cerrar sesión</button>
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