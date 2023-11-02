<?php
include 'variables.php';

$ldapconn = ldap_connect($servidor) or die("Couldn't connect to AD, please check host and port");
ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);
ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
?>