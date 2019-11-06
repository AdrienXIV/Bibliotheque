-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  lun. 16 sep. 2019 à 18:00
-- Version du serveur :  5.7.24
-- Version de PHP :  7.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `bibliotheque`
--

-- --------------------------------------------------------

--
-- Structure de la table `collections`
--

DROP TABLE IF EXISTS `collections`;
CREATE TABLE IF NOT EXISTS `collections` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `categorie` text NOT NULL,
  `titre` text CHARACTER SET utf8mb4 NOT NULL,
  `auteur` text NOT NULL,
  `image` text NOT NULL,
  `temps` datetime DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `collections`
--

INSERT INTO `collections` (`id`, `categorie`, `titre`, `auteur`, `image`, `temps`, `email`) VALUES
(1, 'livre', 'livre 1', 'auteur 1', 'public/images/livres/livre_livre 1_auteur 1_24-08-2019_19-02-25.png', '2019-08-24 19:02:25', 'adrien.mld@laposte.net'),
(2, 'livre', 'livre 2', 'auteur 2', 'public/images/livres/livre_livre 2_auteur 2_24-08-2019_19-02-44.png', '2019-08-24 19:02:44', 'adrien.mld@laposte.net'),
(3, 'livre', 'livre 3', 'auteur 3', 'public/images/livres/livre_livre 3_auteur 3_24-08-2019_19-02-58.png', '2019-08-24 19:02:58', 'adrien.mld@laposte.net'),
(4, 'livre', 'livre 4', 'auteur 4', 'public/images/livres/livre_livre 4_auteur 4_24-08-2019_19-03-08.png', '2019-08-24 19:03:08', 'adrien.mld@laposte.net'),
(5, 'livre', 'livre 5', 'auteur 5', 'public/images/livres/livre_livre 5_auteur 5_24-08-2019_19-03-21.png', '2019-08-24 19:03:21', 'adrien.mld@laposte.net'),
(6, 'livre', 'livre 6', 'auteur 6', 'public/images/livres/livre_livre 6_auteur 6_24-08-2019_19-03-36.png', '2019-08-24 19:03:36', 'adrien.mld@laposte.net'),
(7, 'livre', 'azerty 45', 'auteur 1', 'public/images/livres/livre_azerty 45_auteur 1_24-08-2019_20-12-47.png', '2019-08-24 20:12:47', 'adri_00@hotmail.fr'),
(8, 'livre', 'azerty', 'azertyu', 'public/images/livres/livre_azerty_azertyu_08-09-2019_19-51-32.png', '2019-09-08 19:51:32', 'adrien.mld@laposte.net'),
(16, 'livre', 'azerty', 'auteur 2', 'public/images/livres/livre_azerty_auteur 2_14-09-2019_20-18-20.png', '2019-09-14 20:18:20', 'adri_00@hotmail.fr'),
(15, 'film', 'azerty', 'film 1', 'public/images/films/film_azerty_film 1_08-09-2019_21-27-35.png', '2019-09-08 21:27:35', 'adrien.mld@laposte.net'),
(14, 'serie', 'azerty', 'auteur 4', 'public/images/series/serie_azerty_auteur 4_08-09-2019_20-10-54.png', '2019-09-08 20:10:54', 'adrien.mld@laposte.net'),
(17, 'livre', 'azerty', 'auteur 3', 'public/images/livres/livre_azerty_auteur 3_14-09-2019_20-20-23.png', '2019-09-14 20:20:23', 'adri_00@hotmail.fr'),
(18, 'livre', 'livre 1', 'auteur 3', 'public/images/livres/livre_livre 1_auteur 3_14-09-2019_20-22-18.png', '2019-09-14 20:22:18', 'adri_00@hotmail.fr'),
(19, 'serie', 'ee', 'zertgyhgfbd dgd', 'public/images/series/serie_ee_zertgyhgfbd dgd_14-09-2019_20-23-45.png', '2019-09-14 20:23:45', 'adri_00@hotmail.fr');

-- --------------------------------------------------------

--
-- Structure de la table `identifiants`
--

