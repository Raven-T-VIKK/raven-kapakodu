-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 04, 2026 at 08:09 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.36

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kapakodu`
--

-- --------------------------------------------------------

--
-- Table structure for table `liigid`
--

CREATE TABLE `liigid` (
  `id` int UNSIGNED NOT NULL,
  `nimetus` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `liigid`
--

INSERT INTO `liigid` (`id`, `nimetus`) VALUES
(1, 'Koer'),
(2, 'Kass'),
(3, 'Lind'),
(4, 'Näriline'),
(5, 'Küülik');

-- --------------------------------------------------------

--
-- Table structure for table `loomad`
--

CREATE TABLE `loomad` (
  `id` int UNSIGNED NOT NULL,
  `liik_id` int UNSIGNED NOT NULL,
  `toug_id` int UNSIGNED DEFAULT NULL,
  `tootaja_id` int UNSIGNED NOT NULL,
  `nimi` varchar(100) NOT NULL,
  `sugu` enum('isane','emane') NOT NULL,
  `vanus` int UNSIGNED NOT NULL,
  `sunnikuupaev` date DEFAULT NULL,
  `suurus` enum('väike','keskmine','suur') NOT NULL,
  `varvus` varchar(100) NOT NULL,
  `asukoht` varchar(100) NOT NULL,
  `luhikirjeldus` varchar(255) NOT NULL,
  `kirjeldus` text NOT NULL,
  `pilt` varchar(255) NOT NULL,
  `staatus` enum('saadaval','broneeritud','hoolduses') NOT NULL DEFAULT 'saadaval',
  `steriliseeritud` tinyint(1) NOT NULL DEFAULT '0',
  `kiibistatud` tinyint(1) NOT NULL DEFAULT '0',
  `lisatud` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `loomad`
--

INSERT INTO `loomad` (`id`, `liik_id`, `toug_id`, `tootaja_id`, `nimi`, `sugu`, `vanus`, `sunnikuupaev`, `suurus`, `varvus`, `asukoht`, `luhikirjeldus`, `kirjeldus`, `pilt`, `staatus`, `steriliseeritud`, `kiibistatud`, `lisatud`) VALUES
(2, 2, NULL, 2, 'Luna', 'emane', 2, '2024-03-05', 'väike', 'Hall', 'Tartu', 'Rahulik kass', 'Naudib aknalaual päikese käes lesimist.', 'luna.jpg', 'saadaval', 1, 0, '2026-06-30'),
(3, 1, 1, 3, 'Rex', 'isane', 2, '2023-05-12', 'keskmine', 'Kuldne', 'Tallinn', 'Sõbralik koer', 'Energiat täis ja armastab jalutuskäike.', 'rex.jpg', 'saadaval', 1, 1, '2026-07-01'),
(4, 2, 5, 4, 'Mimi', 'emane', 6, '2020-08-14', 'väike', 'Hall-valge', 'Tartu', 'Rahulik kass', 'Väga sõbralik ning naudib inimese seltskonda.', 'mimi.jpg', 'broneeritud', 1, 1, '2026-07-02'),
(5, 1, 2, 1, 'Bruno', 'isane', 5, '2021-01-22', 'suur', 'Pruun', 'Pärnu', 'Valvekoer', 'Rahulik ja ustav perekoer.', 'bruno.jpg', 'broneeritud', 1, 1, '2026-07-03'),
(6, 1, 3, 2, 'Bella', 'emane', 1, '2025-02-18', 'keskmine', 'Must', 'Haapsalu', 'Mänguhimuline', 'Noor ja aktiivne koer.', 'bella.jpg', 'saadaval', 0, 1, '2026-07-04'),
(7, 2, 4, 3, 'Miisu', 'emane', 4, '2022-06-30', 'väike', 'Triibuline', 'Viljandi', 'Uudishimulik kass', 'Armastab ronida ja mängida.', 'miisu.jpg', 'broneeritud', 1, 1, '2026-07-05'),
(8, 2, 6, 4, 'Nurr', 'isane', 3, '2023-03-10', 'väike', 'Hall', 'Rakvere', 'Vaikne kass', 'Sobib rahulikku koju.', 'nurr.jpg', 'hoolduses', 1, 0, '2026-07-06'),
(9, 1, NULL, 1, 'Musti', 'isane', 3, '2022-04-01', 'keskmine', 'Pruun-must', 'Tallinn', 'Rõõmsameelne', 'Naudib pikki jalutuskäike.', 'musti.jpg', 'saadaval', 1, 1, '2026-07-07'),
(10, 1, NULL, 2, 'Lotte', 'emane', 7, '2019-09-19', 'keskmine', 'Beež', 'Narva', 'Rahulik', 'Väga sõbralik vanem koer.', 'lotte.jpg', 'saadaval', 1, 1, '2026-07-08'),
(11, 2, NULL, 3, 'Pätu', 'isane', 2, '2024-02-11', 'väike', 'Must', 'Jõhvi', 'Mänguline', 'Armastab pallidega mängida.', 'patu.jpg', 'saadaval', 0, 1, '2026-07-09'),
(12, 5, 11, 4, 'Kribu', 'emane', 1, '2025-01-15', 'väike', 'Valge', 'Tallinn', 'Armas küülik', 'Harjunud inimestega.', 'kribu.jpg', 'saadaval', 0, 0, '2026-07-10'),
(13, 1, NULL, 1, 'Roki', 'isane', 8, '2018-07-07', 'suur', 'Must', 'Tartu', 'Kogenud sõber', 'Rahulik ja kuulekas.', 'roki.jpg', 'hoolduses', 1, 1, '2026-07-11'),
(14, 2, NULL, 2, 'Lumi', 'emane', 5, '2021-12-24', 'väike', 'Valge', 'Pärnu', 'Hellik', 'Naudib pai tegemist.', 'lumi.jpg', 'saadaval', 1, 1, '2026-07-12'),
(15, 1, NULL, 3, 'Marta', 'emane', 4, '2022-10-09', 'keskmine', 'Pruun', 'Rapla', 'Sõbralik', 'Sobib lastega perre.', 'marta.jpg', 'broneeritud', 1, 1, '2026-07-13'),
(16, 2, NULL, 4, 'Simba', 'isane', 2, '2024-01-08', 'väike', 'Oranž', 'Valga', 'Julge', 'Väga uudishimulik kass.', 'simba.jpg', 'saadaval', 1, 0, '2026-07-14'),
(17, 1, NULL, 1, 'Bobby', 'isane', 6, '2020-05-03', 'suur', 'Must-valge', 'Võru', 'Truu kaaslane', 'Armastab õues joosta.', 'bobby.jpg', 'hoolduses', 1, 1, '2026-07-15'),
(18, 2, NULL, 2, 'Kiisu', 'emane', 1, '2025-03-14', 'väike', 'Kilpkonn', 'Paide', 'Noor kass', 'Õpib alles maailma tundma.', 'kiisu.jpg', 'saadaval', 0, 1, '2026-07-16'),
(19, 1, NULL, 3, 'Rufi', 'isane', 2, '2023-08-18', 'keskmine', 'Hall', 'Kuressaare', 'Aktiivne', 'Sobib sportlikule perele.', 'rufi.jpg', 'saadaval', 0, 1, '2026-07-17'),
(20, 1, NULL, 4, 'Muri', 'emane', 9, '2017-11-11', 'suur', 'Must', 'Tallinn', 'Rahulik', 'Vajab rahulikku kodu.', 'muri.jpg', 'hoolduses', 1, 1, '2026-07-18'),
(21, 2, NULL, 1, 'Triibu', 'isane', 7, '2019-04-20', 'väike', 'Triibuline', 'Põlva', 'Tasane', 'Meeldib aknal istuda.', 'triibu.jpg', 'broneeritud', 1, 1, '2026-07-19'),
(22, 2, NULL, 2, 'Sipsik', 'emane', 3, '2023-06-06', 'väike', 'Must-valge', 'Keila', 'Seltskondlik', 'Naudib mängimist ja tähelepanu.', 'sipsik.jpg', 'saadaval', 1, 1, '2026-07-20');

-- --------------------------------------------------------

--
-- Table structure for table `paringud`
--

CREATE TABLE `paringud` (
  `id` int UNSIGNED NOT NULL,
  `loom_id` int UNSIGNED DEFAULT NULL,
  `nimi` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `teema` varchar(150) NOT NULL,
  `sonum` text NOT NULL,
  `staatus` enum('uus','menetluses','suletud') NOT NULL DEFAULT 'uus',
  `lisatud` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `paringud`
--

INSERT INTO `paringud` (`id`, `loom_id`, `nimi`, `email`, `teema`, `sonum`, `staatus`, `lisatud`) VALUES
(1, 2, 'Karl Kask', 'karl@example.com', 'Huvi looma vastu', 'Soovin tulla loomaga tutvuma.', 'uus', '2026-11-04 09:58:52'),
(2, 3, 'Mari Mets', 'mari@example.com', 'Broneerimine', 'Kas loom on veel saadaval?', 'menetluses', '2026-11-04 09:58:52'),
(3, 5, 'Jaan Saar', 'jaan@example.com', 'Lisainfo', 'Kas loom sobib lastega perre?', 'uus', '2026-11-04 09:58:52'),
(4, 7, 'Liis Põld', 'liis@example.com', 'Külastus', 'Soovin külastusaja kokku leppida.', 'suletud', '2026-11-04 09:58:52'),
(5, 9, 'Anne Oja', 'anne@example.com', 'Huvi looma vastu', 'Kas loom on kiibistatud?', 'uus', '2026-11-04 09:58:52'),
(6, 12, 'Rasmus Kivi', 'rasmus@example.com', 'Broneerimine', 'Palun broneerida nädalavahetuseni.', 'menetluses', '2026-11-04 09:58:52'),
(7, 15, 'Kristi Hein', 'kristi@example.com', 'Lisainfo', 'Kas loom saab teiste loomadega läbi?', 'uus', '2026-11-04 09:58:52'),
(8, 18, 'Tarmo Lepp', 'tarmo@example.com', 'Küsimus', 'Kas loom on vaktsineeritud?', 'suletud', '2026-11-04 09:58:52'),
(9, 20, 'Eve Nurm', 'eve@example.com', 'Huvi looma vastu', 'Soovin adopteerimise kohta infot.', 'uus', '2026-11-04 09:58:52');

-- --------------------------------------------------------

--
-- Table structure for table `tootajad`
--

CREATE TABLE `tootajad` (
  `id` int UNSIGNED NOT NULL,
  `nimi` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefon` varchar(30) NOT NULL,
  `parool` varchar(255) NOT NULL,
  `roll` enum('admin','töötaja') NOT NULL,
  `lisatud` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tootajad`
--

INSERT INTO `tootajad` (`id`, `nimi`, `email`, `telefon`, `parool`, `roll`, `lisatud`) VALUES
(1, 'Mari Maasikas', 'mari@kapakodu.ee', '55510001', 'salasona', 'admin', '2026-11-04 09:58:52'),
(2, 'Jaan Tamm', 'jaan@kapakodu.ee', '55510002', 'salasona', 'töötaja', '2026-11-04 09:58:52'),
(3, 'Kati Kask', 'kati@kapakodu.ee', '55510003', 'salasona', 'töötaja', '2026-11-04 09:58:52'),
(4, 'Peeter Pihl', 'peeter@kapakodu.ee', '55510004', 'salasona', 'töötaja', '2026-11-04 09:58:52');

-- --------------------------------------------------------

--
-- Table structure for table `toud`
--

CREATE TABLE `toud` (
  `id` int UNSIGNED NOT NULL,
  `liik_id` int UNSIGNED NOT NULL,
  `nimetus` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `toud`
--

INSERT INTO `toud` (`id`, `liik_id`, `nimetus`) VALUES
(1, 1, 'Labrador'),
(2, 1, 'Saksa lambakoer'),
(3, 1, 'Kuldne retriiver'),
(4, 2, 'Maine Coon'),
(5, 2, 'Briti lühikarvaline'),
(6, 2, 'Siiami'),
(7, 3, 'Viirpapagoi'),
(8, 3, 'Kanaarilind'),
(9, 4, 'Merisiga'),
(10, 4, 'Hamster'),
(11, 5, 'Kääbusküülik');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `liigid`
--
ALTER TABLE `liigid`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loomad`
--
ALTER TABLE `loomad`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_loomad_liik` (`liik_id`),
  ADD KEY `fk_loomad_toug` (`toug_id`),
  ADD KEY `fk_loomad_tootaja` (`tootaja_id`);

--
-- Indexes for table `paringud`
--
ALTER TABLE `paringud`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_paringud_loom` (`loom_id`);

--
-- Indexes for table `tootajad`
--
ALTER TABLE `tootajad`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `toud`
--
ALTER TABLE `toud`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_toud_liik` (`liik_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `liigid`
--
ALTER TABLE `liigid`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `loomad`
--
ALTER TABLE `loomad`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `paringud`
--
ALTER TABLE `paringud`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tootajad`
--
ALTER TABLE `tootajad`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `toud`
--
ALTER TABLE `toud`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `loomad`
--
ALTER TABLE `loomad`
  ADD CONSTRAINT `fk_loomad_liik` FOREIGN KEY (`liik_id`) REFERENCES `liigid` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_loomad_tootaja` FOREIGN KEY (`tootaja_id`) REFERENCES `tootajad` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_loomad_toug` FOREIGN KEY (`toug_id`) REFERENCES `toud` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `paringud`
--
ALTER TABLE `paringud`
  ADD CONSTRAINT `fk_paringud_loom` FOREIGN KEY (`loom_id`) REFERENCES `loomad` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `toud`
--
ALTER TABLE `toud`
  ADD CONSTRAINT `fk_toud_liik` FOREIGN KEY (`liik_id`) REFERENCES `liigid` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
