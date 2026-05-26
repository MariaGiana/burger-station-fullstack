-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 24-10-2024 a las 00:09:15
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
(1, 1, 2, 6000.00, '2024-09-11'),
(2, 2, 1, 3000.00, '2024-09-19'),
(5, 7, 3, 3000.00, '2024-09-12'),
(6, 1, 1, 3000.00, '2024-09-30');

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
(8, 'Coca Cola 1,25lt', 1200, 'Botella de coca cola de litro 25.', 'https://naranjomarket.com/wp-content/uploads/2020/06/CocaCola1.25.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `review`
--

CREATE TABLE `review` (
  `id` int(11) NOT NULL,
  `id_product` int(11) NOT NULL,
  `client_name` varchar(100) NOT NULL,
  `score` int(11) NOT NULL,
  `coment` text NOT NULL,
  `reply` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `review`
--

INSERT INTO `review` (`id`, `id_product`, `client_name`, `score`, `coment`, `reply`) VALUES
(1, 1, 'Juan Perez', 5, 'Excelente producto, me llegó en perfectas condiciones.', 'Gracias por tu reseña, Juan.'),
(2, 2, 'Maria García', 4, 'El producto es bueno, pero la entrega fue un poco lenta.', 'Lamentamos el retraso, Maria. Estamos mejorando nuestro servicio.'),
(3, 5, 'Carlos López', 3, 'El producto cumple, pero esperaba más por el precio.', 'Gracias por tu comentario, Carlos. Valoramos tu feedback.'),
(4, 6, 'Ana Fernandez', 5, '¡Me encantó! Superó mis expectativas.', 'Nos alegra mucho que te haya gustado, Ana. ¡Gracias!'),
(5, 7, 'Luis Ramírez', 2, 'El producto llegó con algunos defectos. No estoy satisfecho.', 'Lo sentimos, Luis. Por favor, contáctanos para solucionar el problema.');

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
-- Indices de la tabla `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id_product`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `review`
--
ALTER TABLE `review`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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

--
-- Filtros para la tabla `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `review_ibfk_1` FOREIGN KEY (`id_product`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
