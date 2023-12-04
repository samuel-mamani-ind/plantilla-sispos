-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 30-03-2023 a las 06:53:59
-- Versión del servidor: 10.1.38-MariaDB
-- Versión de PHP: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sispos`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `acceso`
--

CREATE TABLE `acceso` (
  `idperfil` int(11) NOT NULL,
  `idopcion` int(11) NOT NULL,
  `estado` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `acceso`
--

INSERT INTO `acceso` (`idperfil`, `idopcion`, `estado`) VALUES
(1, 1, 1),
(1, 2, 1),
(1, 3, 1),
(1, 4, 1),
(1, 5, 1),
(1, 6, 1),
(1, 7, 1),
(1, 8, 1),
(1, 9, 1),
(2, 5, 1),
(2, 6, 1),
(3, 5, 1),
(3, 6, 1),
(3, 9, 1),
(4, 1, 1),
(4, 2, 1),
(4, 6, 0),
(4, 7, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `afectacion`
--

CREATE TABLE `afectacion` (
  `idafectacion` int(11) NOT NULL,
  `descripcion` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `afectacion`
--

INSERT INTO `afectacion` (`idafectacion`, `descripcion`) VALUES
(10, 'GRAVADAS'),
(20, 'EXONERADAS'),
(30, 'INAFECTAS');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `idcategoria` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `estado` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`idcategoria`, `nombre`, `estado`) VALUES
(1, 'METALES', 2),
(2, 'CHOCOLATES', 2),
(3, 'UTILES DE LIMPIEZA', 0),
(4, 'UTILES DE ASEO', 2),
(5, 'FRUTAS', 2),
(6, 'FRUTAS', 2),
(7, 'VERDURAS', 2),
(8, 'POSTRES', 0),
(9, 'CARNES', 1),
(10, 'GASEOSAS', 1),
(11, 'DULCES', 1),
(12, 'GOLOSINAS', 1),
(13, 'REPUESTOS', 1),
(14, 'MENESTRAS', 1),
(15, 'GALLETAS', 1),
(16, 'GALLETAS2', 2),
(17, 'LACTEOS', 1),
(18, 'LACTEOS X', 2),
(19, 'LACTEOS X', 2),
(20, 'GALLETAS X', 1),
(21, 'GALLETAS Z', 1),
(22, 'CATEGORIA ABC', 2),
(23, 'CATEGORIA XYZ', 2),
(24, 'PASTILLAS', 1),
(25, 'PRUEBA', 1),
(26, 'CATEGORIA ABC', 2),
(27, 'PRUEBA ABC', 2),
(28, '', 2),
(29, 'FRUTAS', 2),
(30, 'FRUTAS', 2),
(31, '', 2),
(32, 'PRUEBA XYZ', 2),
(33, 'prueba bbb', 2),
(34, 'pruebaqqw', 2),
(35, 'ABARROTESXZA', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `idcliente` int(11) NOT NULL,
  `nombre` varchar(200) DEFAULT NULL,
  `idtipodocumento` char(1) DEFAULT NULL,
  `nrodocumento` varchar(20) DEFAULT NULL,
  `direccion` varchar(200) DEFAULT NULL,
  `estado` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`idcliente`, `nombre`, `idtipodocumento`, `nrodocumento`, `direccion`, `estado`) VALUES
(1, 'JUAN PEREZ', '1', '12345699', 'MANUEL NRO 123 - CERCADO DE LIMA', 1),
(2, 'TAQINI TECHNOLOGY S.A.C.', '6', '20602814425', 'CAL.JUAN CUGLIEVAN NRO. 216 CERCADO DE CHICLAYO  (OFICINA NRO. 301)  LAMBAYEQUE - CHICLAYO - CHICLAYO', 1),
(3, 'JUNTA DE USUARIOS DEL SECTOR HIDRAULICO MENOR SAN LORENZO', '6', '20161500292', 'AV.REFORMA AGRARIA NRO. S N CRUCETA  (EX DRENAJE)  PIURA - PIURA - TAMBO GRANDE', 1),
(4, 'EUSEBIO KELVIN RIVADENEIRA FABIAN', '1', '75123787', 'Aguaytia - UCAYALI', 1),
(5, 'ELVIS ENRIQUE VALENTIN MALDONADO', '1', '46874321', 'PISCO', 1),
(6, 'ESWIN YASMANI MORALES VINCES', '1', '41981450', 'TUMBES', 1),
(7, 'CARLOS', '1', '12345695', 'PERU', 1),
(8, 'ASSEL', '1', '12345678', 'PERU', 1),
(9, 'CHAVITO', '1', '44332211', 'PERU', 1),
(10, 'JOSE PEREZ', '1', '12312333', 'CHICLAYO', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle`
--

