-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3308
-- Généré le :  mar. 01 sep. 2020 à 09:13
-- Version du serveur :  5.7.28
-- Version de PHP :  7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `a08`
--

-- --------------------------------------------------------

--
-- Structure de la table `actors`
--

DROP TABLE IF EXISTS `actors`;
CREATE TABLE IF NOT EXISTS `actors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `last_name` varchar(80) NOT NULL,
  `first_name` varchar(80) NOT NULL,
  `dob` date NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `last_name` (`last_name`,`first_name`,`dob`)
) ENGINE=InnoDB AUTO_INCREMENT=239 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `actors`
--

INSERT INTO `actors` (`id`, `last_name`, `first_name`, `dob`, `image`, `created_at`, `modified_at`) VALUES
(219, 'Downey Jr.', 'Robert ', '1965-04-04', 'Robert Downey Jr..jpg', '2020-08-31 09:34:41', NULL),
(220, 'Paltrow', 'Gwyneth ', '1972-09-27', 'Gwyneth Paltrow.jpg', '2020-08-31 09:34:41', NULL),
(221, 'Bridges', 'Jeff ', '1949-12-04', 'Jeff Bridges.jpg', '2020-08-31 09:34:41', NULL),
(222, 'Johansson', 'Scarlett ', '1984-11-22', 'Scarlett Johansson.jpg', '2020-08-31 10:28:23', NULL),
(223, 'Cheadle', 'Don ', '1964-11-29', 'Don Cheadle.jpg', '2020-08-31 10:28:23', NULL),
(224, 'Hemsworth', 'Chris ', '1983-09-11', 'Chris Hemsworth.jpg', '2020-08-31 10:56:35', NULL),
(225, 'Natalie', 'Portman', '1981-06-09', 'Natalie Portman.jpg', '2020-08-31 10:56:35', NULL),
(226, 'Hiddleston', 'Tom ', '1981-02-09', 'Tom Hiddleston.jpg', '2020-08-31 11:05:50', NULL),
(227, 'Hopkins', 'Anthony ', '1937-12-31', 'Anthony Hopkins.jpg', '2020-08-31 11:05:50', NULL),
(232, 'Pratt', 'Chris ', '1971-06-21', 'Chris Pratt.jpg', '2020-09-01 10:26:45', NULL),
(233, 'Saldana', 'Zoe ', '1978-06-19', 'Zoe Saldana.jpg', '2020-09-01 10:26:45', NULL),
(234, 'Cumberbatch', 'Benedict ', '1976-07-19', 'Benedict Cumberbatch.jpg', '2020-09-01 10:39:47', NULL),
(235, 'tilda ', 'Swinton', '1960-11-05', 'tilda swinton.jpg', '2020-09-01 10:39:47', NULL),
(236, 'Boseman', 'Chadwick ', '1976-11-29', 'Chadwick Boseman.jpg', '2020-09-01 10:54:32', NULL),
(237, 'Wright', 'Letitia ', '1993-08-31', 'Letitia Wright.jpg', '2020-09-01 10:54:32', NULL),
(238, 'Holland', 'Tom ', '1996-06-01', 'Tom Holland.jpg', '2020-09-01 11:10:20', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `actors_movies`
--

DROP TABLE IF EXISTS `actors_movies`;
CREATE TABLE IF NOT EXISTS `actors_movies` (
  `id_actors` int(11) NOT NULL,
  `id_movie` int(11) NOT NULL,
  `role` varchar(80) DEFAULT NULL,
  UNIQUE KEY `id_actors` (`id_actors`,`id_movie`),
  KEY `movies_movies_actors` (`id_movie`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `actors_movies`
--

INSERT INTO `actors_movies` (`id_actors`, `id_movie`, `role`) VALUES
(219, 169, 'Iron Man / Tony Star'),
(219, 170, NULL),
(220, 169, 'Pepper Potts'),
(221, 169, 'Obadiah Stane / Iron Monger'),
(222, 170, 'Black widow'),
(223, 170, 'war machine'),
(224, 171, 'Thor'),
(224, 172, NULL),
(225, 171, 'Jane Foster'),
(226, 172, 'Loki'),
(227, 172, 'Odin'),
(232, 174, 'star - lord'),
(233, 174, 'Gamora'),
(234, 175, 'docteur Strange'),
(235, 175, 'Ancien'),
(236, 176, 'black panther'),
(237, 176, 'Shuri'),
(238, 177, 'spiderman');

-- --------------------------------------------------------

--
-- Structure de la table `movies`
--

DROP TABLE IF EXISTS `movies`;
CREATE TABLE IF NOT EXISTS `movies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL,
  `release_date` date NOT NULL,
  `duration` time DEFAULT NULL,
  `director` varchar(80) DEFAULT NULL,
  `id_phase` tinyint(3) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`,`release_date`,`id_phase`,`image`),
  KEY `image_movies` (`image`),
  KEY `phase_movies` (`id_phase`)
) ENGINE=InnoDB AUTO_INCREMENT=178 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `movies`
--

INSERT INTO `movies` (`id`, `name`, `release_date`, `duration`, `director`, `id_phase`, `image`, `created_at`, `modified_at`) VALUES
(169, 'iron Man', '2008-04-30', NULL, 'Jon Favreau', 1, 'ironMan.jpg', '2020-08-31 09:34:41', NULL),
(170, 'Iron Man 2', '2010-04-28', NULL, 'Jon Favreau', 1, 'ironMan2.jpg', '2020-08-31 10:28:23', NULL),
(171, 'Thor : Le Monde des ténèbres', '2013-10-30', NULL, 'Alan Taylor', 2, 'thorMondeDesTenebres.jpg', '2020-08-31 10:56:35', NULL),
(172, 'Thor : Ragnarok', '2017-08-25', NULL, 'Taika Waititi', 3, 'thorRagnarok.jpg', '2020-08-31 11:05:50', NULL),
(174, 'Les Gardiens de la Galaxie', '2014-08-13', NULL, 'James Gunn', 2, 'gardiensDeLaGalaxie.jpg', '2020-09-01 10:26:45', NULL),
(175, 'Doctor Strange', '2016-08-26', NULL, 'Scott Derrickson', 3, 'doctorStrange.jpg', '2020-09-01 10:39:47', NULL),
(176, 'Black Panther', '2018-02-14', NULL, 'Ryan Coogler', 3, 'blackPanther.jpg', '2020-09-01 10:54:32', NULL),
(177, 'Spider-Man: Far From Home', '2019-07-03', NULL, 'Jon Watts', 3, 'spidermanHomecoming.jpg', '2020-09-01 11:10:20', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `phase`
--

DROP TABLE IF EXISTS `phase`;
CREATE TABLE IF NOT EXISTS `phase` (
  `id` tinyint(3) NOT NULL AUTO_INCREMENT,
  `phase` char(3) CHARACTER SET utf8mb4 NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `phase` (`phase`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `phase`
--

INSERT INTO `phase` (`id`, `phase`) VALUES
(1, 'I'),
(2, 'II'),
(3, 'III');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `actors_movies`
--
ALTER TABLE `actors_movies`
  ADD CONSTRAINT `actors_movies_actors` FOREIGN KEY (`id_actors`) REFERENCES `actors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `movies_movies_actors` FOREIGN KEY (`id_movie`) REFERENCES `movies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `movies`
--
ALTER TABLE `movies`
  ADD CONSTRAINT `phase_movies` FOREIGN KEY (`id_phase`) REFERENCES `phase` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
