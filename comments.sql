-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Авг 24 2023 г., 23:41
-- Версия сервера: 8.0.33-0ubuntu0.22.04.4
-- Версия PHP: 8.1.2-1ubuntu2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `pic_comments_db`
--

-- --------------------------------------------------------

--
-- Структура таблицы `comments`
--

CREATE TABLE `comments` (
  `id` int NOT NULL,
  `user_name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_comment` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `comment_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `pic_id` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `comments`
--

INSERT INTO `comments` (`id`, `user_name`, `user_comment`, `comment_date`, `pic_id`) VALUES
(1, 'маша', 'Очень прикольно', '2023-08-23 22:22:23', 'pic-1'),
(2, 'маша', 'Очень прикольно', '2023-08-23 22:23:58', 'pic-1'),
(3, 'Даша', 'Класс!!', '2023-08-23 22:23:58', 'pic-1'),
(4, 'Дима', 'Крутяк!', '2023-08-23 22:23:58', 'pic-1'),
(7, 'Петя', ' Я Петр,  Я Петр,  Я Петр,  Я Петр,  Я Петр,  Я Петр,  Я Петр,  Я Петр,  Я Петр,  Я Петр,  Я Петр,  Я Петр,  Я Петр,  Я Петр,  Я Петр,  Я Петр,  Я Петр,  Я Петр,  Я Петр,  Я Петр,  Я Петр,  Я Петр,  Я Петр,  Я Петр,  Я Петр,  Я Петр,  Я Петр,  Я Петр,  Я Петр,  Я Петр,  Я Петр,  Я Петр,  Я Петр, ', '2023-08-23 22:58:09', 'pic-1'),
(9, 'rvhrv', 'vrfgvrghvrfnhf', '2023-08-23 23:12:03', 'pic-1'),
(10, 'mgnvn', 'bnnmghmnhn', '2023-08-24 09:07:34', 'pic-1');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
