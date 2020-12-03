-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 03 dec 2020 om 14:25
-- Serverversie: 5.7.17
-- PHP-versie: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gipcorr`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tblactiviteit`
--

CREATE TABLE `tblactiviteit` (
  `actID` int(11) NOT NULL,
  `actAuteursID` int(11) NOT NULL,
  `actFoto` text NOT NULL,
  `actNm` tinytext NOT NULL,
  `actTypeID` int(11) NOT NULL,
  `persAantal` int(11) NOT NULL,
  `persLeeftijdMax` int(11) NOT NULL,
  `persLeeftijdMin` int(11) NOT NULL,
  `actBesch` mediumtext NOT NULL,
  `tijdID` int(11) NOT NULL,
  `benNodig` tinyint(1) NOT NULL,
  `benOpsomming` longtext NOT NULL,
  `actPrijs` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tblauteur`
--

CREATE TABLE `tblauteur` (
  `auteurID` int(11) NOT NULL,
  `auteurNm` tinytext NOT NULL,
  `auteurBesch` mediumtext NOT NULL,
  `auteurFoto` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `tblauteur`
--

INSERT INTO `tblauteur` (`auteurID`, `auteurNm`, `auteurBesch`, `auteurFoto`) VALUES
(1, 'Alfa', 'Test1', 'ws.png'),
(2, 'beta', 'test2', 'apple-touch-icon.png'),
(3, 'Delta', 'ughghg', 'blog-author.jpg');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tblauteursperact`
--

CREATE TABLE `tblauteursperact` (
  `actAuteursID` int(11) NOT NULL,
  `auteurID` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tbldatumtijd`
--

CREATE TABLE `tbldatumtijd` (
  `tijdID` int(11) NOT NULL,
  `actDatum` date NOT NULL,
  `actTijd` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tblinteresse`
--

CREATE TABLE `tblinteresse` (
  `interesseID` int(11) NOT NULL,
  `interesseNm` tinytext NOT NULL,
  `interesseBesch` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tblinteressesuser`
--

CREATE TABLE `tblinteressesuser` (
  `interessesID` int(11) NOT NULL,
  `interesseID` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tbltypes`
--

CREATE TABLE `tbltypes` (
  `actTypeID` int(11) NOT NULL,
  `actType` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tbluser`
--

CREATE TABLE `tbluser` (
  `userID` int(11) NOT NULL,
  `userNm` tinytext NOT NULL,
  `userStraat` text NOT NULL,
  `userGemeente` text NOT NULL,
  `userPostcode` text NOT NULL,
  `interessesID` int(11) NOT NULL,
  `userFoto` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tblusersperact`
--

CREATE TABLE `tblusersperact` (
  `actID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `inschrDatum` date NOT NULL,
  `betaald` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `tblactiviteit`
--
ALTER TABLE `tblactiviteit`
  ADD PRIMARY KEY (`actID`);

--
-- Indexen voor tabel `tblauteur`
--
ALTER TABLE `tblauteur`
  ADD PRIMARY KEY (`auteurID`);

--
-- Indexen voor tabel `tblauteursperact`
--
ALTER TABLE `tblauteursperact`
  ADD PRIMARY KEY (`actAuteursID`);

--
-- Indexen voor tabel `tbldatumtijd`
--
ALTER TABLE `tbldatumtijd`
  ADD PRIMARY KEY (`tijdID`);

--
-- Indexen voor tabel `tblinteresse`
--
ALTER TABLE `tblinteresse`
  ADD PRIMARY KEY (`interesseID`);

--
-- Indexen voor tabel `tblinteressesuser`
--
ALTER TABLE `tblinteressesuser`
  ADD PRIMARY KEY (`interessesID`);

--
-- Indexen voor tabel `tbltypes`
--
ALTER TABLE `tbltypes`
  ADD PRIMARY KEY (`actTypeID`);

--
-- Indexen voor tabel `tbluser`
--
ALTER TABLE `tbluser`
  ADD PRIMARY KEY (`userID`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `tblactiviteit`
--
ALTER TABLE `tblactiviteit`
  MODIFY `actID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `tblauteur`
--
ALTER TABLE `tblauteur`
  MODIFY `auteurID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT voor een tabel `tblauteursperact`
--
ALTER TABLE `tblauteursperact`
  MODIFY `actAuteursID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `tbldatumtijd`
--
ALTER TABLE `tbldatumtijd`
  MODIFY `tijdID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `tblinteresse`
--
ALTER TABLE `tblinteresse`
  MODIFY `interesseID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `tblinteressesuser`
--
ALTER TABLE `tblinteressesuser`
  MODIFY `interessesID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `tbltypes`
--
ALTER TABLE `tbltypes`
  MODIFY `actTypeID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `tbluser`
--
ALTER TABLE `tbluser`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
