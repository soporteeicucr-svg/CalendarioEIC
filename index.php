<?php
include 'shared/utils/variables.php';

if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
	$uri = 'https://';
} else {
	$uri = 'http://';
}
if ($_SERVER['HTTP_HOST'] == 'localhost') {
	$uri .= $_SERVER['HTTP_HOST'] . $rootDir;
} else if ($_SERVER['HTTP_HOST'] == $serverName || $_SERVER['HTTP_HOST'] == "www.$serverName") {
	$uri .= $_SERVER['HTTP_HOST'];
}
header('Location: ' . $uri . '/inicio.php');
exit;
?>