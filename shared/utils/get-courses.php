<?php
include "../shared/utils/db-conn.php";

$sql = "SELECT * FROM cursos";
$result = mysqli_query($conn, $sql);
$courses = array();
while ($row = mysqli_fetch_assoc($result)) {
    $courses[] = $row;
}
?>