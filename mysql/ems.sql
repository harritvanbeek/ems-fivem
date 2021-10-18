-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 15 okt 2021 om 08:54
-- Serverversie: 10.4.14-MariaDB
-- PHP-versie: 7.4.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ems`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `clientdata`
--

CREATE TABLE `clientdata` (
  `contentUuid` char(64) COLLATE utf8_bin NOT NULL,
  `clientUuid` char(64) COLLATE utf8_bin NOT NULL,
  `ssn` char(8) COLLATE utf8_bin NOT NULL,
  `firstname` char(64) COLLATE utf8_bin NOT NULL,
  `lastname` char(64) COLLATE utf8_bin NOT NULL,
  `createdate` datetime NOT NULL,
  `UserUuid` char(64) COLLATE utf8_bin NOT NULL,
  `MeFname` char(64) COLLATE utf8_bin NOT NULL,
  `MeLname` char(64) COLLATE utf8_bin NOT NULL,
  `MeCnumber` char(5) COLLATE utf8_bin NOT NULL,
  `MeRank` char(10) COLLATE utf8_bin NOT NULL,
  `subject` char(255) COLLATE utf8_bin NOT NULL,
  `content` longtext COLLATE utf8_bin NOT NULL,
  `postUpdate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `clients`
--

CREATE TABLE `clients` (
  `uuid` char(63) COLLATE utf8_bin NOT NULL,
  `ssn` char(63) COLLATE utf8_bin NOT NULL,
  `firstname` char(63) COLLATE utf8_bin NOT NULL,
  `lastname` char(63) COLLATE utf8_bin NOT NULL,
  `birthdate` char(63) COLLATE utf8_bin NOT NULL,
  `sex` char(6) COLLATE utf8_bin NOT NULL,
  `nationality` char(63) COLLATE utf8_bin NOT NULL,
  `phone_number` char(10) COLLATE utf8_bin NOT NULL,
  `driverlicense` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `users`
--

CREATE TABLE `users` (
  `uuid` char(36) COLLATE utf8_bin NOT NULL,
  `username` char(36) COLLATE utf8_bin NOT NULL,
  `password` char(63) COLLATE utf8_bin NOT NULL,
  `MeFname` char(36) COLLATE utf8_bin NOT NULL,
  `MeLname` char(36) COLLATE utf8_bin NOT NULL,
  `MeCnumber` char(5) COLLATE utf8_bin NOT NULL,
  `MeRank` char(36) COLLATE utf8_bin NOT NULL,
  `primissions` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Gegevens worden geëxporteerd voor tabel `users`
--

INSERT INTO `users` (`uuid`, `username`, `password`, `MeFname`, `MeLname`, `MeCnumber`, `MeRank`, `primissions`) VALUES
('673d2be2-bccf-4bb9-87a7-c60220ef1cc5', 'admin', '$2y$10$qg2gxTj.zs/tLvZRm4AoYuCxaz1/ZEKX9E4X949ToAnssedCLIqXm', 'Firstname', 'Lastname', '22-14', 'boss', 200);

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `clientdata`
--
ALTER TABLE `clientdata`
  ADD PRIMARY KEY (`contentUuid`);

--
-- Indexen voor tabel `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`uuid`);

--
-- Indexen voor tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`uuid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
