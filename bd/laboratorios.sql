-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 01-Nov-2016 às 23:17
-- Versão do servidor: 10.1.13-MariaDB
-- PHP Version: 5.5.37

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `farmabook`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `laboratorio`
--

CREATE TABLE `laboratorio` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `laboratorio`
--

INSERT INTO `laboratorio` (`id`, `nome`) VALUES
(3, 'Bristol-myers squibb farmacêutica ltda'),
(4, 'Eli lilly do brasil ltda'),
(5, 'Ems sigma pharma ltda'),
(6, 'Bayer s.a.'),
(7, 'Eurofarma laboratórios s.a.'),
(8, 'Aché laboratórios farmacêuticos s.a.'),
(9, 'Cimed indústria de medicamentos ltda'),
(10, 'Geolab indústria farmacêutica s/a'),
(11, 'Legrand pharma indústria farmacêutica ltda'),
(12, 'Mabra farmacêutica ltda.'),
(13, 'Medley farmacêutica ltda'),
(14, 'Laboratório globo ltda'),
(15, 'Laboratório teuto brasileiro s/a'),
(16, 'Laboratório neo química comércio e indústria ltda'),
(17, 'Germed farmaceutica ltda'),
(18, 'Biosintética farmacêutica ltda'),
(19, 'Ems s/a'),
(20, 'União química farmacêutica nacional s/a'),
(21, 'Nova quimica farmacêutica s/a'),
(22, 'Prati donaduzzi & cia ltda'),
(23, 'Laboratório farmacêutico elofar ltda'),
(24, 'Vitamedic industria farmaceutica ltda'),
(25, 'Ranbaxy farmacêutica ltda'),
(26, 'Multilab indústria e comércio de produtos farmacêuticos ltda'),
(27, 'Janssen-cilag farmacêutica ltda'),
(28, 'Sanofi-aventis farmacêutica ltda'),
(29, 'Merck sharp & dohme farmaceutica  ltda'),
(30, 'Merck s/a'),
(31, 'Laboratório químico farmacêutico bergamo ltda'),
(32, 'Apsen farmaceutica s/a'),
(33, 'Laboratórios ferring ltda'),
(34, 'Sun farmacêutica do brasil ltda'),
(35, 'Fundação para o remédio popular - furp'),
(36, 'Belfar ltda'),
(37, 'Greenpharma química e farmacêutica ltda'),
(38, 'Sandoz do brasil indústria farmacêutica ltda'),
(39, 'Kley hertz farmaceutica s.a'),
(40, 'Laboratórios osório de moraes ltda'),
(41, 'Sanval comércio e indústria ltda'),
(42, 'Laboratil farmaceutica ltda'),
(43, 'Instituto terapêutico delta ltda.'),
(44, 'Ativus farmacêutica ltda'),
(45, 'Novartis biociencias s.a'),
(46, 'Latinofarma industrias farmaceuticas ltda'),
(47, 'Schering-plough indústria farmacêutica ltda'),
(48, 'Teva farmacêutica ltda.'),
(49, 'Astrazeneca do brasil ltda'),
(50, 'Theraskin farmaceutica ltda.'),
(51, 'Shire farmacêutica brasil ltda.'),
(52, 'Beaufour ipsen farmacêutica ltda'),
(53, 'Abbvie farmacêutica ltda.'),
(54, 'Zodiac produtos farmacêuticos s/a'),
(55, 'Abbott laboratórios do brasil ltda'),
(56, 'Laboratorios pfizer ltda.'),
(57, 'Libbs farmacêutica ltda'),
(58, 'Produtos farmacêuticos millet roux'),
(59, 'Allergan produtos farmacêuticos ltda'),
(60, 'Casula & vasconcelos indústria farmacêutica e comércio ltda me'),
(61, 'Cazi quimica farmaceutica industria e comercio ltda'),
(62, 'Zambon laboratórios farmacêuticos ltda.'),
(63, 'Glaxosmithkline brasil ltda'),
(64, 'Uci - farma indústria farmacêutica ltda'),
(65, 'Glenmark farmacêutica ltda'),
(66, 'Blau farmacêutica s.a.'),
(67, 'Brainfarma indústria química e farmacêutica s.a'),
(68, 'Takeda pharma ltda.'),
(69, 'Aspen pharma indústria farmacêutica ltda'),
(70, 'Pharlab indústria farmacêutica s.a.'),
(71, 'Indústria farmacêutica melcon do brasil s.a.'),
(72, 'Laboratório farmacêutico da marinha'),
(73, 'Cifarma científica farmacêutica ltda'),
(74, 'Luper indústria farmacêutica ltda'),
(75, 'Cristália produtos químicos farmacêuticos ltda.'),
(76, 'Novafarma indústria farmacêutica ltda'),
(77, 'Produtos roche químicos e farmacêuticos s.a.'),
(78, 'Galderma brasil ltda'),
(79, 'Farmoquímica s/a'),
(80, 'Hipolabor farmaceutica ltda'),
(81, 'Laboratórios servier do brasil ltda'),
(82, 'Claris produtos farmacêuticos do brasil ltda'),
(83, 'Fresenius kabi brasil ltda'),
(84, 'Inpharma laboratorios ltda'),
(85, 'Royton química farmacêutica ltda'),
(86, 'Grifols brasil ltda'),
(87, 'Csl behring comércio de produtos farmacêuticos ltda'),
(88, 'Octapharma brasil ltda'),
(89, 'Lfb - hemoderivados e biotecnologia ltda'),
(90, 'Biotest farmacêutica ltda'),
(91, 'Baxter hospitalar ltda'),
(92, 'Biolab sanus farmacêutica ltda'),
(93, 'Wyeth indústria farmacêutica ltda'),
(94, 'Instituto terapeutico delta ltda'),
(95, 'Marjan indústria e comércio ltda'),
(96, 'Genzyme do brasil ltda'),
(97, 'Ucb biopharma s.a.'),
(98, 'Panamerican medical supply suprimentos médicos ltda'),
(99, 'Amgen biotecnologia do brasil ltda.'),
(100, 'Fundação oswaldo cruz'),
(101, 'Novo nordisk farmacêutica do brasil ltda'),
(102, 'Chron epigen indústria e comércio ltda'),
(103, 'Schering-plough produtos farmacêuticos ltda'),
(104, 'Chiesi farmacêutica ltda'),
(105, 'Laboratórios bagó do brasil s.a.'),
(106, 'Torrent do brasil ltda'),
(107, 'Zydus nikkho farmacêutica ltda'),
(108, 'Cosmed industria de cosmeticos e medicamentos s.a.'),
(109, 'Opem representação importadora exportadora e distribuidora ltda'),
(110, 'Boehringer ingelheim do brasil química e farmacêutica ltda.'),
(111, 'Brasterapica indústria farmacêutica ltda'),
(112, 'Laboratórios baldacci ltda'),
(113, 'Halex istar indústria farmacêutica sa'),
(114, 'Farmace indústria químico-farmacêutica cearense ltda'),
(115, 'Hypofarma - instituto de hypodermia e farmácia ltda'),
(116, 'Unichem farmacêutica do brasil ltda'),
(117, 'Aurobindo pharma indústria farmacêutica limitada'),
(118, 'Onefarma industria farmaceutica ltda'),
(119, 'Momenta farmacêutica ltda.'),
(120, 'Mylan laboratorios ltda'),
(121, 'Hypermarcas s/a'),
(122, 'Indústria química do estado de goiás s/a - iquego'),
(123, 'Laboratórios libra do brasil ltda'),
(124, 'Instituto butantan'),
(125, 'United medical ltda'),
(126, 'Pharmascience laboratórios ltda'),
(127, 'Accord farmacêutica ltda'),
(128, 'Supera farma laboratórios s.a'),
(129, 'Neolatina comércio e indústria farmaceutica s.a'),
(130, 'Antibióticos do brasil ltda'),
(131, 'Medquimica industria farmaceutica ltda.'),
(132, 'Althaia s.a indústria farmacêutica'),
(133, 'Instituto biochimico indústria farmacêutica ltda'),
(134, 'Theodoro f sobral & cia ltda'),
(135, 'Mantecorp indústria química e farmacêutica s.a.'),
(136, 'Laboratorio sinterapico industrial farmaceutico ltda'),
(137, 'Laboratório farmacêutico do estado de pernambuco - lafepe'),
(138, 'Airela indústria farmacêutica ltda.'),
(139, 'Indústria farmacêutica santa terezinha ltda - epp'),
(140, 'Comando do exército'),
(141, 'Quimifar ltda.'),
(142, 'Dfl indústria e comércio s/a'),
(143, 'Laboris farmaceutica ltda'),
(144, 'Diffucap - chemobrás química e farmacêutica ltda'),
(145, 'Actavis farmaceutica ltda.'),
(146, 'Avert laboratórios ltda'),
(147, 'Biogen brasil produtos farmacêuticos ltda'),
(148, 'Fresenius medical care ltda'),
(149, 'Laboratórios b. braun s/a'),
(150, 'Farmarin industria e comercio ltda'),
(151, 'Samtec biotecnologia limitada'),
(152, 'Salbego laboratório farmacêutico ltda'),
(153, 'Dr. reddys farmacêutica do brasil ltda'),
(154, 'Actelion pharmaceuticals do brasil ltda'),
(155, 'Valeant farmacêutica do brasil ltda'),
(156, 'Lundbeck brasil ltda'),
(157, 'Wasser farma ltda'),
(158, 'Mariol industrial ltda'),
(159, 'Nativita ind. com. ltda.'),
(160, 'Laboratórios pierre fabre do brasil ltda'),
(161, 'Leo pharma ltda'),
(162, 'Balm-labor indústria farmacêutica ltda'),
(163, 'Jarrell farmacêutica ltda epp'),
(164, 'Laboratorio quimico farmaceutico da aeronautica'),
(165, 'Laboratorio industrial farmaceutico de alagoas s.a'),
(166, 'Fundação ezequiel dias - funed'),
(167, 'Ophthalmos s/a'),
(168, 'Laboratório farmacêutico vitamed ltda'),
(169, 'Natulab laboratório s.a'),
(170, 'Nunesfarma distribuidora de produtos farmacêuticos ltda'),
(171, 'Hospira produtos hospitalares ltda'),
(172, 'Bl indústria otica ltda'),
(173, 'Isofarma industrial farmacêutica ltda'),
(174, 'Althaia s.a. indústria farmacêutica.'),
(175, 'Laboratório hepacholan sa'),
(176, 'Laboratorio industrial farmacêutico lifar ltda'),
(177, 'Laboratório farmacêutico caresse ltda me'),
(178, 'Beker produtos fármaco hospitalares ltda'),
(179, 'Equiplex indústria farmacêutica ltda'),
(180, 'Laboratório daudt oliveira ltda'),
(181, 'Laboratorio sanobiol limitada'),
(182, 'Laboratório sanobiol ltda'),
(183, 'Laboratório de extratos alergênicos ltda'),
(184, 'Jp industria farmaceutica s/a'),
(185, 'Indústria farmacêutica texon ltda'),
(186, 'Industria farmaceutica basa ltda'),
(187, 'Vidfarma indústria de medicamentos ltda'),
(188, 'Infan industria quimica farmaceutica nacional s/a'),
(189, 'Evolabis produtos farmacêuticos ltda'),
(190, 'Blisfarma indústria farmacêutica ltda - me'),
(191, 'Blisfarma indústria farmacêutica ltda'),
(192, 'Chemicaltech importação, exportação e comércio de produtos médicos, farmacêuticos e hospitalares ltda'),
(193, 'Dla pharmaceutical ltda'),
(194, 'Santisa laboratório farmacêutico s/a'),
(195, 'Daiichi sankyo brasil farmacêutica ltda'),
(196, 'Blanver farmoquimica ltda'),
(197, 'Astellas farma brasil importação e distribuição de medicamentos ltda.'),
(198, 'Grünenthal do brasil farmacêutica ltda.'),
(199, 'Laboratórios stiefel ltda'),
(200, 'Procter & gamble do brasil s/a'),
(201, 'Trb pharma indústria química e farmacêutica ltda'),
(202, 'Bracco imaging do brasil importacao e distribuicao de medicamentos ltda'),
(203, 'Laboratório gross s. a.'),
(204, 'Johnson & johnson industrial ltda.'),
(205, 'Schering do brasil química e farmacêutica ltda'),
(206, 'Laboratório brasileiro de biologia ltda'),
(207, 'Besins healthcare brasil comercial e distribuidora de medicamentos ltda'),
(208, 'Empresa brasileira de hemoderivados e biotecnologia'),
(209, 'Reckitt benckiser (brasil) ltda'),
(210, 'Blanver farmoquímica ltda.'),
(211, 'Ge healthcare do brasil comércio e serviços para equipamentos medico-hospitalares ltda'),
(212, 'Alko do brasil industria e comercio ltda'),
(213, 'Mallinckrodt do brasil ltda'),
(214, 'Biomarin brasil farmacêutica ltda'),
(215, 'Instituto vital brazil s/a'),
(216, 'Celltrion healthcare distribuicao de produtos farmaceuticos dos brasil ltda'),
(217, 'Guerbet produtos radiológicos ltda'),
(218, 'Laboratorio simoes ltda.'),
(219, 'Geyer medicamentos s/a'),
(220, 'Colbrás indústria e comércio ltda'),
(221, 'Eisai laboratórios ltda'),
(222, 'Meda pharma importação e exportação de produtos farmacêuticos ltda.'),
(223, 'Universidade federal do rio grande do norte'),
(224, 'Laboratorio catarinense ltda'),
(225, 'Farmacia e laboratorio homeopatico almeida prado ltda'),
(226, 'Quimica farmaceutica nikkho do brasil ltda'),
(227, 'Hisamitsu farmacêutica do brasil ltda'),
(228, 'Gilead sciences farmaceutica do brasil ltda'),
(229, 'Dismédica distribuidora de produtos hospitalares e farmacêuticos ltda'),
(230, 'Silvestre labs química e farmacêutica ltda'),
(231, 'Vic pharma industria e comercio ltda'),
(232, 'Quimica haller ltda'),
(233, 'Fundação ataulpho de paiva'),
(234, 'Imec - indústria de medicamentos custódia ltda'),
(235, 'Laboratório saúde ltda'),
(236, 'Droxter industria, comércio e participações ltda'),
(237, 'Tommasi importação e exportação ltda'),
(238, 'Johnson  & johnson do brasil indústria e comércio de produtos para saúde ltda'),
(239, 'Minâncora & cia ltda'),
(240, 'Casa granado laboratórios, farmácias e drogarias s/a'),
(241, 'Dentsply ind.com. ltda');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `laboratorio`
--
ALTER TABLE `laboratorio`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `laboratorio`
--
ALTER TABLE `laboratorio`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=242;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