CREATE TABLE `detalle` (
  `iddetalle` int(11) NOT NULL,
  `idventa` int(11) DEFAULT NULL,
  `idproducto` int(11) DEFAULT NULL,
  `cantidad` decimal(15,2) DEFAULT NULL,
  `unidad` char(3) DEFAULT NULL,
  `pventa` decimal(15,2) DEFAULT NULL,
  `igv` decimal(15,2) DEFAULT NULL,
  `icbper` decimal(15,2) DEFAULT NULL,
  `descuento` decimal(15,2) DEFAULT NULL,
  `total` decimal(15,2) DEFAULT NULL,
  `idafectacion` int(11) DEFAULT NULL,
  `estado` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `detalle`
--

INSERT INTO `detalle` (`iddetalle`, `idventa`, `idproducto`, `cantidad`, `unidad`, `pventa`, `igv`, `icbper`, `descuento`, `total`, `idafectacion`, `estado`) VALUES
(1, 1, 8, '1.00', 'NIU', '10.00', '1.53', '0.00', '0.00', '10.00', 10, 1),
(2, 2, 1, '3.00', 'NIU', '3.00', '1.37', '0.00', '0.00', '9.00', 10, 1),
(3, 3, 1, '1.00', 'NIU', '3.00', '0.46', '0.00', '0.00', '3.00', 10, 1),
(4, 3, 9, '1.00', 'NIU', '15.00', '2.29', '0.00', '0.00', '15.00', 10, 1),
(5, 4, 6, '2.00', 'NIU', '6.00', '1.83', '0.00', '0.00', '12.00', 10, 1),
(6, 5, 1, '7.00', 'NIU', '3.00', '3.20', '0.00', '0.00', '21.00', 10, 1),
(7, 6, 3, '2.00', 'NIU', '0.60', '0.18', '0.00', '0.00', '1.20', 10, 1),
(8, 6, 6, '3.00', 'NIU', '6.00', '2.75', '0.00', '0.00', '18.00', 10, 1),
(9, 7, 8, '4.00', 'NIU', '10.00', '6.10', '0.00', '0.00', '40.00', 10, 1),
(10, 8, 6, '3.00', 'NIU', '6.00', '2.75', '0.00', '0.00', '18.00', 10, 1),
(11, 9, 9, '7.00', 'NIU', '15.00', '16.02', '0.00', '0.00', '105.00', 10, 1),
(12, 10, 9, '3.00', 'NIU', '15.00', '6.86', '0.00', '0.00', '45.00', 10, 1),
(13, 10, 6, '2.00', 'NIU', '6.00', '1.83', '0.00', '0.00', '12.00', 10, 1),
(14, 10, 8, '1.00', 'NIU', '10.00', '1.53', '0.00', '0.00', '10.00', 10, 1),
(15, 11, 8, '1.00', 'NIU', '10.00', '1.53', '0.00', '0.00', '10.00', 10, 1),
(16, 11, 9, '2.00', 'NIU', '15.00', '4.58', '0.00', '0.00', '30.00', 10, 1),
(17, 11, 6, '1.00', 'NIU', '6.00', '0.92', '0.00', '0.00', '6.00', 10, 1),
(18, 12, 8, '1.00', 'NIU', '10.00', '1.53', '0.00', '0.00', '10.00', 10, 1),
(19, 12, 9, '1.00', 'NIU', '15.00', '2.29', '0.00', '0.00', '15.00', 10, 1),
(20, 13, 8, '1.00', 'NIU', '10.00', '1.53', '0.00', '0.00', '10.00', 10, 1),
(21, 13, 9, '2.00', 'NIU', '15.00', '4.58', '0.00', '0.00', '30.00', 10, 1),
(22, 14, 9, '1.00', 'NIU', '15.00', '2.29', '0.00', '0.00', '15.00', 10, 1),
(23, 15, 9, '1.00', 'NIU', '15.00', '2.29', '0.00', '0.00', '15.00', 10, 1),
(24, 16, 9, '2.00', 'NIU', '15.00', '4.58', '0.00', '0.00', '30.00', 10, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `moneda`
--

CREATE TABLE `moneda` (
  `idmoneda` char(3) NOT NULL,
  `nombre` varchar(20) DEFAULT NULL,
  `estado` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `moneda`
--

INSERT INTO `moneda` (`idmoneda`, `nombre`, `estado`) VALUES
('PEN', 'SOLES', 1),
('USD', 'DOLARES', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `opcion`
--

CREATE TABLE `opcion` (
  `idopcion` int(11) NOT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  `icono` varchar(20) DEFAULT NULL,
  `url` varchar(150) DEFAULT NULL,
  `estado` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `opcion`
--

INSERT INTO `opcion` (`idopcion`, `descripcion`, `icono`, `url`, `estado`) VALUES
(1, 'Categorias', 'fa-tags', 'vista/categorias.php', 1),
(2, 'Productos', 'fa-list', 'vista/productos.php', 1),
(3, 'Perfiles', 'fa-user-lock', 'vista/perfiles.php', 1),
(4, 'Usuarios', 'fa-user-circle', 'vista/usuarios.php', 1),
(5, 'Clientes', 'fa-users', 'vista/clientes.php', 1),
(6, 'Ventas', 'fa-cart-plus', 'vista/ventas.php', 1),
(7, 'Inventario', 'fa-boxes', 'vista/inventario.php', 1),
(8, 'Reportes', 'fa-chart-bar', 'vista/reportes.php', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perfil`
--

CREATE TABLE `perfil` (
  `idperfil` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `estado` smallint(6) DEFAULT NULL COMMENT '0 -> INACTIVO \n1 -> ACTIVO\n2 -> ELIMINADO'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `perfil`
--

INSERT INTO `perfil` (`idperfil`, `nombre`, `estado`) VALUES
(1, 'ADMINISTRADOR', 1),
(2, 'VENDEDOR', 1),
(3, 'CAJERO', 0),
(4, 'ALMACENERO', 1),
(5, 'PRUEBA', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `idproducto` int(11) NOT NULL,
  `nombre` varchar(200) DEFAULT NULL,
  `codigobarra` varchar(100) DEFAULT NULL,
  `pventa` decimal(15,2) DEFAULT NULL,
  `pcompra` decimal(15,2) DEFAULT NULL,
  `stock` decimal(15,2) DEFAULT NULL,
  `idunidad` char(3) DEFAULT NULL,
  `urlimagen` varchar(200) DEFAULT NULL,
  `idcategoria` int(11) DEFAULT NULL,
  `idafectacion` int(11) DEFAULT NULL,
  `afectoicbper` smallint(6) DEFAULT NULL,
  `estado` smallint(6) DEFAULT NULL,
  `stockseguridad` decimal(15,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`idproducto`, `nombre`, `codigobarra`, `pventa`, `pcompra`, `stock`, `idunidad`, `urlimagen`, `idcategoria`, `idafectacion`, `afectoicbper`, `estado`, `stockseguridad`) VALUES
(1, 'GASEOSA COCA COLA 1.5L', '292992929292', '3.00', '2.00', '18.00', 'NIU', 'imagen/productos/IMG_1_cepillo_vitis_duro.jpg', 10, 10, 0, 0, '12.00'),
(2, 'GASEOSA INKA KOLA 1L', '9099393993', '5.00', '4.00', '24.00', 'NIU', NULL, 10, 10, NULL, 2, NULL),
(3, 'GALLETA RELLENITA DE CHOCOLATE', '98238489234', '0.60', '0.40', '4.00', 'NIU', 'imagen/productos/IMG_3_rellenita.jpg', 15, 10, 0, 1, NULL),
(4, 'GALLETITAS DE ANIMALITOS', '98238489231', '1.00', '0.80', '0.00', 'NIU', 'imagen/productos/IMG_4_animalitos.jpg', 15, 10, 0, 1, NULL),
(5, 'BOLSA PLASTICA', 'B9999', '0.80', '0.60', '10.00', 'NIU', 'imagen/productos/IMG_5_BOLSA.jpg', 13, 10, 1, 1, '50.00'),
(6, 'PILSEN CALLAO 620', '', '6.00', '5.00', '4.00', 'NIU', NULL, 10, 10, 0, 1, NULL),
(7, 'INKA COLA 1L', '11111111', '5.00', '4.00', '8.00', 'NIU', NULL, 10, 10, 0, 1, '12.00'),
(8, 'CONCORDIA 2L MARACUYA', 'C0000111', '10.00', '9.00', '5.00', 'NIU', NULL, 10, 10, 0, 1, NULL),
(9, 'MARTILLO', '98899888', '15.00', '10.00', '29.00', 'NIU', NULL, 0, 10, 0, 1, '20.00'),
(10, 'OREO', NULL, '1.00', '0.50', '45.00', 'NIU', NULL, 15, 10, 0, 2, '20.00'),
(11, 'OREO', 'MNDASF32', '1.00', '0.50', '45.00', 'NIU', NULL, 15, 10, 0, 2, '20.00'),
(12, 'OREO ABC', 'MNDASF32', '1.00', '0.50', '45.00', 'BOX', NULL, 15, 10, 0, 1, '20.00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `serie`
--

CREATE TABLE `serie` (
  `idserie` int(11) NOT NULL,
  `idtipocomprobante` char(2) DEFAULT NULL,
  `serie` varchar(6) DEFAULT NULL,
  `correlativo` int(11) DEFAULT NULL,
  `estado` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `serie`
--

INSERT INTO `serie` (`idserie`, `idtipocomprobante`, `serie`, `correlativo`, `estado`) VALUES
(1, '03', 'B001', 146, 1),
(2, '03', 'B002', 90, 1),
(3, '01', 'F001', 35, 1),
(4, '01', 'F002', 45, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipocomprobante`
--

CREATE TABLE `tipocomprobante` (
  `idtipocomprobante` char(2) NOT NULL,
  `nombre` varchar(45) DEFAULT NULL,
  `estado` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tipocomprobante`
--

INSERT INTO `tipocomprobante` (`idtipocomprobante`, `nombre`, `estado`) VALUES
('01', 'FACTURA', 1),
('03', 'BOLETA', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipodocumento`
--

CREATE TABLE `tipodocumento` (
  `idtipodocumento` char(1) NOT NULL,
  `nombre` varchar(45) DEFAULT NULL,
  `estado` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tipodocumento`
--

INSERT INTO `tipodocumento` (`idtipodocumento`, `nombre`, `estado`) VALUES
('0', 'SIN DOCUMENTO', 1),
('1', 'DNI', 1),
('4', 'CARNET DE EXTRANJERIA', 1),
('6', 'RUC', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `unidad`
--

CREATE TABLE `unidad` (
  `idunidad` char(3) NOT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  `estado` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `unidad`
--

INSERT INTO `unidad` (`idunidad`, `descripcion`, `estado`) VALUES
('BOX', 'CAJA', 1),
('KGM', 'KILOGRAMO', 1),
('NIU', 'UNIDAD', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `idusuario` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `usuario` varchar(50) DEFAULT NULL,
  `clave` text,
  `idperfil` int(11) NOT NULL,
  `estado` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`idusuario`, `nombre`, `usuario`, `clave`, `idperfil`, `estado`) VALUES
(1, 'ANTONIO', 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 1, 1),
(2, 'CARLOS ALBERTO', 'carlosalberto', '7c4a8d09ca3762af61e59520943dc26494f8941b', 2, 1),
(3, 'Pedro Perez', 'pedrito', '7c4a8d09ca3762af61e59520943dc26494f8941b', 4, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta`
--

CREATE TABLE `venta` (
  `idventa` int(11) NOT NULL,
  `fecha` date DEFAULT NULL,
  `idcliente` int(11) DEFAULT NULL,
  `idtipocomprobante` char(2) DEFAULT NULL,
  `serie` varchar(6) DEFAULT NULL,
  `correlativo` int(11) DEFAULT NULL,
  `total` decimal(15,2) DEFAULT NULL,
  `total_gravado` decimal(15,2) DEFAULT NULL,
  `total_exonerado` decimal(15,2) DEFAULT NULL,
  `total_inafecto` decimal(15,2) DEFAULT NULL,
  `total_igv` decimal(15,2) DEFAULT NULL,
  `total_icbper` decimal(15,2) DEFAULT NULL,
  `total_descuento` decimal(15,2) DEFAULT NULL,
  `formapago` char(1) DEFAULT NULL,
  `idmoneda` char(3) DEFAULT NULL,
  `vencimiento` date DEFAULT NULL,
  `guiaremision` varchar(20) DEFAULT NULL,
  `ordencompra` varchar(20) DEFAULT NULL,
  `idusuario` int(11) DEFAULT NULL,
  `estado` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `venta`
--

INSERT INTO `venta` (`idventa`, `fecha`, `idcliente`, `idtipocomprobante`, `serie`, `correlativo`, `total`, `total_gravado`, `total_exonerado`, `total_inafecto`, `total_igv`, `total_icbper`, `total_descuento`, `formapago`, `idmoneda`, `vencimiento`, `guiaremision`, `ordencompra`, `idusuario`, `estado`) VALUES
(1, '2022-01-05', 7, '03', 'B001', 136, '10.00', '8.47', '0.00', '0.00', '1.53', '0.00', '0.00', 'C', 'PEN', '0000-00-00', '', '', 1, 1),
(2, '2022-01-20', 7, '03', 'B001', 137, '9.00', '7.63', '0.00', '0.00', '1.37', '0.00', '0.00', 'C', 'PEN', '0000-00-00', '', '', 1, 1),
(3, '2022-02-16', 1, '03', 'B001', 138, '18.00', '15.25', '0.00', '0.00', '2.75', '0.00', '0.00', 'C', 'PEN', '0000-00-00', '', '', 1, 1),
(4, '2022-03-24', 7, '03', 'B001', 139, '12.00', '10.17', '0.00', '0.00', '1.83', '0.00', '0.00', 'C', 'PEN', '0000-00-00', '', '', 1, 1),
(5, '2022-04-20', 7, '03', 'B001', 140, '21.00', '17.80', '0.00', '0.00', '3.20', '0.00', '0.00', 'C', 'PEN', '0000-00-00', '', '', 1, 1),
(6, '2022-05-26', 7, '03', 'B001', 141, '19.20', '16.27', '0.00', '0.00', '2.93', '0.00', '0.00', 'C', 'PEN', '0000-00-00', '', '', 1, 1),
(7, '2022-06-23', 7, '03', 'B001', 142, '40.00', '33.90', '0.00', '0.00', '6.10', '0.00', '0.00', 'C', 'PEN', '0000-00-00', '', '', 1, 1),
(8, '2022-07-19', 7, '03', 'B001', 143, '18.00', '15.25', '0.00', '0.00', '2.75', '0.00', '0.00', 'C', 'PEN', '0000-00-00', '', '', 1, 1),
(9, '2022-11-30', 7, '03', 'B001', 144, '105.00', '88.98', '0.00', '0.00', '16.02', '0.00', '0.00', 'C', 'PEN', '0000-00-00', '', '', 1, 1),
(10, '2022-08-24', 7, '03', 'B001', 145, '67.00', '56.78', '0.00', '0.00', '10.22', '0.00', '0.00', 'C', 'PEN', '0000-00-00', '', '', 1, 1),
(11, '2022-09-22', 2, '03', 'B001', 146, '46.00', '38.97', '0.00', '0.00', '7.03', '0.00', '0.00', 'C', 'PEN', '0000-00-00', '', '', 1, 1),
(12, '2022-10-25', 2, '01', 'F001', 31, '25.00', '21.18', '0.00', '0.00', '3.82', '0.00', '0.00', 'C', 'PEN', '0000-00-00', '', '', 1, 1),
(13, '2022-11-30', 2, '01', 'F001', 32, '40.00', '33.89', '0.00', '0.00', '6.11', '0.00', '0.00', 'C', 'PEN', '0000-00-00', '', '', 1, 1),
(14, '2022-01-01', 2, '01', 'F001', 33, '15.00', '12.71', '0.00', '0.00', '2.29', '0.00', '0.00', 'C', 'PEN', '0000-00-00', '', '', 1, 1),
(15, '2021-01-01', 2, '01', 'F001', 34, '15.00', '12.71', '0.00', '0.00', '2.29', '0.00', '0.00', 'C', 'PEN', '0000-00-00', '', '', 1, 1),
(16, '2022-11-15', 2, '01', 'F001', 35, '30.00', '25.42', '0.00', '0.00', '4.58', '0.00', '0.00', 'C', 'PEN', '0000-00-00', '', '', 1, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `acceso`
--
ALTER TABLE `acceso`
  ADD PRIMARY KEY (`idperfil`,`idopcion`);

--
-- Indices de la tabla `afectacion`
--
ALTER TABLE `afectacion`
  ADD PRIMARY KEY (`idafectacion`);

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`idcategoria`);

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`idcliente`);

--
-- Indices de la tabla `detalle`
--
ALTER TABLE `detalle`
  ADD PRIMARY KEY (`iddetalle`);

--
-- Indices de la tabla `moneda`
--
ALTER TABLE `moneda`
  ADD PRIMARY KEY (`idmoneda`);

--
-- Indices de la tabla `opcion`
--
ALTER TABLE `opcion`
  ADD PRIMARY KEY (`idopcion`);

--
-- Indices de la tabla `perfil`
--
ALTER TABLE `perfil`
  ADD PRIMARY KEY (`idperfil`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`idproducto`);

--
-- Indices de la tabla `serie`
--
ALTER TABLE `serie`
  ADD PRIMARY KEY (`idserie`);

--
-- Indices de la tabla `tipocomprobante`
--
ALTER TABLE `tipocomprobante`
  ADD PRIMARY KEY (`idtipocomprobante`);

--
-- Indices de la tabla `tipodocumento`
--
ALTER TABLE `tipodocumento`
  ADD PRIMARY KEY (`idtipodocumento`);

--
-- Indices de la tabla `unidad`
--
ALTER TABLE `unidad`
  ADD PRIMARY KEY (`idunidad`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`idusuario`);

--
-- Indices de la tabla `venta`
--
ALTER TABLE `venta`
  ADD PRIMARY KEY (`idventa`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `idcategoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `idcliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `detalle`
--
ALTER TABLE `detalle`
  MODIFY `iddetalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `opcion`
--
ALTER TABLE `opcion`
  MODIFY `idopcion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `perfil`
--
ALTER TABLE `perfil`
  MODIFY `idperfil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `idproducto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `serie`
--
ALTER TABLE `serie`
  MODIFY `idserie` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `idusuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `venta`
--
ALTER TABLE `venta`
  MODIFY `idventa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
