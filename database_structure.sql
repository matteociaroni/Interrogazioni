-- phpMyAdmin SQL Dump
-- version 4.1.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Ott 29, 2020 alle 11:12
-- Versione del server: 5.6.33-log
-- PHP Version: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `my_db`
--
-- --------------------------------------------------------
--
-- Struttura della tabella `interrogazioni_alunni`
--

CREATE TABLE IF NOT EXISTS `interrogazioni_alunni` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(30) NOT NULL,
  `cognome` varchar(30) NOT NULL,
  `classe` varchar(4) NOT NULL,
  `email` varchar(40) NOT NULL,
  `password` varchar(40) NOT NULL,
  `auth_admin` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `interrogazioni_contatti`
--

CREATE TABLE IF NOT EXISTS `interrogazioni_contatti` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_alunno` int(11) NOT NULL,
  `messaggio` text NOT NULL,
  `data_inserimento` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_admin_risposta` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `interrogazioni_giorni`
--

CREATE TABLE IF NOT EXISTS `interrogazioni_giorni` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_sessione` int(11) NOT NULL,
  `data` date NOT NULL,
  `n_minimo` int(1) DEFAULT NULL,
  `n_massimo` int(1) DEFAULT NULL,
  `data_inserimento` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=32 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `interrogazioni_interrogati`
--

CREATE TABLE IF NOT EXISTS `interrogazioni_interrogati` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_alunno` int(11) NOT NULL,
  `id_giorno` int(11) NOT NULL,
  `data_inserimento` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=376 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `interrogazioni_log`
--

CREATE TABLE IF NOT EXISTS `interrogazioni_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_utente` int(11) NOT NULL,
  `pagina` varchar(100) NOT NULL,
  `data_inserimento` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=917 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `interrogazioni_messaggi`
--

CREATE TABLE IF NOT EXISTS `interrogazioni_messaggi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_mittente` int(11) NOT NULL,
  `id_destinatario` int(11) DEFAULT NULL,
  `messaggio` text NOT NULL,
  `data_inserimento` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `letto` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `interrogazioni_notifiche`
--

CREATE TABLE IF NOT EXISTS `interrogazioni_notifiche` (
  `letta` tinyint(1) NOT NULL DEFAULT '0',
  `id_notifica` int(11) NOT NULL AUTO_INCREMENT,
  `id_alunno` int(11) NOT NULL,
  `data` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `titolo` text NOT NULL,
  `messaggio` text NOT NULL,
  `link` text,
  `id_mittente` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_notifica`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=84 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `interrogazioni_sessioni`
--

CREATE TABLE IF NOT EXISTS `interrogazioni_sessioni` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `classe` varchar(4) NOT NULL,
  `materia` varchar(20) NOT NULL,
  `descrizione` text NOT NULL,
  `stato` tinyint(1) NOT NULL DEFAULT '0',
  `data_inserimento` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=26 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
