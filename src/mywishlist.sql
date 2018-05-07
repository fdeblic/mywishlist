SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `liste`;
CREATE TABLE `liste` (
    `no` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) DEFAULT NULL,
    `titre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
    `description` text COLLATE utf8_unicode_ci,
    `expiration` date DEFAULT NULL,
    `token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
    `public` tinyint(1),
    PRIMARY KEY (`no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `liste` (`no`, `user_id`, `titre`, `description`, `expiration`, `token`,`public`) VALUES
(1,	1,	'Pour fêter le bac !',	'Pour un week-end à Nancy qui nous fera oublier les épreuves. ',	'2018-06-27',	'nosecure1', 1),
(2,	2,	"Liste de mariage d'Alice et Bob",	'Nous souhaitons passer un week-end royal à Nancy pour notre lune de miel :)',	'2018-06-30',	'nosecure2', 1),
(3,	3,	"C'est l\'anniversaire de Charlie",	'Pour lui préparer une fête dont il se souviendra :)',	'2017-12-12',	'nosecure3',1);

DROP TABLE IF EXISTS `item`;
CREATE TABLE `item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` text NOT NULL,
  `descr` text,
  `img` text,
  `tarif` decimal(5,2) DEFAULT NULL,
  `token` text,
  `url` text,
  `cagnotte` tinyint(1) NOT NULL DEFAULT '0',
  `liste_id` int(11) NOT NULL,
  CONSTRAINT fk_liste_id FOREIGN KEY (liste_id) REFERENCES Liste(no) ON DELETE CASCADE,
  PRIMARY KEY(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `item`
--

INSERT INTO `item` (`id`, `nom`, `descr`, `img`, `tarif`, `token`, `url`, `cagnotte`, `liste_id`) VALUES
(1, 'Champagne', 'Bouteille de champagne + flutes + jeux à gratter', 'champagne.jpg', '20.00', '', 'www.uneurl.com', 0, 2),
(2, 'Musique', 'Partitions de piano à 4 mains', 'musique.jpg', '25.00', '', 'www.uneurl.com', 0, 2),
(3, 'Exposition', 'Visite guidée de l’exposition ‘REGARDER’ à la galerie Poirel', 'poirelregarder.jpg', '14.00', '', 'www.uneurl.com', 0, 2),
(4, 'Goûter', 'Goûter au FIFNL', 'gouter.jpg', '20.00', '', 'www.uneurl.com', 0, 3),
(5, 'Projection', 'Projection courts-métrages au FIFNL', 'film.jpg', '10.00', '', 'www.uneurl.com', 0, 3),
(6, 'Bouquet', 'Bouquet de roses et Mots de Marion Renaud', 'rose.jpg', '16.00', '', 'www.uneurl.com', 0, 2),
(7, 'Diner Stanislas', 'Diner à La Table du Bon Roi Stanislas (Apéritif /Entrée / Plat / Vin / Dessert / Café / Digestif,0)', 'bonroi.jpg', '60.00', '', 'www.uneurl.com', 0, 2),
(8, 'Origami', 'Baguettes magiques en Origami en buvant un thé', 'origami.jpg', '12.00', '', 'www.uneurl.com', 0, 3),
(9, 'Livres', 'Livre bricolage avec petits-enfants + Roman', 'bricolage.jpg', '24.00', '', 'www.uneurl.com', 0, 3),
(10, 'Diner  Grand Rue ', 'Diner au Grand’Ru(e,0) (Apéritif / Entrée / Plat / Vin / Dessert / Café,0)', 'grandrue.jpg', '59.00', '', 'www.uneurl.com', 0, 2),
(11, 'Visite guidée', 'Visite guidée personnalisée de Saint-Epvre jusqu’à Stanislas', 'place.jpg', '11.00', '', 'www.uneurl.com', 0, 2),
(12, 'Bijoux', 'Bijoux de manteau + Sous-verre pochette de disque + Lait après-soleil', 'bijoux.jpg', '29.00', '', 'www.uneurl.com', 0, 2),
(19, 'Jeu contacts', 'Jeu pour échange de contacts', 'contact.png', '5.00', '', 'www.uneurl.com', 0, 1),
(22, 'Concert', 'Un concert à Nancy', 'concert.jpg', '17.00', '', 'www.uneurl.com', 0, 1),
(24, "Hôtel dHaussonville", "Hôtel d'Haussonville, au coeur de la Vieille ville à deux pas de la place Stanislas", 'hotel_haussonville_logo.jpg', '169.00', '', 'www.uneurl.com', 0, 2),
(25, 'Boite de nuit', 'Discothèque, Boîte tendance avec des soirées à thème & DJ invités', 'boitedenuit.jpg', '32.00', '', 'www.uneurl.com', 0, 1),
(26, 'Planètes Laser', 'Laser game : Gilet électronique et pistolet laser comme matériel, vous voilà équipé.', 'laser.jpg', '15.00', '', 'www.uneurl.com', 0, 1);


CREATE TABLE IF NOT EXISTS `account` (
  `id_account` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(70) NOT NULL,
  `prenom` varchar(70) NOT NULL,
  `login` varchar(70) NOT NULL,
  `password` varchar(100) NOT NULL,
  `admin` tinyint(1) NOT NULL,
  `participant` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_account`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `account` (`id_account`, `nom`, `prenom`, `login`, `password`, `admin`, `participant`) VALUES
(1, 'Spacher', 'Loïc', 'loic', 'seldpJxxtvI7E', 0, 1),
(2, 'Moreau', 'Elise', 'elise', 'seldpJxxtvI7E', 0, 0),
(3, 'de Blic', 'François', 'francois', 'seldpJxxtvI7E', 1, 1);



CREATE TABLE IF NOT EXISTS `message_list` (
  `id_message` int(11) NOT NULL AUTO_INCREMENT,
  `list_id` int(11) NOT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `id_creator` int(11) NOT NULL,
  `updated_at` TIMESTAMP,
  `created_at` TIMESTAMP,
  PRIMARY KEY (`id_message`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;



--
-- Structure de la table `pot_participation`
--

DROP TABLE IF EXISTS `pot_participation`;
CREATE TABLE `pot_participation` (
  `id_pot_participation` int(11) NOT NULL,
  `pot_id` int(11) NOT NULL,
  `name` varchar(70) NOT NULL,
  `amount` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Index pour les tables exportées
--

--
-- Index pour la table `pot_participation`
--
ALTER TABLE `pot_participation`
  ADD PRIMARY KEY (`id_pot_participation`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `pot_participation`
--
ALTER TABLE `pot_participation`
  MODIFY `id_pot_participation` int(11) NOT NULL AUTO_INCREMENT;
