SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `touite`;
CREATE TABLE `touite` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `texte` varchar(235) NOT NULL,
  `date` date,
  `note` int(9) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `tag`;
CREATE TABLE `tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(100) NOT NULL,
  `description` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `image`;
CREATE TABLE `image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(235) NOT NULL,
  `chemin` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(256) NOT NULL,
  `pseudo` varchar(256) NOT NULL,
  `nom` varchar(256) NOT NULL,
  `prenom` varchar(256) NOT NULL,
  `passwd` varchar(256) NOT NULL,
  `role` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `user2touite`;
CREATE TABLE `user2touite` (
    `id_touite` int(11) NOT NULL,
    `id_user` int(11) NOT NULL,
    PRIMARY KEY (`id_touite`,`id_user`),
    CONSTRAINT `user2touite_ibfk_1` FOREIGN KEY (`id_touite`) REFERENCES `touite` (`id`),
    CONSTRAINT `user2touite_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `abonnement`;
CREATE TABLE `abonnement` (
  `id_user1` int(11) NOT NULL,
  `id_user2` int(11) NOT NULL,
  PRIMARY KEY (`id_user1`,`id_user2`),
  CONSTRAINT `abonnement_ibfk_1` FOREIGN KEY (`id_user1`) REFERENCES `user` (`id`),
  CONSTRAINT `abonnement_ibfk_2` FOREIGN KEY (`id_user2`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `user2tag`;
CREATE TABLE `user2tag` (
  `id_user` int(11) NOT NULL,
  `id_tag` int(11) NOT NULL,
  PRIMARY KEY (`id_user`,`id_tag`),
  CONSTRAINT `user2tag_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`),
  CONSTRAINT `user2tag_ibfk_2` FOREIGN KEY (`id_tag`) REFERENCES `tag` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `touite2tag`;
CREATE TABLE `touite2tag` (
  `id_touite` int(11) NOT NULL,
  `id_tag` int(11) NOT NULL,
  PRIMARY KEY (`id_touite`,`id_tag`),
  CONSTRAINT `touite2tag_ibfk_1` FOREIGN KEY (`id_touite`) REFERENCES `touite` (`id`),
  CONSTRAINT `touite2tag_ibfk_2` FOREIGN KEY (`id_tag`) REFERENCES `tag` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `touite2image`;
CREATE TABLE `touite2image` (
  `id_touite` int(11) NOT NULL,
  `id_image` int(11) NOT NULL,
  PRIMARY KEY (`id_touite`,`id_image`),
  CONSTRAINT `touite2image_ibfk_1` FOREIGN KEY (`id_touite`) REFERENCES `touite` (`id`),
  CONSTRAINT `touite2image_ibfk_2` FOREIGN KEY (`id_image`) REFERENCES `image` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `evaluer`;
CREATE TABLE `evaluer` (
    `id_user` int(11) NOT NULL,
    `id_touite` int(11) NOT NULL,
    `note` int(2) NOT NULL,
    PRIMARY KEY (`id_user`,`id_touite`),
    CONSTRAINT `evaluer_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`),
    CONSTRAINT `evaluer_ibfk_2` FOREIGN KEY (`id_touite`) REFERENCES `touite` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Insert Celena.dev
INSERT INTO `user` (`email`, `pseudo`, `nom`, `prenom`, `passwd`, `role`)
VALUES ('devoitine@mail.com', 'Celena.dev', 'Devoitine', 'Celena', 'Cdevoitine1&', 100);

-- Insert Victor.geo
INSERT INTO `user` (`email`, `pseudo`, `nom`, `prenom`, `passwd`)
VALUES ('georges@mail.com', 'Victor.geo', 'Georges', 'Victor', 'Vdeorges1&');

-- Insert Killian.rich_duti
INSERT INTO `user` (`email`, `pseudo`, `nom`, `prenom`, `passwd`)
VALUES ('richardin.dutilleul@mail.com', 'Killian.rich_duti', 'Richardin–Dutilleul', 'Killian', 'Krichardindutilleul1&');

-- Insert Massi.chek
INSERT INTO `user` (`email`, `pseudo`, `nom`, `prenom`, `passwd`)
VALUES ('cheklat@mail.com', 'Massi.chek', 'Cheklat', 'Ahmed-Massi', 'AMcheklat1&');

-- Insert Touite 1
INSERT INTO `touite` (`texte`, `date`, `note`)
VALUES ('Je suis fatigée.', '2022-10-14', 0);

-- Insert Touite 2
INSERT INTO `touite` (`texte`, `date`, `note`)
VALUES ('Rêveil toi et travail.', '2022-10-14', 0);

-- Insert Touite 3
INSERT INTO `touite` (`texte`, `date`, `note`)
VALUES ('Oh le professeur est la.', '2022-10-14', 0);

-- Insert Touite 4
INSERT INTO `touite` (`texte`, `date`, `note`)
VALUES ('Il y a un beau schema au tableau.', '2022-08-14', 0);

INSERT INTO `user2touite` (`id_touite`, `id_user`)
VALUES ( 1, 4);

INSERT INTO `user2touite` (`id_touite`, `id_user`)
VALUES ( 2, 3);

INSERT INTO `user2touite` (`id_touite`, `id_user`)
VALUES ( 3, 2);

INSERT INTO `user2touite` (`id_touite`, `id_user`)
VALUES ( 4, 2);

INSERT INTO `image` VALUES (1, 'tableau', './image/IMG_0420.png');

INSERT INTO `touite2image` VALUES (4, 1);

-- 2022-10-14 12:55:42