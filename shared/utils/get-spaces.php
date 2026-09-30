<?php
include '../shared/utils/db-conn.php';

// Los laboratorios van primero (son los espacios de uso más frecuente),
// luego el resto alfabéticamente.
$sql = "SELECT * FROM espacios ORDER BY (nombre NOT LIKE 'Laboratorio%'), nombre";
$result = mysqli_query($conn, $sql);
$spaces = array();
while ($row = mysqli_fetch_assoc($result)) {
    $spaces[] = $row['nombre'];
}
?>
