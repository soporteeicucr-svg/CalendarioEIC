<?php
$ldapconn = ldap_connect("serverlic.eic.ucr.ac.cr") or die("Couldn't connect to AD, please check host and port");
ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);
ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
$OUStudents = "LaboratoriosEIC";
$OUProfessors = "ProfesoresEIC";
$OUAdministrators = "AdministrativosEIC";
$DNStudents = "OU=$OUStudents,DC=eic,DC=ucr,DC=ac,DC=cr";
$DNProfessors = "OU=$OUProfessors,DC=eic,DC=ucr,DC=ac,DC=cr";
$DNAdministrators = "OU=$OUAdministrators,DC=eic,DC=ucr,DC=ac,DC=cr";

/* Ejemplo de lectura de datos
include 'variables.php';
$ldapbind = ldap_bind($ldapconn, $adminUser, $adminPassword);
if ($ldapbind) {
    $filter= "(userprincipalname=gabriel.zunigaorozco@ucr.ac.cr)";
    $result = ldap_search($ldapconn, $DN, $filter);
    if (FALSE !== $result) {
        $entries = ldap_get_entries($ldapconn, $result);
        if ($entries['count'] > 0) {
            $odd = 0;
            foreach ($entries[0] as $key => $value) {
                if (0 === $odd % 2) {
                    $ldap_columns[] = $key;
                }
                $odd++;
            }
            echo '<table class="data">';
            echo '<tr>';
            $header_count = 0;
            foreach ($ldap_columns as $col_name) {
                if (0 === $header_count++) {
                    echo '<th class="ul">';
                } else if (count($ldap_columns) === $header_count) {
                    echo '<th class="ur">';
                } else {
                    echo '<th class="u">';
                }
                echo $col_name . '</th>';
            }
            echo '</tr>';
            for ($i = 0; $i < $entries['count']; $i++) {
                echo '<tr>';
                $td_count = 0;
                foreach ($ldap_columns as $col_name) {
                    if (0 === $td_count++) {
                        echo '<td class="l">';
                    } else {
                        echo '<td>';
                    }
                    if (isset($entries[$i][$col_name])) {
                        $output = NULL;
                        if ('lastlogon' === $col_name || 'lastlogontimestamp' === $col_name) {
                            $output = date('D M d, Y @ H:i:s', ($entries[$i][$col_name][0] / 10000000) - 11676009600); // See note below
                        } else {
                            $output = $entries[$i][$col_name][0];
                        }
                        echo $output . '</td>';
                    }
                }
                echo '</tr>';
            }
            echo '</table>';
        }
    }
}
ldap_unbind($ldapconn);
*/
?>