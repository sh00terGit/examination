-- phpMyAdmin SQL Dump
-- version 4.4.15.5
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1:3306
-- Время создания: Мар 07 2017 г., 13:56
-- Версия сервера: 5.5.48
-- Версия PHP: 5.3.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `clear`
--

DELIMITER $$
--
-- Процедуры
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `randomizer`()
begin
      declare i int Default 0 ;
      declare random char(20) ;
      myloop: loop
      set random=conv(floor(rand() * 99999999999999), 20, 36) ;
      insert into `chapters` SET `fname`= random,`sname` =random,`comment`= random,`archive`= 0,`division_id`= 1;
      set i=i+1;
      if i=1000 then
        leave myloop;
	end if;
    end loop myloop;
  end$$

CREATE DEFINER=`root`@`%` PROCEDURE `random_resultSchedule`()
begin
      declare i int Default 0 ;
      declare random char(20) ;
      myloop: loop
      insert into `result_schedule` 
		SET 
		`exam_theme_fname`= 'Электробезопасность',
		exam_fname = i ,
		date_start ='2017-01-20',
		date_end ='2017-02-28',
		manager_first_name ='Вася',
		manager_middle_name ='Ильич',
		manager_last_name ='Моськин',
		manager_division ='Гомельское Отделение БелЖД',
		manager_subdivision ='Гомельская дистанция сигнализации и связи',
		committee ='';
		
      set i=i+1;
      if i=1000 then
        leave myloop;
	end if;
    end loop myloop;
  end$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Структура таблицы `answers`
--

