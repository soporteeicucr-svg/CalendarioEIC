# Sistema-EIC

Sistema de reservas para la Escuela de Ingeniería Civil.

## Sistema de registros y reservas EIC

El presente sistema de la Escuela de Ingeniería Civil fue desarrollado con el fin de automatizar y digitalizar tanto el proceso de reserva de espacios por parte de docentes y personal administrativo, como el de registro y retiro de cartuchera para el uso de laboratorios por parte de los estudiantes y docentes, permitiendo al personal administrativo una fácil y rápida administración de dichos datos.

## Funcionalidad

El sistema está dividido en tres módulos, cada uno con su sistema de autentificación
y funcionalidades diferentes. El módulo de estudiantes y el módulo de docentes son accesibles a través de una página de inicio en la dirección `http://localhost`, la cual cuenta con botones que redirigen a los respectivos formularios de inicio de sesión, mientras que para el módulo de docentes es necesario ingresar la dirección `http://localhost/administrativo` en la barra de navegación, esto para que no sea visible a los otros tipos de usuarios.

![Foto de página de inicio](/shared/img/pagina-inicio.png)
Formulario de registro para estudiantes.

Las funcionalidades de cada módulo son:

### Módulo de estudiantes

1. Inicio de sesión.
2. Registro del formulario para el uso de un laboratorio por parte de un estudiante.
3. Cerrar sesión.

![Foto de formulario de registro para estudiantes](/shared/img/modulo-estudiante.png)
Formulario de registro para estudiantes.

### Módulo de docentes

1. Inicio de sesión.
2. Registro y retiro de cartuchera por parte de un docente que utilizará el laboratorio un día dado (entrega de llaves y cartuchera en recepción).
3. Solicitar una reserva para apartar una sala, aula o laboratorio para impartir un curso un día dado durante un periodo de tiempo específico.(la reserva debe ser aprobada por un usuario administrativo).
4. Visualización del calendario con las reservas activas de toda la escuela (sin mostrar datos detallados sobre la reservación o la persona que la hizo, simplemente se indica que dicho espacio ya está reservado).
5. Cerrar sesión

![Foto de página de inicio para docentes](/shared/img/modulo-docente-inicio.png)
Página de inicio para docentes.

![Foto de formulario de registro para docentes](/shared/img/modulo-docente-registro.png)
Formulario de registro para docentes.

![Foto de formulario de reserva para docentes](/shared/img/modulo-docente-reserva.png)
Formulario de reserva para docentes.

![Foto de calendario para docentes](/shared/img/modulo-docente-calendario.png)
Calendario para docentes.

### Módulo administrativo

