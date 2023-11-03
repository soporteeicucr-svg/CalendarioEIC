<?php
function getTableName($tipo)
{
    $tableName = "";
    if ($tipo == "estudiante") {
        $tableName = "usuarios_estudiantes";
    } else if ($tipo == "docente") {
        $tableName = "usuarios_docentes";
    } else if ($tipo == "administrativo") {
        $tableName = "usuarios_administrativos";
    }

    return $tableName;
}
?>