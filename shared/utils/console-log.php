<?php
function consoleLog($data)
{
	$output = $data;
	if (is_array($output))
		$output = implode(',', $output);

	echo "<script>console.log('Objetos: " . $output . "' );</script>";
}
?>