SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


CREATE TABLE `bruteforce` (
  `ip` int(11) NOT NULL,
  `failed_attempts` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE `chunks` (
  `id` int(11) NOT NULL,
  `streamid` int(11) NOT NULL,
  `length` float NOT NULL,
  `skylink` varchar(46) CHARACTER SET ascii NOT NULL,
  `is_first_chunk` tinyint(1) NOT NULL,
  `resolution` enum('1080','1080@60','720','720@60','360','360@60','original') COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE `newsletter` (
  `email` int(11) NOT NULL,
  `subscribtion_time` datetime NOT NULL,
  `ip` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE `stream` (
  `streamid` int(11) NOT NULL,
  `token` varchar(255) COLLATE utf8_bin NOT NULL,
  `userid` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `description` text COLLATE utf8_bin,
  `scheule_time` datetime DEFAULT NULL,
  `started` tinyint(1) NOT NULL,
  `finished` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(127) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(63) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(127) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `avatar` varchar(127) NOT NULL,
  `rank` enum('unverified','user','admin') DEFAULT 'unverified',
  `badlogin` tinyint(4) DEFAULT '0',
  `loginban` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `chunks`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `stream`
  ADD PRIMARY KEY (`streamid`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `chunks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `stream`
  MODIFY `streamid` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
