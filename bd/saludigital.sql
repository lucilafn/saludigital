-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 11-11-2025 a las 19:47:25
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `saludigital`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horarios`
--

CREATE TABLE `horarios` (
  `id_horario` int(11) NOT NULL,
  `id_profesional` int(11) NOT NULL,
  `dia` date NOT NULL,
  `hora` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `horarios`
--

INSERT INTO `horarios` (`id_horario`, `id_profesional`, `dia`, `hora`) VALUES
(1, 4, '2025-09-24', '10:00:00'),
(2, 4, '2025-09-24', '11:00:00'),
(3, 5, '2025-09-24', '12:00:00'),
(4, 5, '2025-09-24', '13:00:00'),
(5, 6, '2025-09-24', '14:00:00'),
(6, 6, '2025-09-24', '15:00:04'),
(7, 7, '2025-09-23', '16:00:00'),
(8, 7, '2025-09-23', '17:00:00'),
(9, 5, '2025-09-28', '18:00:00'),
(10, 4, '2025-10-23', '18:00:00'),
(12, 4, '2025-10-24', '18:00:00'),
(13, 4, '2025-10-25', '18:00:00'),
(14, 4, '2025-10-26', '18:00:00'),
(15, 4, '2025-10-27', '18:00:00'),
(16, 5, '2025-10-30', '11:00:00'),
(17, 6, '2025-10-04', '17:00:00'),
(18, 7, '2025-11-05', '13:30:00'),
(19, 7, '2025-11-05', '13:30:00'),
(20, 6, '2025-11-10', '16:50:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profesionales`
--

CREATE TABLE `profesionales` (
  `id_profesional` int(11) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `apellido` varchar(30) NOT NULL,
  `id_servicio` int(11) NOT NULL,
  `dias_trabajados` varchar(50) NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  `intervalo` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `profesionales`
--

INSERT INTO `profesionales` (`id_profesional`, `nombre`, `apellido`, `id_servicio`, `dias_trabajados`, `hora_inicio`, `hora_fin`, `intervalo`) VALUES
(4, 'Karina', 'Latorre', 5, 'lunes,miercoles,viernes', '12:00:00', '18:00:00', 30),
(5, 'Julian', 'Delano', 3, 'miercoles,jueves,viernes', '08:00:00', '16:00:00', 30),
(6, 'Cristofer', 'Estrada', 4, 'martes,jueves', '14:00:00', '20:00:00', 20),
(7, 'Valentina', 'Pincharrata', 3, 'martes,jueves,viernes', '09:00:00', '17:00:00', 30);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

CREATE TABLE `servicios` (
  `id_servicio` int(11) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `descripcion` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `servicios`
--

INSERT INTO `servicios` (`id_servicio`, `nombre`, `descripcion`) VALUES
(3, 'cardiologia', 'del cocoro'),
(4, 'dermatologia', 'de la pielcita'),
(5, 'fonoaudiologia', 'del habla');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `turnos`
--

CREATE TABLE `turnos` (
  `id_turno` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_horario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `turnos`
--

INSERT INTO `turnos` (`id_turno`, `id_usuario`, `id_horario`) VALUES
(18, 12, 14),
(19, 12, 13),
(20, 12, 17),
(21, 12, 18),
(22, 12, 16),
(23, 12, 20);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `apellido` varchar(30) NOT NULL,
  `dni` int(8) NOT NULL,
  `telefono` bigint(20) NOT NULL,
  `obra_social` varchar(30) NOT NULL,
  `email` varchar(30) NOT NULL,
  `contrasenia` varchar(255) NOT NULL,
  `rol` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `apellido`, `dni`, `telefono`, `obra_social`, `email`, `contrasenia`, `rol`) VALUES
(10, 'qsy', 'wacho', 243424234, 0, 'papafrita', 'juan@juan', '81dc9bdb52d04dc20036dbd8313ed055', 0),
(12, 'Matias', 'Gigena', 32456683, 5492262235377, 'polenta paola', 'mati@mati', '81dc9bdb52d04dc20036dbd8313ed055', 0),
(13, 'fulano', 'mengano', 12345432, 12321312, 'pipo gorosito', 'totute@pedo', '81dc9bdb52d04dc20036dbd8313ed055', 0),
(16, 'Tomas', 'Medina', 46694510, 5492262338001, 'union personal', 'tomamediarri@gmail.com', '90a9d40093ed9f0e387b8923f799105f', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `horarios`
--
ALTER TABLE `horarios`
  ADD PRIMARY KEY (`id_horario`),
  ADD KEY `id_profesional` (`id_profesional`);

--
-- Indices de la tabla `profesionales`
--
ALTER TABLE `profesionales`
  ADD PRIMARY KEY (`id_profesional`),
  ADD KEY `id_servicio` (`id_servicio`);

--
-- Indices de la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`id_servicio`);

--
-- Indices de la tabla `turnos`
--
ALTER TABLE `turnos`
  ADD PRIMARY KEY (`id_turno`),
  ADD KEY `id_usuario` (`id_usuario`) USING BTREE,
  ADD KEY `id_horario` (`id_horario`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `horarios`
--
ALTER TABLE `horarios`
  MODIFY `id_horario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `profesionales`
--
ALTER TABLE `profesionales`
  MODIFY `id_profesional` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `servicios`
--
ALTER TABLE `servicios`
  MODIFY `id_servicio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `turnos`
--
ALTER TABLE `turnos`
  MODIFY `id_turno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `horarios`
--
ALTER TABLE `horarios`
  ADD CONSTRAINT `horarios_ibfk_1` FOREIGN KEY (`id_profesional`) REFERENCES `profesionales` (`id_profesional`);

--
-- Filtros para la tabla `profesionales`
--
ALTER TABLE `profesionales`
  ADD CONSTRAINT `fk_especialidad` FOREIGN KEY (`id_servicio`) REFERENCES `servicios` (`id_servicio`);

--
-- Filtros para la tabla `turnos`
--
ALTER TABLE `turnos`
  ADD CONSTRAINT `fk_paciente` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `turnos_ibfk_1` FOREIGN KEY (`id_horario`) REFERENCES `horarios` (`id_horario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
