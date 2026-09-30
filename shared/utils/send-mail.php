<?php
// El envío de correo está deshabilitado a propósito: bajo ninguna circunstancia
// este sistema debe enviar correos. Se mantiene la firma de la función para
// no tener que tocar cada punto de llamada (save-schedule.php,
// administrativo/calendario-reserva.php); es no-op y no hace ninguna
// conexión de red.

function sendMail($address, $subject, $message, $header)
{
    return false;
}
?>
