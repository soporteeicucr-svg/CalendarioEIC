<?php
// Elimina de registro_estudiante/registro_docente (la "Lista de registros"
// de administrativo/registro-accesos.php) todo lo anterior al mes actual y
// los 2 meses anteriores (3 meses en total), para que ese historial no
// crezca indefinidamente. Se llama cada vez que se carga esa página: como
// el corte se calcula con la fecha del momento, al cambiar de mes la
// ventana de 3 meses se recalcula sola, sin necesidad de un cron ni de
// guardar cuándo fue la última limpieza.
function cleanupAccessLogs($conn)
{
    $year = (int) date('Y');
    $month = (int) date('n') - 2;
    if ($month <= 0) {
        $month += 12;
        $year -= 1;
    }
    // Día fijo en 01: solo interesa el mes de corte, no el día exacto de hoy
    // (evita el problema de restar meses con fechas cerca de fin de mes).
    $cutoff = sprintf('%04d-%02d-01', $year, $month);

    $conn->query("DELETE FROM registro_estudiante WHERE fecha_ingreso < '$cutoff'");
    $conn->query("DELETE FROM registro_docente WHERE fecha_ingreso < '$cutoff'");
}
?>
