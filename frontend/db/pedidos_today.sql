-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-10-2024 a las 05:48:03
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
-- Base de datos: `pedidos_today`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `id_product` int(11) DEFAULT NULL,
  `cant_products` int(11) DEFAULT NULL,
  `total` decimal(10,2) NOT NULL,
  `date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `orders`
--

INSERT INTO `orders` (`id`, `id_product`, `cant_products`, `total`, `date`) VALUES
(11, 1, 3, 9000.00, '2024-09-30'),
(12, 1, 5, 15000.00, '2024-10-17'),
(13, 2, 3, 9000.00, '2024-10-08'),
(14, 2, 4, 12000.00, '2024-10-05'),
(15, 5, 1, 500.00, '2024-10-10'),
(16, 5, 3, 1500.00, '2024-10-25'),
(17, 6, 1, 6000.00, '2024-09-29'),
(18, 6, 1, 6000.00, '2024-11-01'),
(19, 7, 6, 6000.00, '2024-10-11'),
(20, 7, 6, 6000.00, '2024-10-12'),
(21, 8, 7, 8400.00, '2024-10-02'),
(22, 8, 2, 2400.00, '2024-11-09');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `price` double DEFAULT NULL,
  `description` varchar(150) DEFAULT NULL,
  `image_product` varchar(120) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `product`
--

INSERT INTO `product` (`id`, `name`, `price`, `description`, `image_product`) VALUES
(1, 'Hamburguesa doble con chedar', 3000, 'Hamburguesa doble carne, con chedar, huevo, tomate, lechuga.', 'https://www.carniceriademadrid.es/wp-content/uploads/2022/09/smash-burger-que-es.jpg'),
(2, 'Pizza mozzarella', 3000, 'Pizza con salsa de tomate y mucha mozzarella', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTI2hdQeNVlyu20ReOpJcNwdgW0ER5hwxnauQ&s'),
(5, 'Papas', 500, 'Papas artesanalmente recolectadas, cortadas y fritas', NULL),
(6, 'Picada', 6000, 'Salamin, quesos y aceitunas', NULL),
(7, 'Limonada', 1000, 'Jugo fresco de limones exprimidos, genjibre y azucar.', 'https://cdn0.celebritax.com/sites/default/files/styles/amp/public/recetas/limonada.jpg'),
(8, 'Coca Cola 1,25lt', 1200, 'Botella de coca cola de litro 25', 'https://naranjomarket.com/wp-content/uploads/2020/06/CocaCola1.25.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `user_name` varchar(250) NOT NULL,
  `password` char(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `user`
--

INSERT INTO `user` (`id`, `user_name`, `password`) VALUES
(1, 'webadmin', '$2y$10$XIxf3cEkb65J2zsFlL32meabNayi2sqgXgzyiAwPujiSk.0zoMnta');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_product` (`id_product`);

--
-- Indices de la tabla `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `userName` (`user_name`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_product` FOREIGN KEY (`id_product`) REFERENCES `product` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
