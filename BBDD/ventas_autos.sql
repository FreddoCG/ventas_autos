SET FOREIGN_KEY_CHECKS=0;
--
-- Base de datos: `ventas_autos`
--
CREATE DATABASE IF NOT EXISTS `ventas_autos` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `ventas_autos`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `auto`
--

CREATE TABLE `auto` (
  `id` int(11) NOT NULL,
  `marca` varchar(45) NOT NULL,
  `modelo` varchar(45) NOT NULL,
  `anio` varchar(5) NOT NULL,
  `descripcion` varchar(45) NOT NULL,
  `status` varchar(45) NOT NULL,
  `precio` varchar(30) NOT NULL,
  `fecha_registro` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Truncar tablas antes de insertar `auto`
--

TRUNCATE TABLE `auto`;
--
-- Volcado de datos para la tabla `auto`
--

INSERT INTO `auto` (`id`, `marca`, `modelo`, `anio`, `descripcion`, `status`, `precio`, `fecha_registro`) VALUES
(1, 'Chevrolet', 'Chevy Pop', '2000', 'Chevy 2000 1.5 4 cilindros.', 'vendido', '', '2018-06-15 00:53:12'),
(2, 'Chevrolet', 'Chevy Pop', '2002', 'Chevy 2000 1.5 4 cilindros.', 'disponible', '', '2018-06-15 00:53:28'),
(3, 'Chevrolet', 'Chevy Cavalier', '2002', 'Chevy 2002 2.0 4 cilindros.', 'vendido', '1000', '2018-06-15 00:53:42');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `id` int(11) NOT NULL,
  `nombres` varchar(200) NOT NULL,
  `apellidos` varchar(200) NOT NULL,
  `tel1` varchar(12) NOT NULL,
  `tel2` varchar(12) DEFAULT NULL,
  `correo` varchar(60) NOT NULL,
  `direccion` varchar(200) NOT NULL,
  `status` varchar(200) NOT NULL DEFAULT 'activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Truncar tablas antes de insertar `cliente`
--

TRUNCATE TABLE `cliente`;
--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`id`, `nombres`, `apellidos`, `tel1`, `tel2`, `correo`, `direccion`, `status`) VALUES
(2, 'Jhonni', 'Doe', 'asdsa@sfsdf.', NULL, 'asdsa@sfsdf.sdf', 'Calle 21 #203 entre 56 y 12 Col Los Pinos Merida Yuc.', 'activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleado`
--

CREATE TABLE `empleado` (
  `id` int(11) NOT NULL,
  `nombres` varchar(200) NOT NULL,
  `apellidos` varchar(200) NOT NULL,
  `correo` varchar(60) NOT NULL,
  `tel` varchar(45) DEFAULT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date DEFAULT NULL,
  `status` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Truncar tablas antes de insertar `empleado`
--

TRUNCATE TABLE `empleado`;
--
-- Volcado de datos para la tabla `empleado`
--

INSERT INTO `empleado` (`id`, `nombres`, `apellidos`, `correo`, `tel`, `fecha_inicio`, `fecha_fin`, `status`) VALUES
(2, 'llllll', 'lllll', 'jho7s@gmail.com', '45534534', '2018-06-15', NULL, 'baja'),
(3, 'Jhonnn', 'Doerd', 'jhondg2s@gmail.com', '45534534', '2018-06-15', NULL, 'activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `nombres` varchar(50) NOT NULL,
  `apellidos` varchar(50) NOT NULL,
  `correo` varchar(45) NOT NULL,
  `contrasena` varchar(200) NOT NULL,
  `creado` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Truncar tablas antes de insertar `usuario`
--

TRUNCATE TABLE `usuario`;
--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `nombres`, `apellidos`, `correo`, `contrasena`, `creado`) VALUES
(1, 'Admin', 'Admin', 'admin@admin.com', '12345678', '2018-06-12 19:35:23'),
(2, 'nombresss', 'apellidos', 'a@aaa.sa', 'ef797c8118f02dfb649607dd5d3f8c7623048c9c063d532cc95c5ed7a898a64f', '2018-06-14 22:08:37'),
(3, 'nombre', 'apellidos', 'a@aa.a', 'ef797c8118f02dfb649607dd5d3f8c7623048c9c063d532cc95c5ed7a898a64f', '2018-06-14 22:32:33');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta`
--

CREATE TABLE `venta` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `empleado_id` int(11) NOT NULL,
  `auto_id` int(11) NOT NULL,
  `fecha_venta` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `observacion` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Truncar tablas antes de insertar `venta`
--

TRUNCATE TABLE `venta`;
--
-- Volcado de datos para la tabla `venta`
--

INSERT INTO `venta` (`id`, `cliente_id`, `empleado_id`, `auto_id`, `fecha_venta`, `observacion`) VALUES
(4, 2, 3, 1, '2018-06-16 00:57:11', NULL),
(5, 2, 3, 3, '2018-06-16 02:33:19', NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `auto`
--
ALTER TABLE `auto`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `empleado`
--
ALTER TABLE `empleado`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `venta`
--
ALTER TABLE `venta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ventas_cliente_idx` (`cliente_id`),
  ADD KEY `fk_ventas_empleado1_idx` (`empleado_id`),
  ADD KEY `fk_ventas_auto1_idx` (`auto_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `auto`
--
ALTER TABLE `auto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `empleado`
--
ALTER TABLE `empleado`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `venta`
--
ALTER TABLE `venta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `venta`
--
ALTER TABLE `venta`
  ADD CONSTRAINT `fk_ventas_auto1` FOREIGN KEY (`auto_id`) REFERENCES `auto` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_ventas_cliente` FOREIGN KEY (`cliente_id`) REFERENCES `cliente` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_ventas_empleado1` FOREIGN KEY (`empleado_id`) REFERENCES `empleado` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
SET FOREIGN_KEY_CHECKS=1;
