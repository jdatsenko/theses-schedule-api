-- phpMyAdmin SQL Dump
-- version 5.2.1deb1+jammy2
-- https://www.phpmyadmin.net/
--
-- Хост: localhost:3306
-- Время создания: Мар 24 2024 г., 18:19
-- Версия сервера: 8.0.36-0ubuntu0.22.04.1
-- Версия PHP: 8.3.3-1+ubuntu22.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `rozvrh`
--

-- --------------------------------------------------------

--
-- Структура таблицы `schedule`
--

CREATE TABLE `schedule` (
  `id` int NOT NULL,
  `nazov` varchar(255) COLLATE utf8mb3_slovak_ci NOT NULL,
  `den` varchar(255) COLLATE utf8mb3_slovak_ci NOT NULL,
  `miestnost` varchar(255) COLLATE utf8mb3_slovak_ci NOT NULL,
  `typ` varchar(255) COLLATE utf8mb3_slovak_ci NOT NULL,
  `cas_od` time NOT NULL,
  `cas_do` time NOT NULL,
  `ucitel` varchar(255) COLLATE utf8mb3_slovak_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_slovak_ci;

--
-- Дамп данных таблицы `schedule`
--

INSERT INTO `schedule` (`id`, `nazov`, `den`, `miestnost`, `typ`, `cas_od`, `cas_do`, `ucitel`) VALUES
(533, 'Telesná kultúra 6 (3,4)', 'Po', 'tv (BA-FEI-FEI TV-PL)', 'Cvičenie', '07:00:00', '08:50:00', 'J. Lamošová'),
(534, 'Úvod do herného dizajnu (1,3,5)', 'Po', 'bc300 (BA-FEI-FEI B-C)', 'Prednáška', '13:00:00', '14:50:00', 'O. Haffner'),
(535, 'Vývoj softvérových aplikácií (1,2,3)', 'Po', 'de300 (BA-FEI-FEI D-E)', 'Prednáška', '15:00:00', '16:50:00', 'I. Kossaczký'),
(536, 'Webové technológie 2 (1)', 'Ut', 'cd300 (BA-FEI-FEI C-D)', 'Prednáška', '10:00:00', '11:50:00', 'K. Žáková'),
(537, 'Algebraické štruktúry (1)', 'Ut', 'bc300 (BA-FEI-FEI B-C)', 'Prednáška', '13:00:00', '14:50:00', 'O. Nánásiová'),
(538, 'Webové technológie 2 (1,6)', 'St', 'cd300 (BA-FEI-FEI C-D)', 'Prednáška', '13:00:00', '14:50:00', 'K. Žáková');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `schedule`
--
ALTER TABLE `schedule`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `schedule`
--
ALTER TABLE `schedule`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=543;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
