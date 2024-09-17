-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 17-09-2024 a las 19:46:16
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
-- Base de datos: `seleccion argentina`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `posiciones, nombres`
--

CREATE TABLE `posiciones, nombres` (
  `ID` int(11) NOT NULL,
  `Arqueros` text NOT NULL,
  `Defensores` text NOT NULL,
  `Mediocampistas` text NOT NULL,
  `Delanteros` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `posiciones, nombres`
--

INSERT INTO `posiciones, nombres` (`ID`, `Arqueros`, `Defensores`, `Mediocampistas`, `Delanteros`) VALUES
(1, 'ARQUEROS:\r\n\r\nEmiliano Martínez\r\nFranco Armani\r\nGerónimo Rulli', 'DEFENSORES:\r\n\r\nGonzalo Montiel\r\nNahuel Molina\r\nCristian Romero\r\nGermán Pezzella\r\nLucas Martínez Quarta\r\nNicolás Otamendi\r\nLisandro Martínez\r\nMarcos Acuña\r\nNicolás Tagliafico', 'MEDIOCAMPISTAS:\r\n\r\nGuido Rodríguez\r\nLeandro Paredes\r\nAlexis Mac Allister\r\nRodrigo De Paul\r\nExequiel Palacios\r\nEnzo Fernández\r\nGiovani Lo Celso', 'DELANTEROS:\r\n\r\nLionel Messi\r\nValentín Carboni\r\nAlejandro Garnacho\r\nNicolás González\r\nLautaro Martínez\r\nJulián Álvarez');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `seleccion`
--

CREATE TABLE `seleccion` (
  `ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `posiciones, nombres`
--
ALTER TABLE `posiciones, nombres`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `seleccion`
--
ALTER TABLE `seleccion`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `posiciones, nombres`
--
ALTER TABLE `posiciones, nombres`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `seleccion`
--
ALTER TABLE `seleccion`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `seleccion`
--
ALTER TABLE `seleccion`
  ADD CONSTRAINT `seleccion_ibfk_1` FOREIGN KEY (`ID`) REFERENCES `posiciones, nombres` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
