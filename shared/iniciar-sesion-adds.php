<?php
include 'utils/db-adds.php';
include 'utils/validate-data.php';

session_start();

if (isset($_POST['usuario']) && isset($_POST['contrasena'])) {
    $usuario = validate($_POST['usuario']);
    // str_ireplace (no str_replace): el usuario ahora llega en mayúsculas
    // desde el formulario, así que "@UCR.AC.CR" también debe reconocerse.
    $usuario = str_ireplace("@ucr.ac.cr", "", $usuario);
    $usuario = mb_strtoupper($usuario, 'UTF-8'); // mb_*: preserva tildes/ñ correctamente
    $contrasena = validate($_POST['contrasena']);
    $tipo = $_POST['tipo'];

    if (empty($usuario)) {
        header("Location: index.php?error=Nombre de usuario obligatorio");
        exit();
    } else if (empty($contrasena)) {
        header("Location: index.php?error=Contraseña obligatorio");
        exit();
    } else {
        $usuario = $usuario . "@ucr.ac.cr";
        $DN = '';
        if ($tipo == 'estudiante') {
            $DN = $DNStudents;
        } else if ($tipo == 'docente') {
            $DN = $DNProfessors;
        } else if ($tipo == 'administrativo') {
            $DN = $DNAdministrators;
        }

        // Inicio de sesión usando userprincipalname
        if (ldap_bind($ldapconn, $usuario, $contrasena)) {
            $filter = "(userprincipalname=$usuario)";
            $result = ldap_search($ldapconn, $DN, $filter);
            $entries = ldap_get_entries($ldapconn, $result);
            if (!empty($entries[0])) {
                unset($_SESSION['intentos_' . $tipo]);
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
                $_SESSION['intentos_' . $tipo] = ($_SESSION['intentos_' . $tipo] ?? 0) + 1;
                header('Location: ' . '../' . $tipo . '/index.php?error=Credenciales incorrectas&intentos=' . $_SESSION['intentos_' . $tipo]);
                exit();
            }
        } else {
            $_SESSION['intentos_' . $tipo] = ($_SESSION['intentos_' . $tipo] ?? 0) + 1;
            header('Location: ' . '../' . $tipo . '/index.php?error=Credenciales incorrectas&intentos=' . $_SESSION['intentos_' . $tipo]);
            exit();
        }
    }
} else {
    header('Location: ../index.php');
    exit();
}
?>