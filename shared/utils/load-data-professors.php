<?php
include 'db-conn.php';

// Path to the CSV file
$csvFile = "../csv/Docentes.csv";

// Open and read the CSV file
if (($handle = fopen($csvFile, "r")) !== false) {
    while (($data = fgetcsv($handle, 1000, ";")) !== false) {
        $cedula = $data[0];
        $usuario = $data[1];
        $contrasena = $data[2];
        $nombre = $data[3];

        // Insert data into the database
        $sql = "INSERT INTO usuarios_docentes (cedula, usuario, contrasena, nombre) VALUES ('$cedula', '$usuario', '$contrasena', '$nombre')";

        if ($conn->query($sql) === true) {
            echo "Record inserted successfully: $cedula, $usuario, $contrasena, $nombre<br>";
        }
    }

    // Close the CSV file
    fclose($handle);
} else {
    echo "Error opening CSV file";
}

// Close the database connection
$conn->close();
?>