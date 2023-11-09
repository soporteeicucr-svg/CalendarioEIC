<?php
include 'utils/db-conn.php';
include 'utils/validate-data.php';
include 'utils/table-name.php';

session_start();

if (isset($_POST['usuario']) && isset($_POST['contrasena'])) {
	$usuario = validate($_POST['usuario']);
	$usuario = str_replace("@ucr.ac.cr", "", $usuario);
	$contrasena = validate($_POST['contrasena']);
	$tipo = $_POST['tipo'];
	$table = getTableName($tipo);

	if (empty($usuario)) {
		header("Location: index.php?error=Nombre de usuario obligatorio");
		exit();
	} else if (empty($contrasena)) {
		header("Location: index.php?error=Contraseña obligatorio");
		exit();
	} else {
		$sql = "SELECT * FROM $table WHERE usuario = '$usuario' AND contrasena = '$contrasena'";

		$result = mysqli_query($conn, $sql);

		if (mysqli_num_rows($result) === 1) {
			$row = mysqli_fetch_assoc($result);
			if (strcasecmp($row['usuario'], $usuario) == 0 && strcasecmp($row['contrasena'], $contrasena) == 0) {
				$_SESSION['usuario'] = $row['usuario'];
				$_SESSION['nombre'] = $row['nombre'];
				$_SESSION['tipo'] = $tipo;
				if ($tipo == 'estudiante') {
					$_SESSION['id'] = $row['carnet'];
				} else {
					$_SESSION['id'] = $row['cedula'];
				}

				if ($tipo == 'estudiante' || $tipo == 'docente') {
					if ($row['cambiar_contrasena'] == 'si') {
						header("Location: cambiar-contrasena.php");
					} else {
						if ($tipo == 'estudiante') {
							header('Location: ' . '../' . $tipo . '/formulario-registro.php');
						} else {
							header('Location: ' . '../' . $tipo . '/inicio.php');
						}
					}
				} else {
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
	header('Location: ../index.php');
	exit();
}
?>