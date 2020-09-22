-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-01-2020 a las 21:35:37
-- Versión del servidor: 10.4.8-MariaDB
-- Versión de PHP: 7.3.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `adbai`
--

CREATE DATABASE IF NOT EXISTS `adbai` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci;
USE `adbai`;
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `anuncios`
--

CREATE TABLE `anuncios` (
  `id` int(11) NOT NULL,
  `titulo` varchar(60) COLLATE utf8_spanish_ci NOT NULL,
  `descripcion` varchar(500) COLLATE utf8_spanish_ci NOT NULL,
  `imagen` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `precio` double NOT NULL,
  `telefono` int(9) NOT NULL,
  `correo` varchar(60) COLLATE utf8_spanish_ci NOT NULL,
  `fecha_ini` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `renovaciones` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `categoria` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `anuncios`
--

INSERT INTO `anuncios` (`id`, `titulo`, `descripcion`, `imagen`, `precio`, `telefono`, `correo`, `fecha_ini`, `fecha_fin`, `renovaciones`, `userid`, `categoria`) VALUES
(1, 'IPHONE X', 'Iphone x seminuevo, con poco uso', '6028d5454cb1d9d0f1b80de85e5d5440 2020-1-27 2-34-38.jpeg', 500, 678123456, 'MARCOS@GMAIL.COM', '2020-01-27', '2020-02-11', 0, 4, 7),
(2, 'Playstation 4', 'Playstation 4 , se vende por poco uso , se acepta intercambio', 'b70b950f87df1906d279d3cceb3435b1 2020-1-27 2-37-30.jpeg', 150, 678123456, 'MARCOS@GMAIL.COM', '2020-01-27', '2020-02-11', 0, 4, 10),
(3, 'Bicicleta', 'Se vence bicicleta de montaña de marca Orbea, por compra de una nueva', 'b66343ca20fcc2ef84f9122380cadf72 2020-1-27 2-40-31.jpeg', 250, 655641232, 'MANUELPRUNA@GMAIL.COM', '2020-01-27', '2020-02-11', 0, 5, 9),
(4, 'Piso Urbion', 'Piso situado en la zona de urbion, 3 dormitorios, 1 cuarto de baño,1 salon amplio y una buena terraza', 'a06c6e0e5956001de62fad915a15abad 2020-1-27 2-42-47.jpeg', 190000, 655641232, 'MANUELPRUNA@GMAIL.COM', '2020-01-27', '2020-02-11', 0, 5, 5),
(5, 'Ford Mustang', 'Ford Mustang GT , con tan solo 90000KM siendo del 2013, estado del motor excelente', '03e03f7769b7e943296aa31bb8a1dd16 2020-1-27 2-46-17.jpeg', 28000, 655634234, 'JOSEMA@GMAIL.COM', '2020-01-27', '2020-02-11', 0, 6, 1),
(6, 'Banco Musculacion', 'Se vende banco de musculacion por poco uso , estado idoneo para utilizarlo', '3d9402337b543196697830485a396383 2020-1-27 2-50-56.jpeg', 90, 655634234, 'JOSEMA@GMAIL.COM', '2020-01-27', '2020-02-11', 0, 6, 9);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `categoria` varchar(60) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `categoria`) VALUES
(13, 'Cine, Libros y Musica'),
(1, 'Coches'),
(10, 'Consolas y Videojuegos'),
(9, 'Deporte y Ocio'),
(12, 'Electrodomesticos'),
(14, 'Empleo'),
(11, 'Hogar y Jardin'),
(8, 'Informatica y Electronica'),
(5, 'Inmobiliaria'),
(4, 'Moda y Accesorios'),
(3, 'Motor y Accesorios'),
(2, 'Motos'),
(7, 'Moviles y Telefonia'),
(6, 'TV, Audio y Foto');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `dni` varchar(9) COLLATE utf8_spanish_ci NOT NULL,
  `nombre` varchar(60) COLLATE utf8_spanish_ci NOT NULL,
  `apellido1` varchar(60) COLLATE utf8_spanish_ci NOT NULL,
  `apellido2` varchar(60) COLLATE utf8_spanish_ci NOT NULL,
  `sexo` varchar(1) COLLATE utf8_spanish_ci NOT NULL,
  `fecha_nac` date NOT NULL,
  `direccion` varchar(60) COLLATE utf8_spanish_ci NOT NULL,
  `codpostal` int(5) NOT NULL,
  `telefono` int(9) NOT NULL,
  `correo` varchar(60) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `dni`, `nombre`, `apellido1`, `apellido2`, `sexo`, `fecha_nac`, `direccion`, `codpostal`, `telefono`, `correo`) VALUES
