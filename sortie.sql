-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 06 mai 2026 à 09:21
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `wishorando`
--

-- --------------------------------------------------------

--
-- Structure de la table `sortie`
--

CREATE TABLE `sortie` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `depart_longitude` float NOT NULL,
  `depart_latitude` float NOT NULL,
  `description` text DEFAULT NULL,
  `parcours` varchar(50) DEFAULT NULL,
  `distance` float NOT NULL,
  `denivele` int(11) NOT NULL,
  `difficulte` varchar(50) DEFAULT NULL,
  `chien_autorise` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `sortie`
--

INSERT INTO `sortie` (`id`, `nom`, `depart_longitude`, `depart_latitude`, `description`, `parcours`, `distance`, `denivele`, `difficulte`, `chien_autorise`) VALUES
(1, '10.24 km 236 m Arbusigny, La Grange, Le Biollay, Chez Boget, Penavex, Arbusigny', 6.21871, 46.0914, 'Haute Savoie, commune d\'Arbusigny 10.24 km 236 m…', '10-24-km-236-m-arbusigny-la-grange-le-biollay-chez', 10.24, 320, 'Facile', 1),
(2, '11.25 km 403 m Cusy, Marsinge, Esery, Moussy , Vuret , Arculinge, Cusy', 6.24861, 46.134, 'Haute Savoie commune de Réignier-Esery 11.25 km 403 m…', '11-25-km-403-m-cusy-marsinge-esery-moussy-vuret-ar', 11.25, 690, 'Moyen', 1),
(3, 'Entre rives droite et gauche du Tech', 2.6347, 42.4576, 'Au départ d\'Arles sur Tech, le circuit grimpe…', '66-entre-rives-droite-et-gauche-du-tech-en-vallesp', 66, 2000, 'Difficile', 0),
(4, 'ABC - Sortie Locale 30/01/2011 - 40 km', 3.22028, 50.1752, 'ABC - Sortie Locale 30/01/2011 - 40 km', 'abc-sortie-locale-30-01-2011-40-km.gpx', 40, 430, 'Moyen', 0);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `sortie`
--
ALTER TABLE `sortie`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `sortie`
--
ALTER TABLE `sortie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
