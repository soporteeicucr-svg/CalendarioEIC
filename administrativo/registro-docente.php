<?php
include 'utils/courses.php';

session_start();

if (isset($_SESSION['id']) && isset($_SESSION['usuario'])) {

     ?>
     <!DOCTYPE html>
     <html>

     <head>
          <meta charset="UTF-8">
          <meta http-equiv="X-UA-Compatible" content="ie=edge">
          <title>Registro docente</title>
          <link rel="stylesheet" type="text/css" href="../shared/css/formulario.css">
     </head>

     <body>
          <div>
               <div class="center">
                    <img src="../img/logoEIC.png" alt="Logo Escuela de Ingeniería Civil UCR">
                    <h1>Sistema de registro docente</h1>
               </div>

               <form action="../docente/utils/db-registro-docente.php" method="post">
                    <h2 class="subDatos">Datos personales</h2>

                    <label for="nombre">Nombre completo</label>
                    <input type="text" name="nombre" id="nombre" placeholder="Ingrese el nombre del docente" required>

                    <label for="correo">Correo institucional</label>
                    <input type="text" name="correo" id="correo" placeholder="Ingrese el correo institucional del docente" required>

                    <label for="cedula">Cédula de identidad</label>
                    <input type="text" name="cedula" id="cedula" placeholder="Ingrese la cédula de identidad del docente" required>

                    <label for="celular">Número de teléfono</label>
                    <input type="text" name="celular" id="celular" placeholder="Ingrese el número de teléfono del docente">

                    <h2 class="subDatos">Datos reserva</h2>

                    <label for="sigla_curso">Sigla del curso</label>
                    <input type="text" name="sigla_curso" id="sigla_curso"
                         placeholder="Ingrese la sigla del curso (ejemplo: IC1234)" oninput="searchCourse()">

                    <label for="nombre_curso">Nombre del curso</label>
                    <input type="text" name="nombre_curso" id="nombre_curso" placeholder="Ingrese la sigla del curso primero"
                         readonly>

                    <label for="espacio">¿Cual espacio desea reservar?</label>
                    <div class="selectOption">
                         <select name="espacio" id="espacio">
                              <option value="Laboratorio 1">Laboratorio 1</option>
                              <option value="Laboratorio 2">Laboratorio 2</option>
                              <option value="Laboratorio 3">Laboratorio 3</option>
                              <option value="Aula PEC">Aula PEC (Requiere visto bueno de la dirección)</option>
                              <option value="Sala Interna">Sala Interna (Requiere visto bueno de la dirección)</option>
                              <option value="Sala Externa">Sala Externa</option>
                         </select>
                    </div>

                    <!--
                    <label>Solicitud de cable HDMI</label>
                    <div class="selectorForm">
                         <label for="hdmiSi"><input type="radio" name="hdmi" value="si" id="si">Si</label>
                         <label for="hdmiNo"><input type="radio" name="hdmi" value="no" id="no" checked="checked">No</label>
                    </div>
                    -->

                    <br>

                    <label for="fecha_ingreso">Fecha y hora de ingreso</label>
                    <p class="avisoHora">Si utiliza el laboratorio después de las 17:00, comuníquese con la recepción de la
                         escuela</p>
                    <input type="text" name="fecha_ingreso" id="fecha_ingreso" readonly />
                    <input type="submit" id="submit" value="Enviar solicitud" disabled>
               </form>

               <div class="center">
                    <button id="btn5" onclick="location.href='inicio.php'">Cancelar</button>
               </div>
          </div>

          <script>
               let listaCursos = <?php echo json_encode($courses); ?>;
               let enableButton = false;
               let date = new Date();

               function searchCourse() {
                    let p = document.getElementById('sigla_curso').selectionStart;
                    document.getElementById('sigla_curso').value = document.getElementById('sigla_curso').value.toUpperCase();
                    document.getElementById('sigla_curso').setSelectionRange(p, p);

                    let curso = listaCursos.find((element) => element.sigla_curso == document.getElementById('sigla_curso').value);
                    if (date.getHours() >= 7 && date.getHours() < 17 && curso) {
                         document.getElementById('nombre_curso').setAttribute("value", curso.nombre_curso);
                         document.getElementById('submit').disabled = false;
                         enableButton = true;
                    } else {
                         document.getElementById('nombre_curso').setAttribute("value", "");
                         document.getElementById('submit').disabled = true;
                         enableButton = false;
                    }
               }

               function getDate() {
                    date = new Date();
                    dateString = date.getFullYear() + '-' +
                         ('00' + (date.getMonth() + 1)).slice(-2) + '-' +
                         ('00' + date.getDate()).slice(-2) + ' ' +
                         ('00' + date.getHours()).slice(-2) + ':' +
                         ('00' + date.getMinutes()).slice(-2) + ':' +
                         ('00' + date.getSeconds()).slice(-2);
                    document.getElementById('fecha_ingreso').setAttribute("value", dateString);

                    if (date.getHours() >= 7 && date.getHours() < 17 && enableButton) {
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