(4, '49122338L', 'MANUEL', 'HERRERA', 'PULIDO', 'M', '1996-12-11', 'CALLE JOSE DIAZ', 41670, 655641232, 'MANUELPRUNA@GMAIL.COM'),
(5, '49122335G', 'JOSE MANUEL', 'FERNANDEZ', 'LABRADOR', 'M', '1995-05-03', 'CALLE BETIS', 41010, 655634234, 'JOSEMA@GMAIL.COM'),
(6, '49123438L', 'MARCOS', 'MORENO', 'PORRAS', 'M', '1999-06-12', 'CALLE LLANA', 41010, 678123456, 'MARCOS@GMAIL.COM'),
(7, '49122399K', 'LUIS', 'SANCHEZ', 'RODRIGUEZ', 'M', '1996-01-12', 'CALLE CORTA', 41670, 677529963, 'LUIS@GMAIL.COM');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `departamentos`
--

CREATE TABLE `departamentos` (
  `dept_no` int(11) NOT NULL,
  `nombre` varchar(60) COLLATE utf8_spanish_ci NOT NULL,
  `descripcion` varchar(500) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `departamentos`
--

INSERT INTO `departamentos` (`dept_no`, `nombre`, `descripcion`) VALUES
(1, 'Administradores', 'Tienen acceso a todas las funcionalidades del sistema'),
(2, 'Tecnicos', 'Son los encargados de controlar los anuncios de los clientes y a los propios clientes'),
(3, 'Administrativos', 'Son los encargados de dar de alta nuevos empleados. Tambien se ocupan de las categorias');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleados`
--

CREATE TABLE `empleados` (
  `id` int(11) NOT NULL,
  `dni` varchar(9) COLLATE utf8_spanish_ci NOT NULL,
  `nombre` varchar(60) COLLATE utf8_spanish_ci NOT NULL,
  `apellido1` varchar(60) COLLATE utf8_spanish_ci NOT NULL,
  `apellido2` varchar(60) COLLATE utf8_spanish_ci NOT NULL,
  `sexo` varchar(1) COLLATE utf8_spanish_ci NOT NULL,
  `fecha_nac` date NOT NULL,
  `direccion` varchar(60) COLLATE utf8_spanish_ci NOT NULL,
  `codpostal` int(5) NOT NULL,
  `telefono` int(9) NOT NULL,
  `correo` varchar(60) COLLATE utf8_spanish_ci NOT NULL,
  `salario` double NOT NULL,
  `departamento` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `empleados`
--

INSERT INTO `empleados` (`id`, `dni`, `nombre`, `apellido1`, `apellido2`, `sexo`, `fecha_nac`, `direccion`, `codpostal`, `telefono`, `correo`, `salario`, `departamento`) VALUES
(1, '52251807Q', 'DANIEL', 'BARCIELA', 'RUEDA', 'M', '1995-11-04', 'C CADIZ', 41010, 677524578, 'DANIEL@GMAIL.COM', 1200, 1),
(2, '22222222T', 'ALEJANDRO', 'TORRALBA', 'LUNA', 'M', '1990-01-01', 'ALGODONALES', 40123, 655631211, 'ALEJANDRO@GMAIL.COM', 900, 2),
(3, '56452343R', 'ANTONIO', 'VERDUGO', 'ESCALERA', 'M', '1999-01-01', 'C LORCA', 41789, 956179874, 'ANTONIO@GMAIL.COM', 1200, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(60) COLLATE utf8_spanish_ci NOT NULL,
  `clave` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `tipo` varchar(1) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `clave`, `tipo`) VALUES
(1, 'daniel', '$2y$10$ydEo7IjYchuDHAlHQcuzp.ZFEJSJXgmRKR/vPMeLQwAzxTDVY8JYG', 'E'),
(2, 'alejandro', '$2y$10$V8RKKmEmYfb5g/bLna2.J.WS4CnLUVEMdxiX9lt0bB1fNnV0tCqwW', 'E'),
(3, 'antonio', '$2y$10$qeTOfjYMudmhd94KUX1LEOOBBvNo.lNtPKR/m52r9RSXDQCBka0yK', 'E'),
(4, 'manuel', '$2y$10$6Bzpmmhcuygf8AfXoQM/zeSTOjRXGfsIASLGe/HPQG2sJhSlMiJY2', 'C'),
(5, 'josema', '$2y$10$Sl9Mnc0tUFqm60T64ht9U.MFH0VDa2oCpiCx.g57kFuxQowk9/jCe', 'C'),
(6, 'marcos', '$2y$10$HldQicRjG8b7pF8HUm1snudVRajE52bLZBdx4m6uVAYfT2H6pilc6', 'C'),
(7, 'luis', '$2y$10$63HX7DFDAkGjMjaXxUaQ3.WxSth3KzwpjugIH5YNT4L/RGYdy1wEy', 'C');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `anuncios`
--
ALTER TABLE `anuncios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categoria` (`categoria`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `dni` (`dni`);

--
-- Indices de la tabla `departamentos`
--
ALTER TABLE `departamentos`
  ADD PRIMARY KEY (`dept_no`);

--
-- Indices de la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `dni` (`dni`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `anuncios`
--
ALTER TABLE `anuncios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `departamentos`
--
ALTER TABLE `departamentos`
  MODIFY `dept_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
