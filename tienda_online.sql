-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 09-10-2024 a las 17:33:15
-- Versión del servidor: 10.4.20-MariaDB
-- Versión de PHP: 8.0.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `tienda_online`
--
CREATE DATABASE IF NOT EXISTS `tienda_online` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `tienda_online`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administrador`
--

DROP TABLE IF EXISTS `administrador`;
CREATE TABLE IF NOT EXISTS `administrador` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `administrador`
--

INSERT INTO `administrador` (`id`, `nombre`, `contrasena`) VALUES
(1, 'Administrador', 'vzFtrYjMBJNVEmEZA');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrito`
--

DROP TABLE IF EXISTS `carrito`;
CREATE TABLE IF NOT EXISTS `carrito` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  KEY `idx_producto_id` (`producto_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `carrito`
--

INSERT INTO `carrito` (`id`, `usuario_id`, `producto_id`, `cantidad`) VALUES
(1, 1, 2, 37),
(2, 2, 1, 2),
(3, 3, 3, 1),
(4, 1, 1, 12),
(5, 1, 3, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_pedido`
--

DROP TABLE IF EXISTS `detalle_pedido`;
CREATE TABLE IF NOT EXISTS `detalle_pedido` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pedido_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pedido_id` (`pedido_id`),
  KEY `producto_id` (`producto_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `detalle_pedido`
--

INSERT INTO `detalle_pedido` (`id`, `pedido_id`, `producto_id`, `cantidad`, `precio_unitario`) VALUES
(1, 1, 1, 2, '19.99'),
(2, 1, 3, 1, '79.99'),
(3, 2, 2, 1, '39.99'),
(4, 3, 4, 1, '14.99');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_estados`
--

DROP TABLE IF EXISTS `historial_estados`;
CREATE TABLE IF NOT EXISTS `historial_estados` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pedido_id` int(11) NOT NULL,
  `estado_anterior` enum('pendiente','procesando','completado','cancelado') NOT NULL,
  `estado_nuevo` enum('pendiente','procesando','completado','cancelado') NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `pedido_id` (`pedido_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

DROP TABLE IF EXISTS `pagos`;
CREATE TABLE IF NOT EXISTS `pagos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pedido_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `metodo_pago` enum('Tarjeta','PayPal','Transferencia','Criptomoneda') NOT NULL,
  `estado_pago` enum('Pendiente','Completado','Fallido') NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `pedido_id` (`pedido_id`),
  KEY `usuario_id` (`usuario_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

DROP TABLE IF EXISTS `pedidos`;
CREATE TABLE IF NOT EXISTS `pedidos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `fecha` datetime DEFAULT current_timestamp(),
  `total` decimal(10,2) NOT NULL,
  `estado` enum('pendiente','procesando','completado','cancelado') DEFAULT 'pendiente',
  `pago_confirmado` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_estado` (`estado`),
  KEY `idx_usuario_id` (`usuario_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `usuario_id`, `fecha`, `total`, `estado`, `pago_confirmado`) VALUES
(1, 1, '2024-09-08 18:40:08', '59.98', 'completado', 0),
(2, 2, '2024-09-08 18:40:08', '19.99', 'pendiente', 0),
(3, 3, '2024-09-08 18:40:08', '39.99', 'procesando', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

DROP TABLE IF EXISTS `productos`;
CREATE TABLE IF NOT EXISTS `productos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `color` varchar(50) DEFAULT NULL,
  `marca` varchar(50) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `categoria` enum('Ropa') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `color`, `marca`, `descripcion`, `precio`, `stock`, `imagen`, `categoria`) VALUES
(1, 'Camiseta', NULL, NULL, 'Camiseta de algodón 100% en varios colores.', '20.00', 50, 'https://th.bing.com/th/id/OIP.EbPZeQL2pRW-kRm3HHZ36gHaIe?rs=1&pid=ImgDetMain', 'Ropa'),
(2, 'Pantalones', NULL, NULL, 'Pantalones de mezclilla, ajustados.', '39.99', 30, 'https://th.bing.com/th/id/OIP.gufFbsGJwfh7zR2_tlfYSgHaI1?w=604&h=720&rs=1&pid=ImgDetMain', 'Ropa'),
(3, 'Zapatos', NULL, NULL, 'Zapatos de cuero genuino.', '79.99', 20, 'https://th.bing.com/th/id/R.1d2fc9ca9143b67d24d7e2e4c0ae9f7a?rik=njACOVA2t5q9GQ&riu=http%3a%2f%2fwww.zapatosecco.shoes%2fwp-content%2fuploads%2f2015%2f03%2fzapatous.jpg&ehk=d5hit4DkRESGqEc7LBEfy2mOT7QKS5l9TJ3DXxQAYAM%3d&risl=&pid=ImgRaw&r=0', 'Ropa'),
(4, 'Gorra', NULL, NULL, 'Gorra con visera ajustable.', '14.99', 100, 'https://th.bing.com/th/id/OIP.rFcMQLbttf4xcjGZOQ9USwHaGJ?rs=1&pid=ImgDetMain', 'Ropa'),
(11, 'Pantalon', NULL, NULL, 'Pantalón de mujer', '8.00', 0, 'https://th.bing.com/th/id/R.f06c75e80d6017763d1b02180b6c737b?rik=hTmZTYQ6ZUjDIw&pid=ImgRaw&r=0', 'Ropa'),
(13, 'Traje Elegante', NULL, NULL, 'Traje de 2 Piezas', '50.00', 0, 'https://m.media-amazon.com/images/I/51PlmNm4J7L._AC_UL1200_.jpg', 'Ropa');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `direccion` varchar(255) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `preferencias_comunicacion` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `contrasena`, `direccion`, `telefono`, `fecha_nacimiento`, `preferencias_comunicacion`) VALUES
(1, 'Juan Pérez', 'juan@example.com', 'password123', 'Calle Falsa 123, Ciudad', '555-1234', NULL, NULL),
(2, 'María López', 'maria@example.com', 'password456', 'Avenida Siempre Viva 456, Ciudad', '555-5678', NULL, NULL),
(3, 'Carlos García', 'carlos@example.com', 'password789', 'Boulevard Principal 789, Ciudad', '555-9876', NULL, NULL);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD CONSTRAINT `carrito_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `carrito_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  ADD CONSTRAINT `detalle_pedido_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`),
  ADD CONSTRAINT `detalle_pedido_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `historial_estados`
--
ALTER TABLE `historial_estados`
  ADD CONSTRAINT `historial_estados_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD CONSTRAINT `pagos_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pagos_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
