<?php
include 'db-conn.php';

// Path to the CSV file
$csvFile = "../csv/Cursos.csv";

// Open and read the CSV file
if (($handle = fopen($csvFile, "r")) !== false) {
    while (($data = fgetcsv($handle, 1000, ";")) !== false) {
        $sigla_curso = $data[0];
        $nombre_curso = $data[1];

        // Insert data into the database
        $sql = "INSERT INTO cursos (sigla_curso, nombre_curso) VALUES ('$sigla_curso', '$nombre_curso')";

        if ($conn->query($sql) === true) {
            echo "Record inserted successfully: $sigla_curso, $nombre_curso<br>";
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