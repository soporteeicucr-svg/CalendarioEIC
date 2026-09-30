<?php
// La app vive en la raíz de su propio (sub)dominio (sin prefijo /registro),
// así que el redirect es siempre al mismo origen + /inicio.php.
if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
	$uri = 'https://';
} else {
	$uri = 'http://';
}
$uri .= $_SERVER['HTTP_HOST'];
header('Location: ' . $uri . '/inicio.php');
exit;
?>
