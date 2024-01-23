<?php
// Variables del servidor
$rootDir = '/registro';

// Base de datos de cuentas de usuarios
$server = 'serverlic.eic.ucr.ac.cr';
$adminUser = 'EIC\Administrador';
$adminPassword = 'EIC-2024.';

// Base de datos de cursos y horarios
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'sistema_reservas_eic';

// Cuenta de correo:
$mailUser = 'ingenieria.civil@ucr.ac.cr';
$mailPassword = 'y45FJKkC';

// OUs (Organizational Unit) y DNs (Distinguished names) para datos de Active Directory
// DNs utilizan DCs (Domain Component) para la búsqueda
$OUStudents = 'LaboratoriosEIC';
$OUProfessors = 'ProfesoresEIC';
$OUAdministrators = 'AdministrativosEIC';
$DNStudents = "OU=$OUStudents,DC=eic,DC=ucr,DC=ac,DC=cr";
$DNProfessors = "OU=$OUProfessors,DC=eic,DC=ucr,DC=ac,DC=cr";
$DNAdministrators = "OU=$OUAdministrators,DC=eic,DC=ucr,DC=ac,DC=cr";
?>