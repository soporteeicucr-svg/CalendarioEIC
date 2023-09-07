<?php
session_start();

if (isset($_SESSION['id']) && isset($_SESSION['usuarioAdministrativo'])) {

     ?>
     <!DOCTYPE html>
     <html>

     <head>
          <title>Reserva</title>
          <link rel="stylesheet" type="text/css" href="formulario.css">
     </head>

     <body>
          <div>
               <div class="center">
                    <img src="logoEIC.png" alt="Logo Escuela de Ingeniería Civil UCR" style="width:60%">
                    <h1>Formulario reserva administrativa</h1>
               </div>

               <form action="registrarDocente.php" method="post">

                    <h2 class="subDatos">Datos de reserva</h2>

                    <label for="nombre">Sigla del curso</label>
                    <input type="text" name="sigla" id="sigla" placeholder="Ingrese la sigla del curso" required>

                    <label for="correo">Correo institucional</label>
                    <input type="text" name="correo" id="correo" placeholder="Ingrese su nombre" value=<?php echo $_SESSION['usuarioDocente']; ?> required>

                    <label for="cedula">Cédula de identidad</label>
                    <input type="text" name="cedula" id="cedula" placeholder="Ingrese su cédula de identidad">

                    <label for="celular">Número de teléfono</label>
                    <input type="text" name="celular" id="celular" placeholder="Ingrese su número de teléfono">

                    <h2 class="subDatos">Datos reserva</h2>

                    <label for="siglaCurso">Sigla del curso</label>
                    <input type="text" name="siglaCurso" id="siglaCurso" placeholder="Ingrese la sigla del curso">


                    <label for="espacio">Espacio reservado</label>
                    <div class="selectOption">
                         <select name="espacio" id="espacio">
                              <option value="Laboratorio 1">Laboratorio 1</option>
                              <option value="Laboratorio 2">Laboratorio 2</option>
                              <option value="Laboratorio 3">Laboratorio 3</option>
                              <option value="Aula PEC">Aula PEC</option>
                              <option value="Sala Interna">Sala Interna</option>
                              <option value="Sala Externa">Sala Externa</option>
                         </select>
                    </div>

                    <label>Solicitud de cable HDMI</label>
                    <div class="selectorForm">
                         <label for="hdmiSi"><input type="radio" name="hdmi" value="si" id="si">Si</label>
                         <label for="hdmiNo"><input type="radio" name="hdmi" value="no" id="no" checked="checked">No</label>
                    </div>

                    <label for="fechaIngreso"> Fecha y hora de ingreso </label>
                    <p class="avisoHora">Si utiliza el laboratorio después de las 17:00, comuníquese con la recepción de la
                         escuela</p>
                    <input type="datetime-local" name="fechaIngreso" id="fechaIngreso" min="07:00:00" max="17:00:00" />

                    <input type="submit" value="Enviar solicitud">
               </form>

               <div class="card">
                    <div class="container">
                         <a class="logout" href="home.php">Volver al inicio</a>
                    </div>
               </div>

               <div class="spacingLogout"></div>
          </div>
     </body>

     </html>

<?php
} else {
     header("Location: index.php");
     exit();
}
?>