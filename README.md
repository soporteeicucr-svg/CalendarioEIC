# Sistema-EIC

Sistema de reservas para la Escuela de Ingeniería Civil.

## Sistema de Reserva EIC

El presente sistema de la Escuela de Ingeniería Civil fue desarrollado con el fin de automatizar y digitalizar tanto el proceso de reserva de espacios por parte de docentes y personal administrativo, como el de registro y retiro de cartuchera para el uso de laboratorios por parte de los estudiantes y docentes, permitiendo al personal administrativo una fácil y rápida administración de dichos datos.

## Funcionalidad

El sistema está dividido en tres módulos, cada uno con su sistema de autentificación
y funcionalidades diferentes. El módulo de estudiantes y el módulo de docentes son accesibles a través de una página de inicio en la dirección `http://localhost`, la cual cuenta con botones que redirigen a los respectivos formularios de inicio de sesión, mientras que para el módulo de docentes es necesario ingresar la dirección `http://localhost/administrativo` en la barra de navegación, esto para que no sea visible a los otros tipos de usuarios. Las funcionalidades de cada módulo son:

### Módulo de estudiantes

1. Inicio de sesión.
2. Registro del formulario para el uso de un laboratorio por parte de un estudiante.
3. Cerrar sesión.

![Formulario de registro para estudiantes](/shared/img/módulo-estudiante.png)
Formulario de registro para estudiantes.

### Módulo de docentes

1. Inicio de sesión.
2. Registro y retiro de cartuchera por parte de un docente que utilizará el laboratorio un día dado (entrega de llaves y cartuchera en recepción).
3. Solicitar una reserva para apartar una sala, aula o laboratorio para impartir un curso un día dado durante un periodo de tiempo específico.(la reserva debe ser aprobada por un usuario administrativo).
4. Visualización del calendario con las reservas activas de toda la escuela (sin mostrar datos detallados sobre la reservación o la persona que la hizo, simplemente se indica que dicho espacio ya está reservado).
5. Cerrar sesión

![Página de inicio para docentes](/shared/img/módulo-docente-inicio.png)
Página de inicio para docentes.

![Formulario de registro para docentes](/shared/img/módulo-docente-registro.png)
Formulario de registro para docentes.

![Formulario de reserva para docentes](/shared/img/módulo-docente-reserva.png)
Formulario de reserva para docentes.

![Calendario para docentes](/shared/img/módulo-docente-calendario.png)
Calendario para docentes.

### Módulo administrativo

1. Inicio de sesión.
2. Lista de formularios de registro por parte de un docente o estudiante para el uso de un laboratorio, aula o sala.
3. Administración de solicitudes de reserva para un laboratorio, aula o sala. (las solicitudes pendientes se pueden aprobar o rechazar, las aprobadas se pueden cancelar, y las rechazadas se pueden revaluar y aprobar de ser el caso).
4. Realizar reservaciones que serán aprobadas automáticamente y agregadas al sistema (a diferencia de la versión para docentes que solo permite reservaciones para impartir cursos, la versión administrativa permite ingresar “NA” en el espacio de sigla del curso para ingresar una razón personalizada para la reserva del espacio.
5. Visualización del calendario con las reservas activas de toda la escuela (con detalles de quién la solicitó y con qué razón).
6. Cerrar sesión.

![Página de inicio para administrativos](/shared/img/módulo-administrativo-inicio.png)
Página de inicio para administrativos.

![Lista de registro de accesos por parte de estudiantes y docentes](/shared/img/módulo-administrativo-registros.png)
Lista de registro de accesos por parte de estudiantes y docentes.

![Lista de solicitudes de reserva](/shared/img/módulo-administrativo-solicitudes.png)
Lista de solicitudes de reserva.

![Formulario de reserva para administrativos](/shared/img/módulo-administrativo-reserva.png)
Formulario de reserva para administrativos.

![Calendario para administrativos](/shared/img/módulo-administrativo-calendario.png)
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

## Credenciales prueba

Módulo estudiante:
usuario: `GABRIEL.ZUNIGAOROZCO@ucr.ac.cr`
contraseña: B98755

Módulo docente:
usuario: `GABRIEL.ZUNIGAOROZCO@ucr.ac.cr`
contraseña: 1-1680Ac1

Módulo administrativo:
usuario: `GABRIEL.ZUNIGAOROZCO@ucr.ac.cr`
contraseña: 1ng.c1v1/

## Información relacionada al código fuente

La totalidad del código se encuentra en la ruta C:\xampp\htdocs accesible a través del navegador de archivos, y se desarrolló utilizando el editor Visual Studio Code. El sistema está compuesto por más de treinta archivos separados en cuatro carpetas, tres de estas nombradas ante el módulo que representan: “estudiante”, “docente” y “administrativo”, y la cuarta carpeta se denomina “shared”, la cual contiene código y archivos compartidos entre los módulos, como por ejemplo, la conexión con la base de datos, la autenticación de usuarios, imágenes y estilos utilizados en las páginas, y también librerías externas utilizadas.

Las librerías externas utilizadas son: 

1. Alertify
2. Bootstrap
3. Flatpickr
4. Font Awesome
5. FullCalendar
6. JQuery
7. PDFMake
8. PHPMailer
9. SheetJS

Algunas de las imágenes utilizadas fueron obtenidas de fuentes externas, más específicamente del banco de imágenes gratuitas y libres de regalías Pixabay, las imágenes en cuestión son las que se encuentran en la ruta C:\xampp\htdocs\shared\img denominadas:

1. calendar.png
2. download-excel.png
3. download-pdf.png
4. form.png
5. key.png
6. plus.png

El resto de imágenes fueron obtenidas de manera local o creadas utilizando las anteriormente mencionadas como base.

Adicionalmente, las páginas Index (responsable de redirigir a la página principal del sistema) e inicio (donde se encuentran los botones para ingresar a los módulos estudiante y docente) están en la raíz de la carpeta htdocs, junto con otros archivos generados automáticamente y utilizados por Xampp.

## To use AD DS

### 1. Make sure the path\to\xampp\php directory has the following files

1. `libeay32.dll`
2. `libsasl.dll`
3. `ssleay32.dll`

### Usually, you can find these files in path\to\xampp\sendmail - this library also uses them. But if not, try to search for them inside the xampp directory

### 2. Uncomment or add the ldap extension in the php.ini (path\to\xampp\php\php.ini) file

`extension=ldap`

### 3. Restart the server

### 4. Make sure the path\to\xampp\php directory is set in the system environment variable PATH
