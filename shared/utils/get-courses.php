<?php
include "../shared/utils/db-conn.php";

session_start();

if (isset($_SESSION['id']) && isset($_SESSION['usuario'])) {
    $sql = "SELECT * FROM cursos";
    $result = mysqli_query($conn, $sql);
    $courses = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $courses[] = $row;
    }
} else {
    header("Location: ../../index.php");
    exit();
}
?>