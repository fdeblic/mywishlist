DROP PROCEDURE IF EXISTS reset;
DELIMITER |
CREATE PROCEDURE reset()
BEGIN
SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

--
-- Structure de la table `account`
--

DROP TABLE IF EXISTS `account`;
CREATE TABLE `account` (
  `id_account` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(30) NOT NULL,
  `prenom` varchar(30) NOT NULL,
  `login` varchar(30) NOT NULL,
  `password` varchar(70) NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  `deleted_at` date DEFAULT NULL,
  PRIMARY KEY (`id_account`),
  UNIQUE KEY `login` (`login`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `account`
--

INSERT INTO `account` (`id_account`, `nom`, `prenom`, `login`, `password`, `admin`) VALUES
(1, 'root', 'Admin', 'admin', 'seldpJxxtvI7E', 1),
(2, 'Salakis', 'Béatrice', 'bea', 'seldpJxxtvI7E', 0),
(3, 'Lamar', 'Carl', 'carl', 'seldpJxxtvI7E', 0);

-- --------------------------------------------------------

--
-- Structure de la table `item`
--

DROP TABLE IF EXISTS `item`;
CREATE TABLE `item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  `descr` text,
  `img` varchar(256) DEFAULT NULL,
  `tarif` decimal(5,2) NOT NULL DEFAULT '0.00',
  `token` varchar(70) NOT NULL,
  `url` text,
  `cagnotte` tinyint(1) NOT NULL DEFAULT '0',
  `booking_user` varchar(30) DEFAULT NULL,
  `message_booking` text,
  `liste_id` int(11) NOT NULL,
  `deleted_at` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_liste_id` (`liste_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=83 ;

--
-- Contenu de la table `item`
--

INSERT INTO `item` (`id`, `nom`, `descr`, `img`, `tarif`, `token`, `url`, `cagnotte`, `booking_user`, `message_booking`, `liste_id`) VALUES
(1, 'Item libre', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus nec pellentesque quam, ut imperdiet est. Donec at venenatis mi. Interdum et malesuada fames ac ante ipsum primis in faucibus.', 'champagne.jpg', '20.00', 'seldpJxxtvI7E', 'http://www.lien-externe.com', 0, NULL, NULL, 2),
(2, 'Item réservé', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus nec pellentesque quam, ut imperdiet est. Donec at venenatis mi. Interdum et malesuada fames ac ante ipsum primis in faucibus.', 'musique.jpg', '25.00', 'seldpJxxtvI7E', NULL, 0, 'Jackie', 'Félicitations !', 2),
(3, 'Item cagnotte', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus nec pellentesque quam, ut imperdiet est. Donec at venenatis mi. Interdum et malesuada fames ac ante ipsum primis in faucibus.', 'poirelregarder.jpg', '14.00', 'seldpJxxtvI7E', 'http://www.lien-externe.com', 1, NULL, NULL, 2),
(4, 'Item cagnotte 50%', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus nec pellentesque quam, ut imperdiet est. Donec at venenatis mi. Interdum et malesuada fames ac ante ipsum primis in faucibus.', 'rose.jpg', '16.00', 'seldpJxxtvI7E', 'http://www.lien-externe.com', 1, NULL, NULL, 2),
(5, 'Item cagnotte 100%', 'Diner à La Table du Bon Roi Stanislas (Apéritif /Entrée / Plat / Vin / Dessert / Café / Digestif,0)', 'bonroi.jpg', '60.00', 'seldpJxxtvI7E', 'http://www.lien-externe.com', 1, NULL, NULL, 2),
(6, 'Item sans descr', '', 'grandrue.jpg', '59.00', 'seldpJxxtvI7E', 'http://www.lien-externe.com', 0, NULL, NULL, 2),
(7, 'Item sans image', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus nec pellentesque quam, ut imperdiet est. Donec at venenatis mi. Interdum et malesuada fames ac ante ipsum primis in faucibus.', NULL, '11.00', 'seldpJxxtvI7E', 'http://www.lien-externe.com', 0, NULL, NULL, 2),
(8, 'Item sans lien', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus nec pellentesque quam, ut imperdiet est. Donec at venenatis mi. Interdum et malesuada fames ac ante ipsum primis in faucibus.', 'bijoux.jpg', '29.00', 'seldpJxxtvI7E', NULL, 0, NULL, NULL, 2),
(9, 'Item réservé, msg long', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus nec pellentesque quam, ut imperdiet est. Donec at venenatis mi. Interdum et malesuada fames ac ante ipsum primis in faucibus.', 'hotel_haussonville_logo.jpg', '169.00', 'seldpJxxtvI7E', 'http://www.lien-externe.com', 0, 'Grand-mère Jeannine', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus nec pellentesque quam, ut imperdiet est. Donec at venenatis mi. Interdum et malesuada fames ac ante ipsum primis in faucibus.', 2),
(28, 'Item libre', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus nec pellentesque quam, ut imperdiet est. Donec at venenatis mi. Interdum et malesuada fames ac ante ipsum primis in faucibus.', 'champagne.jpg', '20.00', 'seldpJxxtvI7E', 'http://www.lien-externe.com', 0, NULL, NULL, 1),
(29, 'Item réservé', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus nec pellentesque quam, ut imperdiet est. Donec at venenatis mi. Interdum et malesuada fames ac ante ipsum primis in faucibus.', 'musique.jpg', '25.00', 'seldpJxxtvI7E', NULL, 0, 'Jackie', 'Félicitations !', 1),
(30, 'Item cagnotte', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus nec pellentesque quam, ut imperdiet est. Donec at venenatis mi. Interdum et malesuada fames ac ante ipsum primis in faucibus.', 'poirelregarder.jpg', '14.00', 'seldpJxxtvI7E', 'http://www.lien-externe.com', 1, NULL, NULL, 1),
(31, 'Item cagnotte 50%', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus nec pellentesque quam, ut imperdiet est. Donec at venenatis mi. Interdum et malesuada fames ac ante ipsum primis in faucibus.', 'rose.jpg', '16.00', 'seldpJxxtvI7E', 'http://www.lien-externe.com', 1, NULL, NULL, 1),
(32, 'Item cagnotte 100%', 'Diner à La Table du Bon Roi Stanislas (Apéritif /Entrée / Plat / Vin / Dessert / Café / Digestif,0)', 'bonroi.jpg', '60.00', 'seldpJxxtvI7E', 'http://www.lien-externe.com', 1, NULL, NULL, 1),
(33, 'Item sans descr', '', 'grandrue.jpg', '59.00', 'seldpJxxtvI7E', 'http://www.lien-externe.com', 0, NULL, NULL, 1),
(34, 'Item sans image', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus nec pellentesque quam, ut imperdiet est. Donec at venenatis mi. Interdum et malesuada fames ac ante ipsum primis in faucibus.', NULL, '11.00', 'seldpJxxtvI7E', 'http://www.lien-externe.com', 0, NULL, NULL, 1),
(35, 'Item sans lien', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus nec pellentesque quam, ut imperdiet est. Donec at venenatis mi. Interdum et malesuada fames ac ante ipsum primis in faucibus.', 'bijoux.jpg', '29.00', 'seldpJxxtvI7E', NULL, 0, NULL, NULL, 1),
(36, 'Item réservé, msg long', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus nec pellentesque quam, ut imperdiet est. Donec at venenatis mi. Interdum et malesuada fames ac ante ipsum primis in faucibus.', 'hotel_haussonville_logo.jpg', '169.00', 'seldpJxxtvI7E', 'http://www.lien-externe.com', 0, 'Grand-mère Jeannine', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus nec pellentesque quam, ut imperdiet est. Donec at venenatis mi. Interdum et malesuada fames ac ante ipsum primis in faucibus.', 1),
(37, 'Item libre', 'Description item...', 'champagne.jpg', '20.00', 'seldpJxxtvI7E', 'http://www.lien-externe.com', 0, NULL, NULL, 3),
(38, 'Item réservé', 'Description item...', 'musique.jpg', '25.00', 'seldpJxxtvI7E', NULL, 0, 'Jackie', 'Félicitations !', 3),
(39, 'Item cagnotte', 'Description item...', 'poirelregarder.jpg', '14.00', 'seldpJxxtvI7E', 'http://www.lien-externe.com', 1, NULL, NULL, 3),
(40, 'Item cagnotte 50%', 'Description item...', 'rose.jpg', '16.00', 'seldpJxxtvI7E', 'http://www.lien-externe.com', 1, NULL, NULL, 3),
(41, 'Item cagnotte 100%', 'Description item...', 'bonroi.jpg', '60.00', 'seldpJxxtvI7E', 'http://www.lien-externe.com', 1, NULL, NULL, 3),
(42, 'Item sans descr', '', 'grandrue.jpg', '59.00', 'seldpJxxtvI7E', 'http://www.lien-externe.com', 0, NULL, NULL, 3),
(43, 'Item sans image', 'Description item...', NULL, '11.00', 'seldpJxxtvI7E', 'http://www.lien-externe.com', 0, NULL, NULL, 3),
(44, 'Item sans lien', 'Description item...', 'bijoux.jpg', '29.00', 'seldpJxxtvI7E', NULL, 0, NULL, NULL, 3),
(45, 'Item réservé, msg long', 'Description item...', 'hotel_haussonville_logo.jpg', '169.00', 'seldpJxxtvI7E', 'http://www.lien-externe.com', 0, 'Grand-mère Jeannine', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus nec pellentesque quam, ut imperdiet est. Donec at venenatis mi. Interdum et malesuada fames ac ante ipsum primis in faucibus.', 3),
(64, 'Item', 'Description item', NULL, '5.72', 'seD9grUeZ5NkM', '', 0, NULL, '', 6),
(65, 'Item libre', 'Description item...', 'champagne.jpg', '20.00', 'seldpJxxtvI7E', 'http://www.lien-externe.com', 0, NULL, NULL, 4),
(66, 'Item réservé', 'Description item...', 'musique.jpg', '25.00', 'seldpJxxtvI7E', NULL, 0, 'Jackie', 'Félicitations !', 4),
(67, 'Item cagnotte', 'Description item...', 'poirelregarder.jpg', '14.00', 'seldpJxxtvI7E', 'http://www.lien-externe.com', 1, NULL, NULL, 4),
(68, 'Item cagnotte 50%', 'Description item...', 'rose.jpg', '16.00', 'seldpJxxtvI7E', 'http://www.lien-externe.com', 1, NULL, NULL, 4),
(69, 'Item cagnotte 100%', 'Description item...', 'bonroi.jpg', '60.00', 'seldpJxxtvI7E', 'http://www.lien-externe.com', 1, NULL, NULL, 4),
(70, 'Item sans descr', '', 'grandrue.jpg', '59.00', 'seldpJxxtvI7E', 'http://www.lien-externe.com', 0, NULL, NULL, 4),
(71, 'Item sans image', 'Description item...', NULL, '11.00', 'seldpJxxtvI7E', 'http://www.lien-externe.com', 0, NULL, NULL, 4),
(72, 'Item sans lien', 'Description item...', 'bijoux.jpg', '29.00', 'seldpJxxtvI7E', NULL, 0, NULL, NULL, 4),
(73, 'Item réservé, msg long', 'Description item...', 'hotel_haussonville_logo.jpg', '169.00', 'seldpJxxtvI7E', 'http://www.lien-externe.com', 0, 'Grand-mère Jeannine', 'Description item...', 4),
(74, 'Item libre', 'Description item...', 'champagne.jpg', '20.00', 'seldpJxxtvI7E', 'http://www.lien-externe.com', 0, NULL, NULL, 5),
(75, 'Item réservé', 'Description item...', 'musique.jpg', '25.00', 'seldpJxxtvI7E', NULL, 0, 'Jackie', 'Félicitations !', 5),
(76, 'Item cagnotte', 'Description item...', 'poirelregarder.jpg', '14.00', 'seldpJxxtvI7E', 'http://www.lien-externe.com', 1, NULL, NULL, 5),
(77, 'Item cagnotte 50%', 'Description item...', 'rose.jpg', '16.00', 'seldpJxxtvI7E', 'http://www.lien-externe.com', 1, NULL, NULL, 5),
(78, 'Item cagnotte 100%', 'Description item...', 'bonroi.jpg', '60.00', 'seldpJxxtvI7E', 'http://www.lien-externe.com', 1, NULL, NULL, 5),
(79, 'Item sans descr', '', 'grandrue.jpg', '59.00', 'seldpJxxtvI7E', 'http://www.lien-externe.com', 0, NULL, NULL, 5),
(80, 'Item sans image', 'Description item...', NULL, '11.00', 'seldpJxxtvI7E', 'http://www.lien-externe.com', 0, NULL, NULL, 5),
(81, 'Item sans lien', 'Description item...', 'bijoux.jpg', '29.00', 'seldpJxxtvI7E', NULL, 0, NULL, NULL, 5),
(82, 'Item réservé, msg long', 'Description item...', 'hotel_haussonville_logo.jpg', '169.00', 'seldpJxxtvI7E', 'http://www.lien-externe.com', 0, 'Grand-mère Jeannine', 'Description item...', 5);

-- --------------------------------------------------------

--
-- Structure de la table `liste`
--

DROP TABLE IF EXISTS `liste`;
CREATE TABLE `liste` (
  `no` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `titre` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `expiration` date NOT NULL,
  `token` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `public` tinyint(1) NOT NULL DEFAULT '0',
  `deleted_at` date DEFAULT NULL,
  PRIMARY KEY (`no`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

--
-- Contenu de la table `liste`
--

INSERT INTO `liste` (`no`, `user_id`, `titre`, `description`, `expiration`, `token`, `public`) VALUES
(1, 3, 'Liste Carl [en cours, privée]', 'Pour un week-end à Nancy qui nous fera oublier les épreuves. ', '2019-12-11', 'seldpJxxtvI7E', 0),
(2, 2, 'Liste Béatrice [en cours, privée]', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus nec pellentesque quam, ut imperdiet est. Donec at venenatis mi. Interdum et malesuada fames ac ante ipsum primis in faucibus.', '2019-01-01', 'seldpJxxtvI7E', 0),
(3, 3, 'Liste Carl [finie, privée]', 'Pour lui préparer une fête dont il se souviendra :)', '2017-08-12', 'seldpJxxtvI7E', 0),
(4, 3, 'Liste Carl [finie, publique]', 'Description', '2017-06-04', 'casOYHjc0ny.Y', 1),
(5, 2, 'Liste Béatrice [en cours, publique]', 'Description', '2019-05-09', 'beUOdzea6gOyY', 1),
(6, 1, 'Liste Admin [en cours, privée]', 'Description', '2018-08-30', 'adoEAmooxOgx.', 0),
(7, 2, 'Pour fêter le bac !', 'Pour un week-end à Nancy qui nous fera oublier les épreuves.', '2018-06-27', 'be6wcJ8GAFB6k', 1),
(8, 3, 'Liste de mariage d\'Alice et Bob', 'Nous souhaitons passer un week-end royal à Nancy pour notre lune de miel :)', '2018-06-30', 'beIGabjE9GpWc', 1),
(9, 3, 'C\'est l\'anniversaire de Charlie', 'Pour lui préparer une fête dont il se souviendra :)', '2017-12-12', 'beEqSjqlqNyiw', 1);


-- --------------------------------------------------------

--
-- Structure de la table `message_list`
--

DROP TABLE IF EXISTS `message_list`;
CREATE TABLE `message_list` (
  `id_message` int(11) NOT NULL AUTO_INCREMENT,
  `list_id` int(11) NOT NULL,
  `body` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `id_creator` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` date DEFAULT NULL,
  PRIMARY KEY (`id_message`),
  KEY `list_id` (`list_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Contenu de la table `message_list`
--

INSERT INTO `message_list` (`id_message`, `list_id`, `body`, `id_creator`, `updated_at`, `created_at`) VALUES
(1, 1, 'News #1', 3, '2018-05-16 08:19:45', '2018-05-15 10:24:26'),
(2, 1, 'News #2', 3, '2018-05-16 08:19:49', '2018-05-15 10:24:31'),
(3, 1, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus nec pellentesque quam, ut imperdiet est. Donec at venenatis mi. Interdum et malesuada fames ac ante ipsum primis in faucibus.\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus nec pellentesque quam, ut imperdiet est. Donec at venenatis mi. Interdum et malesuada fames ac ante ipsum primis in faucibus.', 3, '2018-05-16 08:20:00', '2018-05-15 13:14:56'),
(4, 2, 'News #1', 2, '2018-05-16 08:06:37', '2018-05-16 08:06:37'),
(5, 2, 'News #2', 2, '2018-05-16 08:06:47', '2018-05-16 08:06:47'),
(6, 2, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus nec pellentesque quam, ut imperdiet est. Donec at venenatis mi. Interdum et malesuada fames ac ante ipsum primis in faucibus.\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus nec pellentesque quam, ut imperdiet est. Donec at venenatis mi. Interdum et malesuada fames ac ante ipsum primis in faucibus.', 2, '2018-05-16 08:19:55', '2018-05-16 08:06:58'),
(7, 4, 'Message #1', 1, '2018-05-16 08:47:25', '2018-05-16 08:47:25');

-- --------------------------------------------------------

--
-- Structure de la table `pot_participation`
--

DROP TABLE IF EXISTS `pot_participation`;
CREATE TABLE `pot_participation` (
  `id_pot_participation` int(11) NOT NULL AUTO_INCREMENT,
  `pot_id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `amount` decimal(5,2) NOT NULL,
  `deleted_at` date DEFAULT NULL,
  PRIMARY KEY (`id_pot_participation`),
  KEY `pot_id` (`pot_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=41 ;

--
-- Contenu de la table `pot_participation`
--

INSERT INTO `pot_participation` (`id_pot_participation`, `pot_id`, `name`, `amount`) VALUES
(1, 4, 'Kyshloren', '5.00'),
(2, 4, 'Bob l''éponge', '3.00'),
(3, 5, 'Jason', '10.00'),
(4, 5, 'Kimberly', '15.00'),
(5, 5, 'Trini', '7.00'),
(6, 5, 'Billy', '13.00'),
(7, 5, 'Zack', '10.50'),
(8, 5, 'Tommy', '4.50'),
(9, 31, 'Kyshloren', '5.00'),
(10, 31, 'Bob l''éponge', '3.00'),
(11, 32, 'Jason', '10.00'),
(12, 32, 'Kimberly', '15.00'),
(13, 32, 'Trini', '7.00'),
(14, 32, 'Billy', '13.00'),
(15, 32, 'Zack', '10.50'),
(16, 32, 'Tommy', '4.50'),
(17, 40, 'Kyshloren', '5.00'),
(18, 40, 'Bob l''éponge', '3.00'),
(19, 41, 'Jason', '10.00'),
(20, 41, 'Kimberly', '15.00'),
(21, 41, 'Trini', '7.00'),
(22, 41, 'Billy', '13.00'),
(23, 41, 'Zack', '10.50'),
(24, 41, 'Tommy', '4.50'),
(25, 68, 'Kyshloren', '5.00'),
(26, 68, 'Bob l''éponge', '3.00'),
(27, 69, 'Jason', '10.00'),
(28, 69, 'Kimberly', '15.00'),
(29, 69, 'Trini', '7.00'),
(30, 69, 'Billy', '13.00'),
(31, 69, 'Zack', '10.50'),
(32, 69, 'Tommy', '4.50'),
(33, 77, 'Kyshloren', '5.00'),
(34, 77, 'Bob l''éponge', '3.00'),
(35, 78, 'Jason', '10.00'),
(36, 78, 'Kimberly', '15.00'),
(37, 78, 'Trini', '7.00'),
(38, 78, 'Billy', '13.00'),
(39, 78, 'Zack', '10.50'),
(40, 78, 'Tommy', '4.50');

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `item`
--
ALTER TABLE `item`
  ADD CONSTRAINT `fk_liste_id` FOREIGN KEY (`liste_id`) REFERENCES `liste` (`no`) ON DELETE CASCADE;

--
-- Contraintes pour la table `pot_participation`
--
ALTER TABLE `pot_participation`
  ADD CONSTRAINT `pot_participation_ibfk_1` FOREIGN KEY (`pot_id`) REFERENCES `item` (`id`);

END|
DELIMITER ;
CALL reset();
