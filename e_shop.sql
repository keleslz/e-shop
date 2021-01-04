-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3308
-- Généré le :  mer. 16 déc. 2020 à 09:35
-- Version du serveur :  8.0.18
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
-- Base de données :  `e_shop`
--

-- --------------------------------------------------------

--
-- Structure de la table `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(255) NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `category`
--

INSERT INTO `category` (`category_id`, `category_name`) VALUES
(1, 'Vetements'),
(2, 'Images'),
(7, 'Immobilier'),

-- --------------------------------------------------------

--
-- Structure de la table `img`
--

DROP TABLE IF EXISTS `img`;
CREATE TABLE IF NOT EXISTS `img` (
  `img_id` int(11) NOT NULL AUTO_INCREMENT,
  `img_name` varchar(255) NOT NULL,
  `img_path` text NOT NULL,
  `id_product` int(11) DEFAULT NULL,
  PRIMARY KEY (`img_id`),
  UNIQUE KEY `img_name` (`img_name`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `img`
--

INSERT INTO `img` (`img_id`, `img_name`, `img_path`, `id_product`) VALUES
(1, '128640306905fd92027b8b05NETFLIX.png', 'C:\\Users\\elhab\\Desktop\\Projet\\eshop-prod\\/public/img-storage\\128640306905fd92027b8b05NETFLIX.png', NULL),
(2, '11708519105fd92041989c2req.jpeg', 'C:\\Users\\elhab\\Desktop\\Projet\\eshop-prod\\/public/img-storage\\11708519105fd92041989c2req.jpeg', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `product`
--

DROP TABLE IF EXISTS `product`;
CREATE TABLE IF NOT EXISTS `product` (
  `product_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_name` varchar(255) NOT NULL,
  `product_description` text NOT NULL,
  `product_slug` varchar(255) NOT NULL,
  `product_price` varchar(10) NOT NULL,
  `product_status` tinyint(1) NOT NULL,
  `product_created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_img` int(11) DEFAULT NULL,
  `id_category` int(11) DEFAULT '-1',
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `product`
--

INSERT INTO `product` (`product_id`, `product_name`, `product_description`, `product_slug`, `product_price`, `product_status`, `id_img`, `id_category`) VALUES
(1, 'azeaze', 'azeazeaze', 'azeaze', '2', 1, 1, 1),
(2, 'azeazezae', 'azeazeazeazeaze', 'azeazezae', '3', 1, -1, 2),
(3, 'azeazezae eaz aze', 'azeazeazeazeazec azazaze', 'azeazezae-eaz-aze', '3', 1, 2, 7);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(60) NOT NULL,
  `law` int(5) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,

  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `email`, `password`, `law`) VALUES
(1, 'admin@fr.fr', '$2y$10$re/MMXbk7qXgWFPT/XFJ5OMFZ.PSQNWNVfO1Bns4OZiStKpHfJ.mS', 65535),
(2, 'b@fr.fr', '$2y$10$sH5Y7BsIYRiEvGrW8U0Cb..9RRq8zfPBkSfipbMTb3ZnqrZuQfpBS', 1),
(3, 'c@r.fr', '$2y$10$GyepeM2xm5K8IU8d96zABODyLcIR/xIJa8RMjDrrdt5XQqRgv/iry', 1),
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- --------------------------------------------------------

--
-- Structure de la table `order`
--

DROP TABLE IF EXISTS `order`;
CREATE TABLE IF NOT EXISTS `order` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_name` varchar(255) NOT NULL,
  `order_surname` varchar(255) NOT NULL,
  `order_email` varchar(255) NOT NULL,
  `order_address` varchar(255) NOT NULL,
  `order_zip` VARCHAR(5) NOT NULL,
  `order_city` varchar(255) NOT NULL,
  `order_department` varchar(255) NOT NULL,
  `order_created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `order_article` TEXT(1000) NOT NULL,
  `order_state` BOOLEAN NOT NULL DEFAULT false,
  `id_user` int(11) DEFAULT NULL,
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------