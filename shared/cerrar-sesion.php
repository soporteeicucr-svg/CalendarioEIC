<?php
session_start();

$tipo = $_SESSION['tipo'];

session_unset();
session_destroy();

header('Location: ' . '../' . $tipo);
?>