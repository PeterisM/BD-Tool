SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) COLLATE utf8_latvian_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_latvian_ci NOT NULL,
  `password` varchar(40) COLLATE utf8_latvian_ci NOT NULL,
  `salt` varchar(32) COLLATE utf8_latvian_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_latvian_ci AUTO_INCREMENT=3 ;

INSERT INTO `user` (`id`, `name`, `email`, `password`, `salt`, `status`) VALUES
(1, 'guest', 'irrem@inbox.lv', '1d5bb3a6b294e0ee2f75387165dae2aec1b0ee89', '27ec7ba26e0cb44407cea4b8b656c89b', 1),
(2, 'peteris', 'peteris_m@inbox.lv', '1d5bb3a6b294e0ee2f75387165dae2aec1b0ee89', '27ec7ba26e0cb44407cea4b8b656c89b', 1);

DROP TABLE IF EXISTS `charts`;
CREATE TABLE IF NOT EXISTS `charts` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `user_id` int(6) DEFAULT '1',
  `name` varchar(100) COLLATE utf8_latvian_ci NOT NULL,
  `config` varchar(40000) COLLATE utf8_latvian_ci,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `data` varchar(255) NOT NULL,
  `img` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `width` int(6) DEFAULT '900',
  `height` int(6) DEFAULT '500',
  `date_created` timestamp NOT NULL,
  `date_modified` timestamp NOT NULL,
  `visited` int(10) DEFAULT '0',
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `user`(`id`)
  ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_latvian_ci AUTO_INCREMENT=10000002;

INSERT INTO `charts` (`id`, `user_id`, `name`, `config`, `data`, `img`, `type`, `width`, `height`, `status`, `date_created`, `date_modified`, `visited`) VALUES
(10000001, 1, 'Test', '\"{\\\"id\\\":\\\"10000001\\\",\\\"title\\\":\\\"Monthly Average Temperature\\\",\\\"ca_left\\\":\\\"60\\\",\\\"ca_top\\\":\\\"60\\\",\\\"ca_width\\\":\\\"70\\\",\\\"ca_height\\\":\\\"80\\\",\\\"titlePosition\\\":\\\"out\\\",\\\"backgroundColor\\\":\\\"ffffff\\\",\\\"legend_position\\\":\\\"right\\\",\\\"orientation\\\":\\\"horizontal\\\",\\\"vAxis_title\\\":\\\"Temperature (°C)\\\",\\\"hAxis_title\\\":\\\"Data\\\",\\\"series\\\":{\\\"0\\\":\\\"ffd000\\\",\\\"1\\\":\\\"cc3535\\\"}}\"','guest/tok_lon.csv', '', 'line', DEFAULT,  DEFAULT,  1, NULL, NULL, DEFAULT);

SET FOREIGN_KEY_CHECKS=1;
