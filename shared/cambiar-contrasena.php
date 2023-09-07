<?php
include 'utils/db-conn.php';
include 'utils/table-name.php';

session_start();

if (isset($_SESSION['id']) && isset($_SESSION['usuario']) && isset($_SESSION['tipo'])) {
	$table = getTableName($_SESSION['tipo']);
	$idType = "";
	$idParamType = "";
	if ($_SESSION['tipo'] == 'estudiante') {
		$idType = 'carnet';
		$idParamType = "s";
	} else {
		$idType = 'cedula';
		$idParamType = "i";
	}

	if (count($_POST) > 0) {
		$sql = "SELECT * FROM $table WHERE $idType = ?";
		$statement = $conn->prepare($sql);
		$statement->bind_param($idParamType, $_SESSION['id']);
		$statement->execute();
		$result = $statement->get_result();
		$row = $result->fetch_assoc();

		if (!empty($row)) {
			$contrasena = $row["contrasena"];
			$currentPassword = $_POST["currentPassword"];
			$newPassword = $_POST["newPassword"];
			if ($contrasena == $currentPassword) {
				$sql = "UPDATE $table SET contrasena = ?, cambiar_contrasena = 'no' WHERE $idType = ?";
				$statement = $conn->prepare($sql);
				$statement->bind_param('s' . $idParamType, $newPassword, $_SESSION['id']);
				$statement->execute();
				$message = "Se ha cambiado la contraseña con éxito";
			} else {
				$message = "Contraseña actual no es correcta";
			}
		}
	}
	?>

	<html>

	<head>
		<title>Cambio contraseña</title>
		<link rel="stylesheet" type="text/css" href="css/style.css" />
		<link rel="stylesheet" type="text/css" href="css/form.css" />
		<script>
			function validatePassword() {
				var currentPassword, newPassword, confirmPassword, output = true;

				currentPassword = document.frmChange.currentPassword;
				newPassword = document.frmChange.newPassword;
				confirmPassword = document.frmChange.confirmPassword;

				if (!currentPassword.value) {
					currentPassword.focus();
					document.getElementById("currentPassword").innerHTML = "Debe llenar este espacio";
					document.getElementById("message").innerHTML = "";
					output = false;
				}
				else if (!newPassword.value) {
					newPassword.focus();
					document.getElementById("newPassword").innerHTML = "Debe llenar este espacio";
					document.getElementById("message").innerHTML = "";
					output = false;
				}
				else if (!confirmPassword.value) {
					confirmPassword.focus();
					document.getElementById("confirmPassword").innerHTML = "Debe llenar este espacio";
					document.getElementById("message").innerHTML = "";
					output = false;
				}
				if (newPassword.value != confirmPassword.value) {
					newPassword.value = "";
					confirmPassword.value = "";
					newPassword.focus();
					document.getElementById("confirmPassword").innerHTML = "Valores no son iguales";
					document.getElementById("message").innerHTML = "";
					output = false;
				}
				return output;
			}
		</script>
	</head>

	<body>
		<div class="phppot-container tile-container">
			<form name="frmChange" method="post" action="" onSubmit="return validatePassword()">

				<div id="message" class="validation-message text-center">
					<?php if (isset($message)) {
						echo $message;
					} ?>
				</div>
				<h2 class="text-center">Cambio contraseña</h2>
				<div>
					<div class="row">
						<label class="inline-block">Contraseña actual</label> <span id="currentPassword"
							class="validation-message"></span> <input type="password" name="currentPassword"
							class="full-width">

					</div>
					<div class="row">
						<label class="inline-block">Contraseña nueva</label> <span id="newPassword"
							class="validation-message"></span><input type="password" name="newPassword" class="full-width">

					</div>
					<div class="row">
						<label class="inline-block">Confirmar contraseña</label> <span id="confirmPassword"
							class="validation-message"></span><input type="password" name="confirmPassword"
							class="full-width">

					</div>
					<div class="row">
						<input type="submit" name="submit" value="Realizar cambio" class="full-width">
					</div>
				</div>
				<div class="row">
					<button class="full-width" onclick="location.href='cerrar-sesion.php'">Salir</button>
				</div>
			</form>
		</div>
	</body>

	</html <?php
} else {
	header("Location: ../index.php");
	exit();
}
?>