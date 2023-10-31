<?php
include 'utils/db-adds.php';
include 'utils/validate-data.php';

session_start();

if (isset($_POST['usuario']) && isset($_POST['contrasena'])) {
    $usuario = validate($_POST['usuario']);
    $usuario = str_replace("@ucr.ac.cr", "", $usuario);
    $contrasena = validate($_POST['contrasena']);
    $tipo = $_POST['tipo'];

    if (empty($usuario)) {
        header("Location: index.php?error=Nombre de usuario obligatorio");
        exit();
    } else if (empty($contrasena)) {
        header("Location: index.php?error=Contraseña obligatorio");
        exit();
    } else {
        /* Inicio de sesión usando SamAccountName
        // if (ldap_bind($ldapconn, "EIC\$usuario", $contrasena)) {
        */

        // Inicio de sesión usando userprincipalname
        $usuario = $usuario . "@ucr.ac.cr";
        $DN = '';
        if ($tipo == 'estudiante') {
            $DN = $DNStudents;
        } else if ($tipo == 'docente') {
            $DN = $DNProfessors;
        } else if ($tipo == 'administrativo') {
            $DN = $DNAdministrators;
        }
        if (ldap_bind($ldapconn, $usuario, $contrasena)) {
            $filter = "(userprincipalname=$usuario)";
            $result = ldap_search($ldapconn, $DN, $filter);
            $entries = ldap_get_entries($ldapconn, $result);
            if (!empty($entries[0])) {
                $_SESSION['usuario'] = $entries[0]['userprincipalname'][0];
                $_SESSION['nombre'] = $entries[0]['cn'][0];
                $_SESSION['tipo'] = $tipo;
                if ($tipo == 'estudiante') {
                    header('Location: ' . '../' . $tipo . '/formulario-registro.php');
                } else if ($tipo == 'docente') {
                    header('Location: ' . '../' . $tipo . '/inicio.php');
                } else if ($tipo == 'administrativo') {
                    header('Location: ' . '../' . $tipo . '/inicio.php');
                }
                exit();
            } else {
                header('Location: ' . '../' . $tipo . '/index.php?error=Credenciales incorrectas');
                exit();
            }
        } else {
            header('Location: ' . '../' . $tipo . '/index.php?error=Credenciales incorrectas');
            exit();
        }
    }
} else {
    header('Location: /index.php');
    exit();
}
?>