<?php
include 'variables.php';

$ldapconn = ldap_connect($server) or die("No se puedo conectar con el AD, Por favor revise el nombre del servidor y el puerto");
ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);
ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
?>