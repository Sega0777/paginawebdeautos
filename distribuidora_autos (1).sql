-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 03-12-2024 a las 06:19:52
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
-- Base de datos: `distribuidora_autos`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `obtenerReservasPorUsuario` ()   BEGIN
    SELECT 
        usuarios.id AS usuario_id, 
        usuarios.nombre AS nombre_usuario, 
        COUNT(reservas.id) AS total_reservas
    FROM usuarios
    LEFT JOIN reservas ON usuarios.id = reservas.usuario_id
    GROUP BY usuarios.id, usuarios.nombre
    ORDER BY total_reservas DESC;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `autos`
--

CREATE TABLE `autos` (
  `id` int(11) NOT NULL,
  `modelo` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `imagen` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `autos`
--

INSERT INTO `autos` (`id`, `modelo`, `descripcion`, `precio`, `imagen`) VALUES
(1, 'Chevrolet Camaro', 'Un deportivo icónico con estilo y potencia.', 125000.00, 'camaro.webp'),
(2, 'Ford Raptor F150', 'Una pick-up robusta para aventuras extremas.', 150000.00, 'fordF150.jpg'),
(3, 'Lexus GX', 'Lujo y capacidad off-road en un solo auto.', 165000.00, 'lexusGX.avif'),
(4, 'McLaren P1', 'Para los amantes de la velocidad y el lujo.', 500000.00, 'McLaren p1.jpg'),
(5, 'RAM 500', 'Para los que buscan desafiar sus límites.', 170000.00, 'ram500.webp'),
(6, 'Civic TR', 'Un auto para todos.', 30000.00, 'civicTr.webp'),
(7, 'G Wagon', 'Para los que buscan comodidad y elegancia.', 120000.00, 'Gwagon.jpg'),
(8, 'Nissan Skyline R32', 'Un deportivo clásico para los coleccionistas.', 350000.00, 'nissan.webp'),
(9, 'Mini Cooper', 'Un modelo eléctrico para cuidar el ambiente.', 749000.00, 'minicooper.jpg'),
(10, 'McLaren 720S', 'Lujo y rareza son lo que define este auto.', 325000.00, 'McLaren 720S.webp'),
(11, 'Mustang GT500', '50% poder y 50% caballos de fuerza.', 240000.00, 'mustangGT500.jpg'),
(12, 'Kia Picanto', 'Comodidad y seguridad en el volante.', 12000.00, 'picanto.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservas`
--

CREATE TABLE `reservas` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `auto_id` int(11) NOT NULL,
  `fecha_reserva` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reservas`
--

INSERT INTO `reservas` (`id`, `usuario_id`, `auto_id`, `fecha_reserva`) VALUES
(2, 1, 9, '2024-11-30 21:34:08'),
(4, 1, 10, '2024-11-30 22:06:47'),
(8, 2, 2, '2024-12-01 16:14:41'),
(10, 1, 10, '2024-12-02 23:05:12'),
(11, 1, 2, '2024-12-02 23:08:08');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefono` varchar(15) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `contrasena` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `telefono`, `usuario`, `contrasena`) VALUES
(1, 'Monica', 'monica@utp.ac.pa', '69924495', 'monica', '$2y$10$0XvobvP1xPnKNQWyu3espOYdEv3fIY7Q781OlThYJdqWEuX75jx/G'),
(2, 'Victor', 'Victor@utp.ac.pa', '69924495', 'victor', '$2y$10$D5hi8pXiPH4pUCOf5wqHp.DaUaqCEUozX9XcRPj8jOQ6.OkCOtGti');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `autos`
--
ALTER TABLE `autos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `auto_id` (`auto_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `autos`
--
ALTER TABLE `autos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `reservas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `reservas_ibfk_2` FOREIGN KEY (`auto_id`) REFERENCES `autos` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