CREATE TABLE IF NOT EXISTS `answers` (
  `id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `content` varchar(255) NOT NULL,
  `positive` tinyint(1) NOT NULL DEFAULT '0',
  `archive` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='ответы на вопросы';

--
-- Очистить таблицу перед добавлением данных `answers`
--

TRUNCATE TABLE `answers`;
-- --------------------------------------------------------

--
-- Структура таблицы `auth_type`
--

CREATE TABLE IF NOT EXISTS `auth_type` (
  `id` int(11) NOT NULL,
  `fname` enum('общая','индивидуальная','','') NOT NULL,
  `sname` enum('общая','индивидуальная','','') NOT NULL,
  `comment` varchar(255) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Очистить таблицу перед добавлением данных `auth_type`
--

TRUNCATE TABLE `auth_type`;
--
-- Дамп данных таблицы `auth_type`
--

INSERT INTO `auth_type` (`id`, `fname`, `sname`, `comment`) VALUES
(1, 'общая', 'общая', ''),
(2, 'индивидуальная', 'индивидуальная', '');

-- --------------------------------------------------------

--
-- Структура таблицы `chapters`
--

CREATE TABLE IF NOT EXISTS `chapters` (
  `id` int(11) NOT NULL,
  `fname` varchar(100) NOT NULL,
  `comment` varchar(255) DEFAULT NULL,
  `archive` tinyint(1) NOT NULL DEFAULT '0',
  `sname` varchar(20) NOT NULL,
  `division_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='Главы ';

--
-- Очистить таблицу перед добавлением данных `chapters`
--

TRUNCATE TABLE `chapters`;
-- --------------------------------------------------------

--
-- Структура таблицы `chapter_name`
--

CREATE TABLE IF NOT EXISTS `chapter_name` (
  `id` int(11) NOT NULL,
  `fname` varchar(100) NOT NULL,
  `sname` varchar(20) NOT NULL,
  `comment` varchar(255) DEFAULT NULL,
  `multiple` varchar(20) NOT NULL,
  `fname_roditelni` varchar(100) NOT NULL COMMENT 'родительный падеж',
  `fname_datelni` varchar(100) NOT NULL COMMENT 'дательный падеж',
  `fname_vinitel` varchar(100) NOT NULL COMMENT 'винительный',
  `fname_tvoritelni` varchar(100) NOT NULL COMMENT 'творительный',
  `fname_predlojni` varchar(100) NOT NULL COMMENT 'предложный',
  `multiple_roditelni` varchar(100) NOT NULL,
  `multiple_datelni` varchar(100) NOT NULL,
  `multiple_vinitelni` varchar(100) NOT NULL,
  `multiple_tvoritelni` varchar(100) NOT NULL,
  `multiple_predlojni` varchar(100) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Очистить таблицу перед добавлением данных `chapter_name`
--

TRUNCATE TABLE `chapter_name`;
--
-- Дамп данных таблицы `chapter_name`
--

INSERT INTO `chapter_name` (`id`, `fname`, `sname`, `comment`, `multiple`, `fname_roditelni`, `fname_datelni`, `fname_vinitel`, `fname_tvoritelni`, `fname_predlojni`, `multiple_roditelni`, `multiple_datelni`, `multiple_vinitelni`, `multiple_tvoritelni`, `multiple_predlojni`) VALUES
(1, 'Глава', 'Глава', '', 'Главы', 'главы', 'главе', 'главу', 'главой', 'главе', 'глав', 'главам', 'главы', 'главами', 'главах'),
(2, 'Раздел', 'Раздел', '', 'Разделы', 'раздела', 'разделу', 'раздел', 'разделом', 'разделе', 'разделов', 'разделам', 'разделы', 'разделами', 'разделах');

-- --------------------------------------------------------

--
-- Структура таблицы `criterion`
--

CREATE TABLE IF NOT EXISTS `criterion` (
  `id` int(11) NOT NULL,
  `value` int(3) NOT NULL,
  `measure` varchar(20) NOT NULL,
  `division_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='критерий ответа на вопрос';

--
-- Очистить таблицу перед добавлением данных `criterion`
--

TRUNCATE TABLE `criterion`;
-- --------------------------------------------------------

--
-- Структура таблицы `divisions`
--

CREATE TABLE IF NOT EXISTS `divisions` (
  `id` int(11) NOT NULL,
  `fname` varchar(100) NOT NULL,
  `sname` varchar(20) NOT NULL,
  `comment` varchar(255) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='Отделение';

--
-- Очистить таблицу перед добавлением данных `divisions`
--

TRUNCATE TABLE `divisions`;
--
-- Дамп данных таблицы `divisions`
--

INSERT INTO `divisions` (`id`, `fname`, `sname`, `comment`) VALUES
(1, 'Гомельское Отделение БелЖД', 'НОД-4', NULL),
(2, 'Минское отделение Белорусской железной дороги', 'НОД-1', NULL),
(4, 'Барановичское отделение Белорусской железной дорогий', 'НОД-2', NULL),
(5, 'Брестское отделение\nБелорусской железной дороги', 'НОД-3', ''),
(6, 'Могилёвское отделение Белорусской железной дороги', 'НОД-5', NULL),
(7, 'Витебское отделение Белорусской железной дороги', 'НОД-6', '');

-- --------------------------------------------------------

--
-- Структура таблицы `events`
--

CREATE TABLE IF NOT EXISTS `events` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='лог запросов (по умолчанию выключен)';

--
-- Очистить таблицу перед добавлением данных `events`
--

TRUNCATE TABLE `events`;
-- --------------------------------------------------------

--
-- Структура таблицы `exam`
--

CREATE TABLE IF NOT EXISTS `exam` (
  `id` int(11) NOT NULL,
  `division_id` int(11) NOT NULL,
  `manager_id` int(11) NOT NULL,
  `fname` varchar(100) NOT NULL,
  `comment` varchar(255) DEFAULT NULL,
  `sname` varchar(20) NOT NULL,
  `bilet` tinyint(1) NOT NULL DEFAULT '0',
  `archive` tinyint(1) NOT NULL DEFAULT '0',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `exam_theme_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='общая информация об экзамене';

--
-- Очистить таблицу перед добавлением данных `exam`
--

TRUNCATE TABLE `exam`;
-- --------------------------------------------------------

--
-- Структура таблицы `exam_chapter`
--

CREATE TABLE IF NOT EXISTS `exam_chapter` (
  `id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL,
  `chapter_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COMMENT='кросс-таблица экзамен - главы';

--
-- Очистить таблицу перед добавлением данных `exam_chapter`
--

TRUNCATE TABLE `exam_chapter`;
-- --------------------------------------------------------

--
-- Структура таблицы `exam_quest`
--

CREATE TABLE IF NOT EXISTS `exam_quest` (
  `id` int(11) NOT NULL,
  `exam_section_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='кросс-таблица вопросов к билету';

--
-- Очистить таблицу перед добавлением данных `exam_quest`
--

TRUNCATE TABLE `exam_quest`;
-- --------------------------------------------------------

--
-- Структура таблицы `exam_section`
--

CREATE TABLE IF NOT EXISTS `exam_section` (
  `id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL,
  `fname` varchar(100) NOT NULL,
  `sname` varchar(20) NOT NULL,
  `comment` varchar(100) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='билеты к экзамену';

--
-- Очистить таблицу перед добавлением данных `exam_section`
--

TRUNCATE TABLE `exam_section`;
-- --------------------------------------------------------

--
-- Структура таблицы `exam_theme`
--

CREATE TABLE IF NOT EXISTS `exam_theme` (
  `id` int(11) NOT NULL,
  `fname` varchar(100) NOT NULL,
  `sname` varchar(20) NOT NULL,
  `comment` varchar(255) DEFAULT NULL,
  `manager_subdiv_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Очистить таблицу перед добавлением данных `exam_theme`
--

TRUNCATE TABLE `exam_theme`;
-- --------------------------------------------------------

--
-- Структура таблицы `parts`
--

CREATE TABLE IF NOT EXISTS `parts` (
  `id` int(11) NOT NULL,
  `chapter_id` int(11) NOT NULL,
  `fname` varchar(100) NOT NULL,
  `sname` varchar(20) NOT NULL,
  `comment` varchar(255) DEFAULT NULL,
  `archive` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='пункты глав';

--
-- Очистить таблицу перед добавлением данных `parts`
--

TRUNCATE TABLE `parts`;
-- --------------------------------------------------------

--
-- Структура таблицы `part_name`
--

CREATE TABLE IF NOT EXISTS `part_name` (
  `id` int(11) NOT NULL,
  `fname` varchar(100) NOT NULL,
  `sname` varchar(20) NOT NULL,
  `multiple` varchar(20) NOT NULL,
  `fname_roditelni` varchar(100) NOT NULL COMMENT 'родительный падеж',
  `fname_datelni` varchar(100) NOT NULL COMMENT 'дательный падеж',
  `fname_vinitel` varchar(100) NOT NULL COMMENT 'винительный',
  `fname_tvoritelni` varchar(100) NOT NULL COMMENT 'творительный',
  `fname_predlojni` varchar(100) NOT NULL COMMENT 'предложный',
  `multiple_roditelni` varchar(100) NOT NULL,
  `multiple_datelni` varchar(100) NOT NULL,
  `multiple_vinitelni` varchar(100) NOT NULL,
  `multiple_tvoritelni` varchar(100) NOT NULL,
  `multiple_predlojni` varchar(100) NOT NULL,
  `comment` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Очистить таблицу перед добавлением данных `part_name`
--

TRUNCATE TABLE `part_name`;
--
-- Дамп данных таблицы `part_name`
--

INSERT INTO `part_name` (`id`, `fname`, `sname`, `multiple`, `fname_roditelni`, `fname_datelni`, `fname_vinitel`, `fname_tvoritelni`, `fname_predlojni`, `multiple_roditelni`, `multiple_datelni`, `multiple_vinitelni`, `multiple_tvoritelni`, `multiple_predlojni`, `comment`) VALUES
(1, 'Пункт', 'Пункт', 'Пункты', 'пункта', 'пункту', 'пункт', 'пунктом', 'пункте', 'пунктов', 'пунктам', 'пункты', 'пунктами', 'пунктах', ''),
(2, 'Подраздел', 'Подраздел', 'Подразделы', 'подраздела', 'подразделом', 'подраздел', 'подразделом', 'подразделе', 'подразделов', 'подразделам', 'подразделы', 'подразделами', 'подразделах', '');

-- --------------------------------------------------------

--
-- Структура таблицы `posts`
--

CREATE TABLE IF NOT EXISTS `posts` (
  `id` int(11) NOT NULL,
  `fname` varchar(100) NOT NULL,
  `division_id` int(11) NOT NULL,
  `sname` varchar(20) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='должности';

--
-- Очистить таблицу перед добавлением данных `posts`
--

TRUNCATE TABLE `posts`;
--
-- Дамп данных таблицы `posts`
--

INSERT INTO `posts` (`id`, `fname`, `division_id`, `sname`) VALUES
(1, 'программист', 1, 'программист'),
(2, 'начальник', 1, 'начальник'),
(3, 'уборщик', 1, 'уборщик'),
(4, 'водитель', 1, 'водитель');

-- --------------------------------------------------------

--
-- Структура таблицы `questions`
--

CREATE TABLE IF NOT EXISTS `questions` (
  `id` int(11) NOT NULL,
  `chapter_id` int(11) NOT NULL,
  `part_id` int(11) NOT NULL,
  `text` varchar(255) NOT NULL,
  `archive` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='информация о вопросе';

--
-- Очистить таблицу перед добавлением данных `questions`
--

TRUNCATE TABLE `questions`;
-- --------------------------------------------------------

--
-- Структура таблицы `result_exam_answers`
--

CREATE TABLE IF NOT EXISTS `result_exam_answers` (
  `id` int(11) NOT NULL,
  `answer_text` varchar(255) NOT NULL,
  `positive` tinyint(1) NOT NULL,
  `result_exam_question_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=80 DEFAULT CHARSET=utf8;

--
-- Очистить таблицу перед добавлением данных `result_exam_answers`
--

TRUNCATE TABLE `result_exam_answers`;
-- --------------------------------------------------------

--
-- Структура таблицы `result_exam_answers_user`
--

CREATE TABLE IF NOT EXISTS `result_exam_answers_user` (
  `id` int(11) NOT NULL,
  `result_exam_question_id` int(11) NOT NULL,
  `result_exam_answer_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;

--
-- Очистить таблицу перед добавлением данных `result_exam_answers_user`
--

TRUNCATE TABLE `result_exam_answers_user`;
-- --------------------------------------------------------

--
-- Структура таблицы `result_exam_questions`
--

CREATE TABLE IF NOT EXISTS `result_exam_questions` (
  `id` int(11) NOT NULL,
  `question_text` varchar(255) NOT NULL,
  `result_schedule_user_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=75 DEFAULT CHARSET=utf8;

--
-- Очистить таблицу перед добавлением данных `result_exam_questions`
--

TRUNCATE TABLE `result_exam_questions`;
-- --------------------------------------------------------

--
-- Структура таблицы `result_schedule`
--

CREATE TABLE IF NOT EXISTS `result_schedule` (
  `id` int(11) NOT NULL,
  `exam_theme_fname` varchar(255) NOT NULL,
  `exam_fname` varchar(255) NOT NULL,
  `date_start` date NOT NULL,
  `date_end` date NOT NULL,
  `manager_first_name` varchar(30) NOT NULL,
  `manager_middle_name` varchar(30) NOT NULL,
  `manager_last_name` varchar(30) NOT NULL,
  `manager_division` varchar(100) NOT NULL,
  `manager_subdivision` varchar(100) NOT NULL,
  `committee` varchar(255) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1011 DEFAULT CHARSET=utf8;

--
-- Очистить таблицу перед добавлением данных `result_schedule`
--

TRUNCATE TABLE `result_schedule`;
-- --------------------------------------------------------

--
-- Структура таблицы `result_schedule_user`
--

CREATE TABLE IF NOT EXISTS `result_schedule_user` (
  `id` int(11) NOT NULL,
  `user_first_name` varchar(30) NOT NULL,
  `user_middle_name` varchar(30) NOT NULL,
  `user_last_name` varchar(30) NOT NULL,
  `user_division` varchar(100) NOT NULL,
  `user_subdivision` varchar(100) NOT NULL,
  `date_pass` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `criterion_value` varchar(30) NOT NULL,
  `exam_time_pass` int(11) NOT NULL,
  `mark` tinyint(1) NOT NULL,
  `result_schedule_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;

--
-- Очистить таблицу перед добавлением данных `result_schedule_user`
--

TRUNCATE TABLE `result_schedule_user`;
-- --------------------------------------------------------

--
-- Структура таблицы `role`
--

CREATE TABLE IF NOT EXISTS `role` (
  `id` int(11) NOT NULL,
  `fname` enum('administrator','экзаменатор','экзаменуемый','редактор') NOT NULL,
  `sname` enum('admin','экзаменатор','экзаменуемый','редактор') NOT NULL,
  `comment` varchar(255) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='типы пользователей';

--
-- Очистить таблицу перед добавлением данных `role`
--

TRUNCATE TABLE `role`;
--
-- Дамп данных таблицы `role`
--

INSERT INTO `role` (`id`, `fname`, `sname`, `comment`) VALUES
(1, 'administrator', 'admin', ''),
(2, 'экзаменатор', 'экзаменатор', ''),
(3, 'экзаменуемый', 'экзаменуемый', ''),
(4, 'редактор', 'редактор', '');

-- --------------------------------------------------------

--
-- Структура таблицы `schedule`
--

CREATE TABLE IF NOT EXISTS `schedule` (
  `id` int(11) NOT NULL,
  `date_start` date NOT NULL,
  `date_end` date NOT NULL,
  `exam_id` int(11) NOT NULL,
  `subdiv_id` int(11) DEFAULT NULL,
  `active_key` tinyint(1) NOT NULL DEFAULT '1',
  `manager_id` int(11) NOT NULL,
  `auth_type_id` int(11) NOT NULL,
  `comment` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `criterion_id` int(11) NOT NULL,
  `attempt` int(11) NOT NULL DEFAULT '0',
  `time_pass` int(11) NOT NULL DEFAULT '0',
  `committee` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COMMENT='Расписание экзаменов';

--
-- Очистить таблицу перед добавлением данных `schedule`
--

TRUNCATE TABLE `schedule`;
-- --------------------------------------------------------

--
-- Структура таблицы `subdivisions`
--

CREATE TABLE IF NOT EXISTS `subdivisions` (
  `id` int(11) NOT NULL,
  `fname` varchar(100) NOT NULL,
  `sname` varchar(20) NOT NULL,
  `division_id` int(11) NOT NULL,
  `subdiv_type_id` int(11) NOT NULL,
  `comment` varchar(255) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COMMENT='подразделение';

--
-- Очистить таблицу перед добавлением данных `subdivisions`
--

TRUNCATE TABLE `subdivisions`;
--
-- Дамп данных таблицы `subdivisions`
--

INSERT INTO `subdivisions` (`id`, `fname`, `sname`, `division_id`, `subdiv_type_id`, `comment`) VALUES
(1, 'Гомельская дистанция сигнализации и связи', 'ШЧ-8', 1, 1, ''),
(3, 'Калинковичская дистанция сигнализации и связи', 'ШЧ-11', 1, 1, ''),
(4, 'Гомельский Информационно-вычислительный центр', 'ИВЦ-9', 1, 2, ''),
(6, 'Гомельское вагонное депо', 'ВЧД-7', 1, 1, ''),
(7, 'ОАО "Белорусский металлургический завод"', 'БМЗ', 1, 3, ''),
(11, 'Вокзал станции Гомель', 'ЛВОК', 1, 1, ''),
(12, 'Локомотивное депо Гомель', 'ТЧ-8', 1, 1, ''),
(13, 'Локомотивное депо Жлобин', 'ТЧ-10', 1, 1, ''),
(14, 'Локомотивное депо Калинковичи', 'ТЧ-11', 1, 1, ''),
(15, 'Жлобинское вагонное депо', 'ВЧД- 8', 1, 1, ''),
(16, 'Гомельский вагонный участок', 'ЛВЧ-4', 1, 1, ''),
(17, 'Гомельская дистанция пути', 'ПЧ-17', 1, 1, ''),
(18, 'Жлобинская дистанция пути', 'ПЧ-16', 1, 1, ''),
(19, 'Калинковичская дистанция пути', 'ПЧ-18', 1, 1, ''),
(21, 'Жлобинская дистанция сигнализации и связи', 'ШЧ-12', 1, 1, ''),
(24, 'НОДБМ', 'НОДБМ', 1, 2, '');

-- --------------------------------------------------------

--
-- Структура таблицы `subdivision_type`
--

CREATE TABLE IF NOT EXISTS `subdivision_type` (
  `id` int(11) NOT NULL,
  `fname` varchar(100) NOT NULL,
  `sname` varchar(20) NOT NULL,
  `division_id` int(11) NOT NULL,
  `comment` varchar(255) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='таблица видов принадлежности ';

--
-- Очистить таблицу перед добавлением данных `subdivision_type`
--

TRUNCATE TABLE `subdivision_type`;
--
-- Дамп данных таблицы `subdivision_type`
--

INSERT INTO `subdivision_type` (`id`, `fname`, `sname`, `division_id`, `comment`) VALUES
(1, 'внутреннее предприятие', 'внутренеее', 1, ''),
(2, 'НОД-СОБСТВЕННЫЙ', 'НОД-СОБСТВЕННЫЙ', 1, ''),
(3, 'сторонее предприятие', 'стороннее', 1, '');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL,
  `login` varchar(30) DEFAULT NULL,
  `passw` varchar(100) DEFAULT NULL,
  `first_name` varchar(30) NOT NULL,
  `middle_name` varchar(30) NOT NULL,
  `last_name` varchar(30) NOT NULL,
  `division_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL DEFAULT '3',
  `post_id` int(11) NOT NULL,
  `archive` tinyint(1) NOT NULL DEFAULT '0',
  `subdivision_id` int(11) NOT NULL,
  `comment` text
) ENGINE=InnoDB AUTO_INCREMENT=299 DEFAULT CHARSET=utf8 COMMENT='Пользователи';

--
-- Очистить таблицу перед добавлением данных `users`
--

TRUNCATE TABLE `users`;
--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `login`, `passw`, `first_name`, `middle_name`, `last_name`, `division_id`, `role_id`, `post_id`, `archive`, `subdivision_id`, `comment`) VALUES
(1, 'admin', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'Андрей', 'Анатольевич', 'Шипуль', 1, 1, 1, 0, 1, ''),
(295, 'exam', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'Александр ', 'Олегович', 'АВЕРЧЕНКО ', 1, 2, 2, 0, 24, ''),
(296, 'user', '6216f8a75fd5bb3d5f22b6f9958cdede3fc086c2', 'Василий', 'Иванович', 'Васильев', 1, 3, 1, 0, 1, ''),
(297, '555', '8aefb06c426e07a0a671a1e2488b4858d694a730', 'Петр', 'Ильич', 'Лавров', 1, 3, 4, 0, 1, ''),
(298, '', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'Иван', 'Иванович', 'Иванов', 1, 3, 2, 0, 4, '');

-- --------------------------------------------------------

--
-- Структура таблицы `user_chapter_part`
--

CREATE TABLE IF NOT EXISTS `user_chapter_part` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `chapter_name_id` int(11) NOT NULL,
  `part_name_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

--
-- Очистить таблицу перед добавлением данных `user_chapter_part`
--

TRUNCATE TABLE `user_chapter_part`;
--
-- Дамп данных таблицы `user_chapter_part`
--

INSERT INTO `user_chapter_part` (`id`, `user_id`, `chapter_name_id`, `part_name_id`) VALUES
(10, 1, 1, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `user_division`
--

CREATE TABLE IF NOT EXISTS `user_division` (
  `id` int(11) NOT NULL,
  `manager_id` int(11) NOT NULL,
  `subdivision_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8 COMMENT='Кросс-таблица экзаменатор - предприятие';

--
-- Очистить таблицу перед добавлением данных `user_division`
--

TRUNCATE TABLE `user_division`;
-- --------------------------------------------------------

--
-- Структура таблицы `user_schedule`
--

CREATE TABLE IF NOT EXISTS `user_schedule` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `schedule_id` int(11) NOT NULL,
  `try` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8 COMMENT='Общая информация экзаменуемый-экзамен';

--
-- Очистить таблицу перед добавлением данных `user_schedule`
--

TRUNCATE TABLE `user_schedule`;
-- --------------------------------------------------------

--
-- Структура таблицы `user_theme`
--

CREATE TABLE IF NOT EXISTS `user_theme` (
  `id` int(11) NOT NULL,
  `manager_id` int(11) NOT NULL,
  `exam_theme_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--
-- Очистить таблицу перед добавлением данных `user_theme`
--

TRUNCATE TABLE `user_theme`;
--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `auth_type`
--
ALTER TABLE `auth_type`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `chapters`
--
ALTER TABLE `chapters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `division_id` (`division_id`),
  ADD KEY `archive` (`archive`);

--
-- Индексы таблицы `chapter_name`
--
ALTER TABLE `chapter_name`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `criterion`
--
ALTER TABLE `criterion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `division_id` (`division_id`);

--
-- Индексы таблицы `divisions`
--
ALTER TABLE `divisions`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `exam`
--
ALTER TABLE `exam`
  ADD PRIMARY KEY (`id`),
  ADD KEY `division_id` (`division_id`),
  ADD KEY `manager_id` (`manager_id`),
  ADD KEY `exam_theme_id` (`exam_theme_id`),
  ADD KEY `aviable_exam` (`exam_theme_id`,`archive`) USING BTREE;

--
-- Индексы таблицы `exam_chapter`
--
ALTER TABLE `exam_chapter`
  ADD PRIMARY KEY (`id`),
  ADD KEY `exam_id` (`exam_id`),
  ADD KEY `chapter_id` (`chapter_id`);

--
-- Индексы таблицы `exam_quest`
--
ALTER TABLE `exam_quest`
  ADD PRIMARY KEY (`id`),
  ADD KEY `exam_section_id` (`exam_section_id`),
  ADD KEY `question_id` (`question_id`);

--
-- Индексы таблицы `exam_section`
--
ALTER TABLE `exam_section`
  ADD PRIMARY KEY (`id`),
  ADD KEY `exam_id` (`exam_id`);

--
-- Индексы таблицы `exam_theme`
--
ALTER TABLE `exam_theme`
  ADD PRIMARY KEY (`id`),
  ADD KEY `manager_subdiv_id` (`manager_subdiv_id`);

--
-- Индексы таблицы `parts`
--
ALTER TABLE `parts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chapter_id` (`chapter_id`);

--
-- Индексы таблицы `part_name`
--
ALTER TABLE `part_name`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `division_id` (`division_id`),
  ADD KEY `division_id_2` (`division_id`);

--
-- Индексы таблицы `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chapter_id` (`chapter_id`),
  ADD KEY `part_id` (`part_id`);

--
-- Индексы таблицы `result_exam_answers`
--
ALTER TABLE `result_exam_answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `result_exam_question_id` (`result_exam_question_id`);

--
-- Индексы таблицы `result_exam_answers_user`
--
ALTER TABLE `result_exam_answers_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `result_exam_question_id` (`result_exam_question_id`),
  ADD KEY `result_exam_answer_id` (`result_exam_answer_id`);

--
-- Индексы таблицы `result_exam_questions`
--
ALTER TABLE `result_exam_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `result_schedule_user_id` (`result_schedule_user_id`);

--
-- Индексы таблицы `result_schedule`
--
ALTER TABLE `result_schedule`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `result_schedule_user`
--
ALTER TABLE `result_schedule_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `result_schedule_id` (`result_schedule_id`);

--
-- Индексы таблицы `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `utype_name` (`fname`);

--
-- Индексы таблицы `schedule`
--
ALTER TABLE `schedule`
  ADD PRIMARY KEY (`id`),
  ADD KEY `date_start` (`date_start`),
  ADD KEY `exam_id` (`exam_id`),
  ADD KEY `manager_id` (`manager_id`),
  ADD KEY `auth_type_id` (`auth_type_id`),
  ADD KEY `subdiv_id` (`subdiv_id`),
  ADD KEY `criterion_id` (`criterion_id`);

--
-- Индексы таблицы `subdivisions`
--
ALTER TABLE `subdivisions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sub_owner_id` (`subdiv_type_id`),
  ADD KEY `division_id` (`division_id`);

--
-- Индексы таблицы `subdivision_type`
--
ALTER TABLE `subdivision_type`
  ADD PRIMARY KEY (`id`),
  ADD KEY `division_id` (`division_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `division_id` (`division_id`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `subdivision_id` (`subdivision_id`),
  ADD KEY `archive` (`archive`);

--
-- Индексы таблицы `user_chapter_part`
--
ALTER TABLE `user_chapter_part`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `chapter_name_id` (`chapter_name_id`),
  ADD KEY `part_name_id` (`part_name_id`);

--
-- Индексы таблицы `user_division`
--
ALTER TABLE `user_division`
  ADD PRIMARY KEY (`id`),
  ADD KEY `manager_id` (`manager_id`),
  ADD KEY `division_id` (`subdivision_id`),
  ADD KEY `manager_id_2` (`manager_id`),
  ADD KEY `division_id_2` (`subdivision_id`);

--
-- Индексы таблицы `user_schedule`
--
ALTER TABLE `user_schedule`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `schedule_id` (`schedule_id`);

--
-- Индексы таблицы `user_theme`
--
ALTER TABLE `user_theme`
  ADD PRIMARY KEY (`id`),
  ADD KEY `manager_id` (`manager_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `auth_type`
--
ALTER TABLE `auth_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `chapters`
--
ALTER TABLE `chapters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT для таблицы `chapter_name`
--
ALTER TABLE `chapter_name`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `criterion`
--
ALTER TABLE `criterion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `divisions`
--
ALTER TABLE `divisions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT для таблицы `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `exam`
--
ALTER TABLE `exam`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT для таблицы `exam_chapter`
--
ALTER TABLE `exam_chapter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT для таблицы `exam_quest`
--
ALTER TABLE `exam_quest`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT для таблицы `exam_section`
--
ALTER TABLE `exam_section`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT для таблицы `exam_theme`
--
ALTER TABLE `exam_theme`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `parts`
--
ALTER TABLE `parts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT для таблицы `part_name`
--
ALTER TABLE `part_name`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT для таблицы `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT для таблицы `result_exam_answers`
--
ALTER TABLE `result_exam_answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=80;
--
-- AUTO_INCREMENT для таблицы `result_exam_answers_user`
--
ALTER TABLE `result_exam_answers_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=33;
--
-- AUTO_INCREMENT для таблицы `result_exam_questions`
--
ALTER TABLE `result_exam_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=75;
--
-- AUTO_INCREMENT для таблицы `result_schedule`
--
ALTER TABLE `result_schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1011;
--
-- AUTO_INCREMENT для таблицы `result_schedule_user`
--
ALTER TABLE `result_schedule_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=38;
--
-- AUTO_INCREMENT для таблицы `role`
--
ALTER TABLE `role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT для таблицы `schedule`
--
ALTER TABLE `schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT для таблицы `subdivisions`
--
ALTER TABLE `subdivisions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT для таблицы `subdivision_type`
--
ALTER TABLE `subdivision_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=299;
--
-- AUTO_INCREMENT для таблицы `user_chapter_part`
--
ALTER TABLE `user_chapter_part`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT для таблицы `user_division`
--
ALTER TABLE `user_division`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=44;
--
-- AUTO_INCREMENT для таблицы `user_schedule`
--
ALTER TABLE `user_schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=41;
--
-- AUTO_INCREMENT для таблицы `user_theme`
--
ALTER TABLE `user_theme`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `chapters`
--
ALTER TABLE `chapters`
  ADD CONSTRAINT `chapters_ibfk_1` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `exam`
--
ALTER TABLE `exam`
  ADD CONSTRAINT `theme` FOREIGN KEY (`exam_theme_id`) REFERENCES `exam_theme` (`id`);

--
-- Ограничения внешнего ключа таблицы `exam_chapter`
--
ALTER TABLE `exam_chapter`
  ADD CONSTRAINT `exam_chapter_ibfk_1` FOREIGN KEY (`chapter_id`) REFERENCES `chapters` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `exam_chapter_ibfk_2` FOREIGN KEY (`exam_id`) REFERENCES `exam` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `exam_quest`
--
ALTER TABLE `exam_quest`
  ADD CONSTRAINT `exam_quest_ibfk_1` FOREIGN KEY (`exam_section_id`) REFERENCES `exam_section` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `exam_quest_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `exam_section`
--
ALTER TABLE `exam_section`
  ADD CONSTRAINT `exam_section_ibfk_1` FOREIGN KEY (`exam_id`) REFERENCES `exam` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `exam_theme`
--
ALTER TABLE `exam_theme`
  ADD CONSTRAINT `exam_theme_ibfk_1` FOREIGN KEY (`manager_subdiv_id`) REFERENCES `subdivisions` (`id`) ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `parts`
--
ALTER TABLE `parts`
  ADD CONSTRAINT `parts_ibfk_1` FOREIGN KEY (`chapter_id`) REFERENCES `chapters` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`part_id`) REFERENCES `parts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `result_exam_answers`
--
ALTER TABLE `result_exam_answers`
  ADD CONSTRAINT `result_exam_answers_ibfk_1` FOREIGN KEY (`result_exam_question_id`) REFERENCES `result_exam_questions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `result_exam_answers_user`
--
ALTER TABLE `result_exam_answers_user`
  ADD CONSTRAINT `result_exam_answers_user_ibfk_1` FOREIGN KEY (`result_exam_question_id`) REFERENCES `result_exam_questions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `result_exam_answers_user_ibfk_2` FOREIGN KEY (`result_exam_answer_id`) REFERENCES `result_exam_answers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `result_exam_questions`
--
ALTER TABLE `result_exam_questions`
  ADD CONSTRAINT `result_exam_questions_ibfk_1` FOREIGN KEY (`result_schedule_user_id`) REFERENCES `result_schedule_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `result_schedule_user`
--
ALTER TABLE `result_schedule_user`
  ADD CONSTRAINT `result_schedule_user_ibfk_1` FOREIGN KEY (`result_schedule_id`) REFERENCES `result_schedule` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `schedule`
--
ALTER TABLE `schedule`
  ADD CONSTRAINT `schedule_ibfk_1` FOREIGN KEY (`exam_id`) REFERENCES `exam` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `schedule_ibfk_2` FOREIGN KEY (`criterion_id`) REFERENCES `criterion` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `schedule_ibfk_3` FOREIGN KEY (`auth_type_id`) REFERENCES `auth_type` (`id`);

--
-- Ограничения внешнего ключа таблицы `subdivisions`
--
ALTER TABLE `subdivisions`
  ADD CONSTRAINT `subdivisions_ibfk_1` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `subdivision_type`
--
ALTER TABLE `subdivision_type`
  ADD CONSTRAINT `subdivision_type_ibfk_1` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`subdivision_id`) REFERENCES `subdivisions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `users_ibfk_3` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`);

--
-- Ограничения внешнего ключа таблицы `user_chapter_part`
--
ALTER TABLE `user_chapter_part`
  ADD CONSTRAINT `user_chapter_part_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_chapter_part_ibfk_2` FOREIGN KEY (`part_name_id`) REFERENCES `part_name` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `user_chapter_part_ibfk_3` FOREIGN KEY (`chapter_name_id`) REFERENCES `chapter_name` (`id`) ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `user_division`
--
ALTER TABLE `user_division`
  ADD CONSTRAINT `user_division_ibfk_1` FOREIGN KEY (`manager_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `user_schedule`
--
ALTER TABLE `user_schedule`
  ADD CONSTRAINT `user_schedule_ibfk_1` FOREIGN KEY (`schedule_id`) REFERENCES `schedule` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_schedule_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `user_theme`
--
ALTER TABLE `user_theme`
  ADD CONSTRAINT `user_theme_ibfk_1` FOREIGN KEY (`manager_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