1. Inicio de sesión.
2. Lista de formularios de registro por parte de un docente o estudiante para el uso de un laboratorio, aula o sala.
3. Administración de solicitudes de reserva para un laboratorio, aula o sala. (las solicitudes pendientes se pueden aprobar o rechazar, las aprobadas se pueden cancelar, y las rechazadas se pueden revaluar y aprobar de ser el caso).
4. Realizar reservaciones que serán aprobadas automáticamente y agregadas al sistema (a diferencia de la versión para docentes que solo permite reservaciones para impartir cursos, la versión administrativa permite ingresar “NA” en el espacio de sigla del curso para ingresar una razón personalizada para la reserva del espacio.
5. Visualización del calendario con las reservas activas de toda la escuela (con detalles de quién la solicitó y con qué razón).
6. Cerrar sesión.

![Foto de página de inicio para administrativos](/shared/img/modulo-administrativo-inicio.png)
Página de inicio para administrativos.

![Foto de lista de registro de accesos por parte de estudiantes y docentes](/shared/img/modulo-administrativo-registros.png)
Lista de registro de accesos por parte de estudiantes y docentes.

![Foto de lista de solicitudes de reserva](/shared/img/modulo-administrativo-solicitudes.png)
Lista de solicitudes de reserva.

![Foto de formulario de reserva para administrativos](/shared/img/modulo-administrativo-reserva.png)
Formulario de reserva para administrativos.

![Foto de calendario para administrativos](/shared/img/modulo-administrativo-calendario.png)
Calendario para administrativos.

## Información relacionada a la ejecución de los módulos

Dirigirse a la carpeta donde Xampp se encuentre instalado (generalmente C:\xampp).
Ejecutar xampp-control.
Iniciar los módulos de Apache y MySQL.
Ingresar al módulo del sistema deseado:
Para la página de inicio: `http://localhost`.
Para el módulo estudiante: `http://localhost/estudiante`.
Para el módulo docente: `http://localhost/docente`.
Para el módulo administrativo: `http://localhost/administrativo`.

## Credenciales de prueba

Módulo estudiante:
usuario: `GABRIEL.ZUNIGAOROZCO@ucr.ac.cr`
contraseña: 1ng.c1v1/

Módulo docente:
usuario: `GABRIEL.ZUNIGA1@ucr.ac.cr`
contraseña: 1ng.c1v1/

Módulo administrativo:
usuario: `GABRIEL.ZUNIGA2@ucr.ac.cr`
contraseña: 1ng.c1v1/

## Información relacionada al código fuente

La totalidad del código se encuentra en la ruta C:\xampp\htdocs accesible a través del navegador de archivos, y se desarrolló utilizando el editor Visual Studio Code. El sistema está compuesto por más de treinta archivos en formato PHP, e incluyen los lenguajes PHP, JavaScript y HTML. Estos archivos se encuentran separados en cuatro carpetas, tres de estas nombradas ante el módulo que representan: “estudiante”, “docente” y “administrativo”, y la cuarta carpeta se denomina “shared”, la cual contiene código y archivos compartidos entre los módulos, como por ejemplo, la conexión con la base de datos, la autenticación de usuarios, imágenes y estilos utilizados en las páginas, y también librerías externas utilizadas.

Las librerías externas utilizadas son:

1. `Alertify`
2. `Bootstrap`
3. `Flatpickr`
4. `Font Awesome`
5. `FullCalendar`
6. `JQuery`
7. `PDFMake`
8. `PHPMailer`
9. `SheetJS`

Algunas de las imágenes utilizadas fueron obtenidas de fuentes externas, más específicamente del banco de imágenes gratuitas y libres de regalías Pixabay, las imágenes en cuestión son las que se encuentran en la ruta C:\xampp\htdocs\shared\img denominadas:

1. `calendar.png`
2. `download-excel.png`
3. `download-pdf.png`
4. `form.png`
5. `key.png`
6. `plus.png`

El resto de imágenes fueron obtenidas de manera local o creadas utilizando las anteriormente mencionadas como base.

Adicionalmente, las páginas Index (responsable de redirigir a la página principal del sistema) e inicio (donde se encuentran los botones para ingresar a los módulos estudiante y docente) están en la raíz de la carpeta htdocs, junto con otros archivos generados automáticamente y utilizados por Xampp.

## Para utilizar AD DS

### Descomentar o agregar la extensión ldap en el archivo php.ini (ruta\a\xampp\php\php.ini).

`extensión=ldap`

### Reiniciar el servidor.

### Verificar que el directorio ruta\a\xampp\php esté configurado en la variable de entorno del sistema PATH.

## Configuración

Para fácil manejo de credenciales y configuración, las variables del sistema se pueden establecer en el archivo `shared/utils/variables.php`, dichas variables son:

### Variables de Active Directory

1. `$server`: Establece el nombre del servidor de Active Directory.
2. `$adminUser`: Usuario administrador de Active Directory.
3. `$adminPassword`: Contraseña del administrador de Active Directory.

### Variables de la base de datos phpMyAdmin

1. `$host`: Dirección IP de la base de datos.
2. `$username`: Nombre de usuario de la base de datos.
3. `$password`: Contraseña del usuario de la base de datos.
4. `$dbname`: Nombre de la base de datos.

### Cuenta de correo

1. `$mailUser`: Dirección de correo para enviar notificaciones.
2. `$mailPassword`: Contraseña de la cuenta de correo.

### OUs (Organizational Unit) y DNs (Distinguished names) para datos de Active Directory

DNs utilizan DCs (Domain Component) para la búsqueda

1. `$OUStudents`: Nombre de la unidad organizacional que contiene las cuentas de los estudiantes.
2. `$OUProfessors`: Nombre de la unidad organizacional que contiene las cuentas de los profesores.
3. `$OUAdministrators`: Nombre de la unidad organizacional que contiene las cuentas de los administradores.
4. `$DNStudents`: Nombre distinguido para acceder a los datos de los estudiantes.
5. `$DNProfessors`: Nombre distinguido para acceder a los datos de los profesores.
6. `$DNAdministrators`: Nombre distinguido para acceder a los datos de los administradores.
