-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 27, 2023 at 10:20 AM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ala_next_logistics`
--

-- --------------------------------------------------------

--
-- Table structure for table `projectdata`
--

CREATE TABLE `projectdata` (
  `ID` int(11) NOT NULL,
  `ProjectID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `EntryDT` int(11) NOT NULL,
  `WorkDT` date NOT NULL,
  `Description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `ID` int(11) NOT NULL,
  `Active` bit(1) NOT NULL,
  `Code` int(10) NOT NULL,
  `Actual` bit(1) NOT NULL,
  `Title` varchar(255) NOT NULL,
  `StartDT` date NOT NULL,
  `EndDT` date NOT NULL,
  `MaxHours` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `projectusers`
--

CREATE TABLE `projectusers` (
  `ID` int(11) NOT NULL,
  `ProjectID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `MayManage` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `ID` int(11) NOT NULL,
  `Active` bit(1) NOT NULL,
  `Name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `projectdata`
--
ALTER TABLE `projectdata`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ProjectID` (`ProjectID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `projectusers`
--
ALTER TABLE `projectusers`
  ADD KEY `ProjectID` (`ProjectID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `projectdata`
--
ALTER TABLE `projectdata`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `projectdata`
--
ALTER TABLE `projectdata`
  ADD CONSTRAINT `projectdata_ibfk_1` FOREIGN KEY (`ProjectID`) REFERENCES `projects` (`ID`),
  ADD CONSTRAINT `projectdata_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `users` (`ID`);

--
-- Constraints for table `projectusers`
--
ALTER TABLE `projectusers`
  ADD CONSTRAINT `projectusers_ibfk_1` FOREIGN KEY (`ProjectID`) REFERENCES `projects` (`ID`),
  ADD CONSTRAINT `projectusers_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `users` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
