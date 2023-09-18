<?php
session_start();

if (isset($_SESSION['id']) && isset($_SESSION['usuario'])) {
     ?>
     <!DOCTYPE html>
     <html>

     <head>
          <meta charset="UTF-8">
          <meta http-equiv="X-UA-Compatible" content="ie=edge">
          <title>Registro para uso de laboratorios para estudiantes</title>
          <link rel="stylesheet" type="text/css" href="../shared/css/formulario.css">
     </head>

     <body>
          <div>
               <div class="center">
                    <img src="../shared/img/logoEIC.png" alt="Logo Escuela de Ingeniería Civil UCR">
                    <h1>Registro para uso de laboratorios</h1>
               </div>

               <form action="utils/db-registro-estudiante.php" method="post">
                    <h2 class="subDatos">Datos personales</h2>

                    <label for="nombre">Nombre completo</label>
                    <input type="text" name="nombre" id="nombre" placeholder="Ingrese su nombre"
                         value="<?php echo $_SESSION['nombre']; ?>">

                    <label for="correo">Correo institucional</label>
                    <input type="text" name="correo" id="correo" placeholder="Ingrese su correo institucional" value=<?php echo $_SESSION['usuario']; ?>>

                    <label for="carnet">Carnet</label>
                    <input type="text" name="carnet" id="carnet" placeholder="Ingrese su carnet institucional" value=<?php echo $_SESSION['id']; ?>>

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
                    <input type="submit" id="submit" value="Enviar solicitud" disabled>
               </form>

               <div class="center">
                    <button id="btn3" onclick="location.href='../shared/cerrar-sesion.php'">Cerrar sesión</button>
                    <!--
                    Botón para cambiar contraseña, la funcionalidad ya existe,
                    si se desea implementar simplemente descomentar:
                    <button id="btn5" onclick="location.href='../shared/cambiar-contrasena.php'">Cambiar contraseña</button>
                    -->
               </div>
          </div>

          <script>
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
                         document.getElementById('submit').disabled = false;
                    } else {
                         document.getElementById('submit').disabled = true;
                    }
               }

               getDate();
               let refreshDate = setInterval(getDate, 1000);
          </script>
     </body>

     </html>

     <?php
} else {
     header("Location: index.php");
     exit();
}
?>