DROP TABLE IF EXISTS `identifiants`;
CREATE TABLE IF NOT EXISTS `identifiants` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(3500) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `identifiants`
--

INSERT INTO `identifiants` (`id`, `nom`, `prenom`, `email`, `password`, `photo`, `date`) VALUES
(8, 'Maillard', 'Adrien', 'adrien.mld@laposte.net', '58ad983135fe15c5a8e2e15fb5b501aedcf70dc2', 'public/images/profils/Maillard_Adrien_08-09-2019_17-06-04.jpg', '2019-09-08 17:06:04'),
(16, 'Gazon', 'Jean', 'adri_00@hotmail.fr', '58ad983135fe15c5a8e2e15fb5b501aedcf70dc2', 'public/images/default.png', '2019-09-09 00:06:42');

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `email_destinataire` varchar(100) NOT NULL,
  `email_expediteur` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `vu` tinyint(1) NOT NULL,
  `date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `messages`
--

INSERT INTO `messages` (`id`, `nom`, `prenom`, `email_destinataire`, `email_expediteur`, `message`, `vu`, `date`) VALUES
(24, 'Gazon', 'Jean', 'adrien.mld@laposte.net', 'adri_00@hotmail.fr', 'Et un troisième.', 1, '2019-09-13 17:30:49'),
(23, 'Gazon', 'Jean', 'adrien.mld@laposte.net', 'adri_00@hotmail.fr', 'Un 2e message', 1, '2019-09-13 17:30:34'),
(6, 'Maillard', 'Adrien', 'adri_00@hotmail.fr', 'adrien.mld@laposte.net', 'yop', 0, '2019-09-09 18:20:14'),
(7, 'Maillard', 'Adrien', 'adri_00@hotmail.fr', 'adrien.mld@laposte.net', 'yop', 0, '2019-09-09 18:20:20'),
(8, 'Maillard', 'Adrien', 'adri_00@hotmail.fr', 'adrien.mld@laposte.net', 'yop', 0, '2019-09-09 18:24:49'),
(9, 'Maillard', 'Adrien', 'adri_00@hotmail.fr', 'adrien.mld@laposte.net', 'yop yop yop', 0, '2019-09-09 18:24:57'),
(10, 'Maillard', 'Adrien', 'adri_00@hotmail.fr', 'adrien.mld@laposte.net', 'yopman', 0, '2019-09-09 18:26:30'),
(11, 'Maillard', 'Adrien', 'adri_00@hotmail.fr', 'adrien.mld@laposte.net', 'zerty', 0, '2019-09-09 18:27:33'),
(12, 'Maillard', 'Adrien', 'adri_00@hotmail.fr', 'adrien.mld@laposte.net', 'zerty', 0, '2019-09-09 18:28:38'),
(13, 'Maillard', 'Adrien', 'adri_00@hotmail.fr', 'adrien.mld@laposte.net', 'azerty', 0, '2019-09-09 18:29:52'),
(14, 'Maillard', 'Adrien', 'adri_00@hotmail.fr', 'adrien.mld@laposte.net', 'azerty', 0, '2019-09-09 18:30:54'),
(15, 'Maillard', 'Adrien', 'adri_00@hotmail.fr', 'adrien.mld@laposte.net', 'azert', 0, '2019-09-09 18:32:41'),
(16, 'Maillard', 'Adrien', 'adri_00@hotmail.fr', 'adrien.mld@laposte.net', 'yo', 0, '2019-09-10 00:58:24'),
(18, 'Maillard', 'Adrien', 'adri_00@hotmail.fr', 'adrien.mld@laposte.net', 'salut l\'ami', 0, '2019-09-10 14:49:04'),
(19, 'Maillard', 'Adrien', 'adri_00@hotmail.fr', 'adrien.mld@laposte.net', 'salut jean gazon', 1, '2019-09-10 15:02:35'),
(20, 'Maillard', 'Adrien', 'adri_00@hotmail.fr', 'adrien.mld@laposte.net', 're', 0, '2019-09-10 15:13:41');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
