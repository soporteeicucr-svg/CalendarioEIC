<?php
$ad = ldap_connect("serverlic.eic.ucr.ac.cr") or die("Couldn't connect to AD!");
$bd = ldap_bind($ad,"serverlic.eic.ucr.ac.cr","Silvia2020.") or die("Couldn't bind to AD!");
?>