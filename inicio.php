<!DOCTYPE html>
<html lang='es'>

<head>
    <meta charset='UTF-8'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sistema de registro para uso de laboratorios EIC</title>
    <link rel='stylesheet' type='text/css' href='./shared/lib/fontawesome/css/all.css'>
    <link rel='stylesheet' type='text/css' href="./shared/css/login.css">
</head>

<body>
    <div class="center">
        <img src="./shared/img/logoEIC.png" alt="Logo Escuela de Ingeniería Civil UCR">
        <h1>Sistema de registro para uso de laboratorios</h1>
    </div>

    <!-- Contenedor -->
    <div class="main-container">
        <!-- Botón para plataforma para estudiantes -->
        <div style="margin-bottom: 10px;">
            <button type="button" class="btn btn-custom" onclick="window.location.href='./estudiante'">
                <div style="padding: 0 12%; display: flex; justify-content: space-between; align-items: center;">
                    <i class="fa-solid fa-user-graduate fa-2xl"></i>
                    <div style="width: 75%; text-align: center;">
                        Estudiantes
                    </div>
                </div>
            </button>
        </div>

        <!-- Botón para plataforma para docentes -->
        <div>
            <button type="button" class="btn btn-custom" onclick="window.location.href='./docente'">
                <div style="padding: 0 12%; display: flex; justify-content: space-between; align-items: center;">
                    <i class="fa-solid fa-user-tie fa-2xl"></i>
                    <div style="width: 75%; text-align: center;">
                        Docentes
                    </div>
                </div>
            </button>
        </div>
    </div>
</body>

</html>