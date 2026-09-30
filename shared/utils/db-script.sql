-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-11-2023 a las 15:20:44
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sistema_reservas_eic`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cursos`
--

CREATE TABLE `cursos` (
  `sigla_curso` varchar(8) NOT NULL,
  `nombre_curso` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Datos de ejemplo para la tabla `cursos` (para poder probar el
-- formulario de registro de docentes)
--

INSERT INTO `cursos` (`sigla_curso`, `nombre_curso`) VALUES
('IC-0101', 'Introducción a la Ingeniería Civil'),
('IC-0402', 'Mecánica de Materiales'),
('IC-0512', 'Hidráulica'),
('IC-0621', 'Estructuras de Concreto'),
('IC-0714', 'Geotecnia'),
('IC-9500', 'Proyecto de Graduación');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `espacios` (catálogo de salas y
-- laboratorios reservables, editable desde administrativo/gestionar-catalogos.php)
--

CREATE TABLE `espacios` (
  `nombre` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `espacios` (`nombre`) VALUES
('Laboratorio 1'),
('Laboratorio 2'),
('Laboratorio 3'),
('Aula PEC'),
('Sala Interna'),
('Sala Externa'),
('Aula 413'),
('Aula 414'),
('Aula 415');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lista_horarios`
--

CREATE TABLE `lista_horarios` (
  `id` int(30) NOT NULL,
  `espacio` varchar(255) NOT NULL,
  `sigla_curso` varchar(255) NOT NULL,
  `nombre_curso` varchar(255) NOT NULL,
  `solicitante` varchar(255) NOT NULL,
  `correo` varchar(255) NOT NULL,
  `hora_inicio` datetime NOT NULL,
  `hora_fin` datetime DEFAULT NULL,
  `estado` varchar(255) NOT NULL,
  `serie_id` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- serie_id agrupa las reservaciones creadas en conjunto como una serie
-- repetida (crear-reserva.php, opcion de repetir reserva); NULL en una
-- reservacion individual. Permite cancelar toda la serie de una vez.

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registro_docente`
--

CREATE TABLE `registro_docente` (
  `id` int(30) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `correo` varchar(255) NOT NULL,
  `cedula` varchar(255) NOT NULL,
  `celular` varchar(255) NOT NULL,
  `sigla_curso` varchar(255) NOT NULL,
  `espacio` varchar(255) NOT NULL,
  `fecha_ingreso` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registro_estudiante`
--

CREATE TABLE `registro_estudiante` (
  `id` int(30) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `correo` varchar(255) NOT NULL,
  `carnet` varchar(255) NOT NULL,
  `celular` varchar(255) NOT NULL,
  `laboratorio` varchar(255) NOT NULL,
  `fecha_ingreso` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios_administrativos`
--

CREATE TABLE `usuarios_administrativos` (
  `cedula` int(15) NOT NULL,
  `usuario` varchar(255) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `nombre` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios_docentes`
--

CREATE TABLE `usuarios_docentes` (
  `cedula` int(15) NOT NULL,
  `usuario` varchar(255) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `cambiar_contrasena` enum('si','no') NOT NULL DEFAULT 'no'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios_estudiantes`
--

CREATE TABLE `usuarios_estudiantes` (
  `carnet` varchar(7) NOT NULL,
  `usuario` varchar(255) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `cambiar_contrasena` enum('si','no') NOT NULL DEFAULT 'no'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`sigla_curso`);

--
-- Indices de la tabla `espacios`
--
ALTER TABLE `espacios`
  ADD PRIMARY KEY (`nombre`);

--
-- Indices de la tabla `lista_horarios`
--
ALTER TABLE `lista_horarios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `registro_docente`
--
ALTER TABLE `registro_docente`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `registro_estudiante`
--
ALTER TABLE `registro_estudiante`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios_administrativos`
--
ALTER TABLE `usuarios_administrativos`
  ADD PRIMARY KEY (`cedula`);

--
-- Indices de la tabla `usuarios_docentes`
--
ALTER TABLE `usuarios_docentes`
  ADD PRIMARY KEY (`cedula`);

--
-- Indices de la tabla `usuarios_estudiantes`
--
ALTER TABLE `usuarios_estudiantes`
  ADD PRIMARY KEY (`carnet`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `lista_horarios`
--
ALTER TABLE `lista_horarios`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `registro_docente`
--
ALTER TABLE `registro_docente`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `registro_estudiante`
--
ALTER TABLE `registro_estudiante`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
