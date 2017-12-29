-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Erstellungszeit: 18. Dez 2017 um 06:24
-- Server-Version: 10.1.19-MariaDB
-- PHP-Version: 5.6.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `robosax_2017_12_17`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ablauf`
--

CREATE TABLE `ablauf` (
  `ablaufID` smallint(6) NOT NULL,
  `position` smallint(6) NOT NULL,
  `typ` smallint(6) NOT NULL DEFAULT '4' COMMENT '0 - Ansprache, 1 - Block, 2 - Mittag, 3 - Teamleiterbesprechung, 4 - Verzögerung, 5 - Siegerehrung',
  `bezeichnung` varchar(100) NOT NULL,
  `zeit` time NOT NULL,
  `dauer` smallint(6) NOT NULL DEFAULT '0' COMMENT 'in Minuten',
  `puffer` smallint(6) NOT NULL DEFAULT '0' COMMENT 'in Minuten'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `ablauf`
--

INSERT INTO `ablauf` (`ablaufID`, `position`, `typ`, `bezeichnung`, `zeit`, `dauer`, `puffer`) VALUES
(1, 0, 1, 'Hallo', '13:30:00', 50, 10),
(2, 1, 1, 'dfghdgfhdfgh', '12:48:00', 13, 5),
(3, 2, 1, 'hgsdfzhgoadfg', '13:00:00', 45, 21),
(4, 3, 1, 'jljhl', '15:42:00', 67, 15);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `anzeige`
--

CREATE TABLE `anzeige` (
  `anzeigeID` smallint(6) NOT NULL,
  `typ` smallint(6) NOT NULL DEFAULT '0' COMMENT '0 - Website, 1 - PDF, 2 - Bild',
  `pfad` varchar(100) NOT NULL,
  `dauer` smallint(6) NOT NULL COMMENT 'in Millisekunden',
  `aktiv` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `anzeige`
--

INSERT INTO `anzeige` (`anzeigeID`, `typ`, `pfad`, `dauer`, `aktiv`) VALUES
(1, 0, 'text/ablauf.html', 2000, 1),
(2, 0, 'text/sponsoren.html', 3500, 1),
(8, 0, 'text/spielstand.php', 5000, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `block`
--

CREATE TABLE `block` (
  `blockID` smallint(6) NOT NULL,
  `ablaufID` smallint(6) DEFAULT NULL,
  `art` smallint(6) NOT NULL DEFAULT '0' COMMENT '0 - Alleinspiele, 1 - Normale Spiele, 2 - KO-Spiele',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 - ausstehend, 1 - läuft, 2 - abgeschlossen',
  `manuell` tinyint(1) NOT NULL DEFAULT '0',
  `sichtbar_anzeige` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `block`
--

INSERT INTO `block` (`blockID`, `ablaufID`, `art`, `status`, `manuell`, `sichtbar_anzeige`) VALUES
(0, 1, 0, 2, 0, 1),
(1, 2, 0, 0, 0, 1),
(2, 3, 0, 1, 0, 1),
(3, 4, 0, 0, 0, 1),
(4, NULL, 0, 0, 0, 1),
(5, NULL, 0, 2, 0, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `spiel`
--

CREATE TABLE `spiel` (
  `spielID` smallint(6) NOT NULL,
  `blockID` smallint(6) NOT NULL,
  `position` smallint(6) NOT NULL,
  `teamID1` smallint(6) DEFAULT NULL,
  `teamID2` smallint(6) DEFAULT NULL,
  `punkte1` smallint(6) NOT NULL DEFAULT '0',
  `punkte2` smallint(6) NOT NULL DEFAULT '0',
  `strafpunkte1` smallint(6) NOT NULL DEFAULT '0',
  `strafpunkte2` smallint(6) NOT NULL DEFAULT '0',
  `handeingriffe1` smallint(6) NOT NULL DEFAULT '0',
  `handeingriffe2` smallint(6) NOT NULL DEFAULT '0',
  `punktegeaendert` tinyint(1) NOT NULL DEFAULT '0',
  `zeitgeaendert` tinyint(1) NOT NULL DEFAULT '0',
  `status` smallint(6) NOT NULL DEFAULT '0' COMMENT '0 - Spiel ausstehend; 1 - Spiel läuft; 2 - Spiel abgeschlossen',
  `fixiert` tinyint(1) NOT NULL DEFAULT '0',
  `zeit` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `spiel`
--

INSERT INTO `spiel` (`spielID`, `blockID`, `position`, `teamID1`, `teamID2`, `punkte1`, `punkte2`, `strafpunkte1`, `strafpunkte2`, `handeingriffe1`, `handeingriffe2`, `punktegeaendert`, `zeitgeaendert`, `status`, `fixiert`, `zeit`) VALUES
(1, 0, 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '02:13:00'),
(2, 0, 1, 3, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '02:18:00'),
(3, 0, 2, 5, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '02:23:00'),
(4, 0, 3, 7, 8, 0, 0, 0, 0, 0, 0, 0, 1, 0, 1, '02:28:00'),
(5, 0, 4, 9, 10, 0, 0, 0, 0, 0, 2, 0, 0, 0, 1, '02:33:00'),
(6, 0, 5, 11, 12, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '02:38:00'),
(7, 0, 6, 13, 14, 7, 6, 0, 5, 123, 4, 0, 0, 2, 0, '02:43:00'),
(8, 0, 7, 15, 16, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '02:48:00'),
(9, 0, 8, 17, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '02:53:00'),
(10, 1, 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '12:30:00'),
(11, 1, 1, 3, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '12:35:00'),
(12, 1, 2, 5, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '12:40:00'),
(13, 1, 3, 7, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '12:45:00'),
(14, 1, 4, 9, 10, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '12:50:00'),
(15, 1, 5, 11, 12, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '12:55:00'),
(16, 1, 6, 13, 14, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, '13:00:00'),
(17, 1, 7, 15, 16, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '13:05:00'),
(18, 1, 8, 17, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '13:10:00'),
(19, 2, 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '13:20:00'),
(20, 2, 1, 3, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '13:45:00'),
(21, 2, 2, 5, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '13:50:00'),
(22, 2, 3, 7, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '13:55:00'),
(23, 2, 4, 9, 10, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '14:00:00'),
(24, 2, 5, 11, 12, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '14:05:00'),
(25, 2, 6, 13, 14, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '14:10:00'),
(26, 2, 7, 15, 16, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '14:15:00'),
(27, 2, 8, 17, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '14:20:00'),
(28, 3, 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '14:40:00'),
(29, 3, 1, 3, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '14:45:00'),
(30, 3, 2, 5, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '14:50:00'),
(31, 3, 3, 7, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '14:55:00'),
(32, 3, 4, 9, 10, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '15:00:00'),
(33, 3, 5, 11, 12, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '15:05:00'),
(34, 3, 6, 13, 14, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '15:10:00'),
(35, 3, 7, 15, 16, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '15:15:00'),
(36, 3, 8, 17, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '15:43:00'),
(37, 4, 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '16:13:00'),
(38, 4, 1, 3, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '16:18:00'),
(39, 4, 2, 5, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '16:23:00'),
(40, 4, 3, 7, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '16:28:00'),
(41, 4, 4, 9, 10, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '16:33:00'),
(42, 4, 5, 11, 12, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '16:38:00'),
(43, 4, 6, 13, 14, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '16:43:00'),
(44, 4, 7, 15, 16, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '16:48:00'),
(45, 4, 8, 17, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '16:53:00');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `spiel_aktiv`
--

CREATE TABLE `spiel_aktiv` (
  `spielID` smallint(6) NOT NULL,
  `zeit` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `team`
--

CREATE TABLE `team` (
  `teamID` smallint(6) NOT NULL,
  `teamname` varchar(100) NOT NULL,
  `anwesenheit` tinyint(1) NOT NULL DEFAULT '0',
  `ko` smallint(6) DEFAULT NULL COMMENT 'nach Runde'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `team`
--

INSERT INTO `team` (`teamID`, `teamname`, `anwesenheit`, `ko`) VALUES
(1, 'RaspBotin', 1, NULL),
(2, 'RobotikAG', 1, NULL),
(3, 'The Constructors', 1, NULL),
(4, 'Kepler BoeBot', 1, NULL),
(5, 'Goe-Tec', 1, NULL),
(6, 'MES Alsfeld', 1, NULL),
(7, 'Thum 1', 1, NULL),
(8, 'Hashtag', 1, NULL),
(9, 'Alpha Team', 1, NULL),
(10, 'JMG-Robotik', 1, NULL),
(11, 'Afra 007', 1, NULL),
(12, 'Afra 2', 1, NULL),
(13, 'Afra 3', 1, NULL),
(14, 'fgp-roler', 1, NULL),
(15, 'fgp-rocher', 1, NULL),
(16, 'bip-kreativ', 1, NULL),
(17, 'Juniorteam Crimotten', 1, NULL);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `ablauf`
--
ALTER TABLE `ablauf`
  ADD PRIMARY KEY (`ablaufID`);

--
-- Indizes für die Tabelle `anzeige`
--
ALTER TABLE `anzeige`
  ADD PRIMARY KEY (`anzeigeID`);

--
-- Indizes für die Tabelle `block`
--
ALTER TABLE `block`
  ADD PRIMARY KEY (`blockID`),
  ADD KEY `anzeigeID` (`ablaufID`);

--
-- Indizes für die Tabelle `spiel`
--
ALTER TABLE `spiel`
  ADD PRIMARY KEY (`spielID`),
  ADD KEY `blockID` (`blockID`),
  ADD KEY `teamID1` (`teamID1`),
  ADD KEY `teamID2` (`teamID2`);

--
-- Indizes für die Tabelle `spiel_aktiv`
--
ALTER TABLE `spiel_aktiv`
  ADD PRIMARY KEY (`spielID`),
  ADD KEY `spielID` (`spielID`);

--
-- Indizes für die Tabelle `team`
--
ALTER TABLE `team`
  ADD PRIMARY KEY (`teamID`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `ablauf`
--
ALTER TABLE `ablauf`
  MODIFY `ablaufID` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT für Tabelle `anzeige`
--
ALTER TABLE `anzeige`
  MODIFY `anzeigeID` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT für Tabelle `spiel`
--
ALTER TABLE `spiel`
  MODIFY `spielID` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;
--
-- AUTO_INCREMENT für Tabelle `team`
--
ALTER TABLE `team`
  MODIFY `teamID` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `spiel`
--
ALTER TABLE `spiel`
  ADD CONSTRAINT `spiel_ibfk_1` FOREIGN KEY (`blockID`) REFERENCES `block` (`blockID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `spiel_ibfk_2` FOREIGN KEY (`teamID1`) REFERENCES `team` (`teamID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `spiel_ibfk_3` FOREIGN KEY (`teamID2`) REFERENCES `team` (`teamID`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints der Tabelle `spiel_aktiv`
--
ALTER TABLE `spiel_aktiv`
  ADD CONSTRAINT `spiel_aktiv_ibfk_1` FOREIGN KEY (`spielID`) REFERENCES `spiel` (`spielID`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
