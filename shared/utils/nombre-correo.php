<?php
// Si no se cuenta con el nombre desde el AD (cn), se deriva del correo
// institucional: el formato es NOMBRE.APELLIDO@ucr.ac.cr. Algunos correos
// terminan en números (ej. NOMBRE.APELLIDO12@ucr.ac.cr); esos números se
// ignoran.
function nombreDesdeCorreo($correo)
{
    $local = explode('@', $correo)[0];
    $partes = explode('.', $local);
    $partes = array_map(function ($parte) {
        return preg_replace('/[0-9]+$/', '', $parte);
    }, $partes);
    $partes = array_filter($partes, function ($parte) {
        return $parte !== '';
    });
    $nombre = implode(' ', $partes);
    return mb_convert_case(mb_strtolower($nombre, 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
}
?>
