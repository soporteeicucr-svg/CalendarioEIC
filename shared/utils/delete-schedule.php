<?php
include 'db-conn.php';

if (!isset($_GET['id'])) {
	echo "<script>
	alert('Undefined Schedule ID.');
	location.replace('./')
	</script>";
	$conn->close();
	exit;
}

$delete = $conn->query("DELETE FROM `lista_horarios` where id = '{$_GET['id']}'");
if ($delete) {
	echo "<script>
	alert('Event has deleted successfully.');
	location.replace('./')
	</script>";
} else {
    echo "<pre>";
    echo "An Error occurred.";
    echo "</pre>";
}
$conn->close();
?>