<?php
include 'db-conn.php';

session_start();

if (isset($_SESSION['usuario']) && isset($_SESSION['tipo']) && $_SESSION['tipo'] == 'administrativo') {
    $tab = isset($_POST['tab']) ? $_POST['tab'] : 'cursos';
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    $error = '';

    if ($action == 'add_curso') {
        $sigla = strtoupper(trim($_POST['sigla_curso']));
        $nombre = trim($_POST['nombre_curso']);
        if ($sigla != '' && $nombre != '') {
            $stmt = $conn->prepare("SELECT sigla_curso FROM cursos WHERE sigla_curso = ?");
            $stmt->bind_param("s", $sigla);
            $stmt->execute();
            if ($stmt->get_result()->num_rows > 0) {
                $error = 'duplicate';
            } else {
                $stmt = $conn->prepare("INSERT INTO cursos (sigla_curso, nombre_curso) VALUES (?, ?)");
                $stmt->bind_param("ss", $sigla, $nombre);
                $stmt->execute();
            }
        }
    } elseif ($action == 'delete_curso') {
        $sigla = $_POST['sigla_curso'];

        // No se permite eliminar un curso que todavía tiene reservaciones
        // aprobadas o pendientes vigentes (a futuro o en curso), para no
        // dejar esas reservaciones "huérfanas" del catálogo.
        $stmt = $conn->prepare("SELECT COUNT(*) AS cnt FROM lista_horarios WHERE sigla_curso = ? AND estado IN ('aprobado', 'pendiente') AND hora_fin >= NOW()");
        $stmt->bind_param("s", $sigla);
        $stmt->execute();
        $tieneReservas = $stmt->get_result()->fetch_assoc()['cnt'] > 0;

        if ($tieneReservas) {
            $error = 'has_reservations';
        } else {
            $stmt = $conn->prepare("DELETE FROM cursos WHERE sigla_curso = ?");
            $stmt->bind_param("s", $sigla);
            $stmt->execute();
        }
    } elseif ($action == 'add_espacio') {
        $nombre = trim($_POST['nombre']);
        if ($nombre != '') {
            $stmt = $conn->prepare("SELECT nombre FROM espacios WHERE nombre = ?");
            $stmt->bind_param("s", $nombre);
            $stmt->execute();
            if ($stmt->get_result()->num_rows > 0) {
                $error = 'duplicate';
            } else {
                $stmt = $conn->prepare("INSERT INTO espacios (nombre) VALUES (?)");
                $stmt->bind_param("s", $nombre);
                $stmt->execute();
            }
        }
    } elseif ($action == 'delete_espacio') {
        $nombre = $_POST['nombre'];

        // Misma protección que para cursos: no se elimina un espacio con
        // reservaciones aprobadas o pendientes vigentes.
        $stmt = $conn->prepare("SELECT COUNT(*) AS cnt FROM lista_horarios WHERE espacio = ? AND estado IN ('aprobado', 'pendiente') AND hora_fin >= NOW()");
        $stmt->bind_param("s", $nombre);
        $stmt->execute();
        $tieneReservas = $stmt->get_result()->fetch_assoc()['cnt'] > 0;

        if ($tieneReservas) {
            $error = 'has_reservations';
        } else {
            $stmt = $conn->prepare("DELETE FROM espacios WHERE nombre = ?");
            $stmt->bind_param("s", $nombre);
            $stmt->execute();
        }
    }

    $conn->close();

    $redirect = "../../administrativo/gestionar-catalogos.php?tab=" . urlencode($tab);
    if ($error != '') {
        $redirect .= "&error=" . urlencode($error);
    }
    header("Location: $redirect");
    exit();
} else {
    header("Location: ../../index.php");
    exit();
}
?>
