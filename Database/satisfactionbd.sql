-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 18-Ago-2017 às 16:45
-- Versão do servidor: 10.1.25-MariaDB
-- PHP Version: 7.1.7

SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `satisfactionbd`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `customer`
--

CREATE TABLE `customer` (
  `idcustomer` int(10) UNSIGNED NOT NULL,
  `name` varchar(30) NOT NULL,
  `emails` varchar(100) NOT NULL,
  `tecnical_visits` int(11) NOT NULL,
  `forms_answereds` int(11) NOT NULL,
  `V11_ID` int(11) NOT NULL
) ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `employee`
--

CREATE TABLE `employee` (
  `idemployee` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `note_avarage` double NOT NULL,
  `issue_sol_avarage` double NOT NULL,
  `V11_code` int(11) NOT NULL
) ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `form`
--

CREATE TABLE `form` (
  `idform` int(10) UNSIGNED NOT NULL,
  `commentary` varchar(200) DEFAULT NULL,
  `idcustomer` int(11) DEFAULT NULL,
  `idemployee` int(11) DEFAULT NULL,
  `evaluation_value` int(11) DEFAULT NULL,
  `issue_solve` varchar(10) DEFAULT NULL,
  `request_sent` date NOT NULL,
  `request_answered` date NOT NULL
) ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `users`
--

CREATE TABLE `users` (
  `idusers` int(10) UNSIGNED NOT NULL,
  `name` varchar(60) NOT NULL,
  `password` varchar(60) NOT NULL,
  `type` varchar(20) NOT NULL,
  `img_perfil` varchar(300) NOT NULL
) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`idcustomer`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`idemployee`);

--
-- Indexes for table `form`
--
ALTER TABLE `form`
  ADD PRIMARY KEY (`idform`),
  ADD KEY `idemployee` (`idemployee`),
  ADD KEY `idcustomer` (`idcustomer`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`idusers`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `idcustomer` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `idemployee` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `form`
--
ALTER TABLE `form`
  MODIFY `idform` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `idusers` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
