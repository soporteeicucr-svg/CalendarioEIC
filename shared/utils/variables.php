<?php
// Active Directory: solo se usa $server, para el bind LDAP del propio
// usuario (shared/iniciar-sesion-adds.php, shared/utils/db-adds.php).
// No hay valor por defecto a proposito: debe venir del entorno (.env).
// (Antes aqui vivian $adminUser/$adminPassword con la clave de
// EIC\Administrador en texto plano; no se usaban en ningun lado y se
// quitaron.)
$server = getenv('AD_SERVER') ?: 'serverlic.eic.ucr.ac.cr';

// Base de datos de cursos y horarios
$host = getenv('DB_HOST') ?: 'db';
$username = getenv('DB_USER');
$password = getenv('DB_PASSWORD');
$dbname = getenv('DB_NAME') ?: 'sistema_reservas_eic';

// OUs (Organizational Unit) y DNs (Distinguished names) para datos de Active Directory
// DNs utilizan DCs (Domain Component) para la búsqueda
$OUStudents = 'LaboratoriosEIC';
$OUProfessors = 'ProfesoresEIC';
$OUAdministrators = 'AdministrativosEIC';
$DNStudents = "OU=$OUStudents,DC=eic,DC=ucr,DC=ac,DC=cr";
$DNProfessors = "OU=$OUProfessors,DC=eic,DC=ucr,DC=ac,DC=cr";
$DNAdministrators = "OU=$OUAdministrators,DC=eic,DC=ucr,DC=ac,DC=cr";
?>