-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 20, 2023 at 09:55 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `Data_Teacher`
--

-- --------------------------------------------------------

--
-- Table structure for table `CourseAverages`
--

CREATE TABLE `CourseAverages` (
  `CourseID` int(11) NOT NULL,
  `AVG_GPA` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `CourseAverages`
--

INSERT INTO `CourseAverages` (`CourseID`, `AVG_GPA`) VALUES
(115, 79.1667),
(116, 63),
(117, 70.4),
(118, 71.4),
(121, 50);

-- --------------------------------------------------------

--
-- Table structure for table `Courses`
--

CREATE TABLE `Courses` (
  `CourseID` int(11) NOT NULL,
  `CourseName` varchar(50) DEFAULT NULL,
  `CourseCredits` int(11) DEFAULT NULL,
  `TeacherID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Courses`
--

INSERT INTO `Courses` (`CourseID`, `CourseName`, `CourseCredits`, `TeacherID`) VALUES
(115, 'Web Dev', 20, 6),
(116, 'Data Structures', 15, 6),
(117, 'Networks', 20, 6),
(118, 'Mathematics', 15, 6),
(121, 'Database', 5, 6);

-- --------------------------------------------------------

--
-- Table structure for table `Scores`
--

CREATE TABLE `Scores` (
  `ScoreID` int(11) NOT NULL,
  `StudentID` int(11) DEFAULT NULL,
  `CourseID` int(11) DEFAULT NULL,
  `Score` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Scores`
--

INSERT INTO `Scores` (`ScoreID`, `StudentID`, `CourseID`, `Score`) VALUES
(119, 23, 116, 69),
(120, 23, 117, 100),
(121, 24, 115, 100),
(122, 24, 116, 75),
(123, 24, 117, 85),
(124, 24, 118, 95),
(125, 25, 115, 75),
(126, 25, 116, 90),
(127, 25, 117, 95),
(128, 25, 118, 98),
(129, 26, 115, 85),
(130, 26, 116, 75),
(131, 26, 117, 98),
(132, 26, 118, 90),
(137, 23, 115, 75),
(138, 23, 118, 95),
(140, 30, 116, 100),
(141, 30, 117, 52),
(142, 30, 118, 54),
(143, 30, 115, 100),
(144, 23, 121, 50),
(145, 24, 121, 50),
(146, 25, 121, 50),
(147, 26, 121, 50),
(148, 32, 115, 10);

-- --------------------------------------------------------

--
-- Table structure for table `StudentAverages`
--

CREATE TABLE `StudentAverages` (
  `StudentID` int(11) NOT NULL,
  `CourseID` int(11) NOT NULL,
  `GPA` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `StudentAverages`
--

INSERT INTO `StudentAverages` (`StudentID`, `CourseID`, `GPA`) VALUES
(23, 115, 100),
(23, 116, 54),
(23, 117, 10),
(23, 118, 12),
(23, 121, 50),
(24, 115, 100),
(24, 116, 68),
(24, 117, 95),
(24, 118, 92),
(24, 121, 50),
(25, 115, 65),
(25, 116, 88),
(25, 117, 95),
(25, 118, 99),
(25, 121, 50),
(26, 115, 100),
(26, 116, 55),
(26, 117, 100),
(26, 118, 100),
(26, 121, 50),
(30, 115, 100),
(30, 116, 50),
(30, 117, 52),
(30, 118, 54),
(30, 121, NULL),
(32, 115, 10),
(32, 116, NULL),
(32, 117, NULL),
(32, 118, NULL),
(32, 121, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `StudentCourses`
--

CREATE TABLE `StudentCourses` (
  `ScoreID` int(11) NOT NULL,
  `StudentID` int(11) DEFAULT NULL,
  `TeacherID` int(11) DEFAULT NULL,
  `CourseID` int(11) DEFAULT NULL,
  `Score` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `StudentCourses`
--

INSERT INTO `StudentCourses` (`ScoreID`, `StudentID`, `TeacherID`, `CourseID`, `Score`) VALUES
(29, 23, 6, 115, 100),
(30, 23, 6, 116, 54),
(31, 23, 6, 118, 12),
(32, 23, 6, 117, 10),
(33, 24, 6, 115, 100),
(34, 24, 6, 116, 68),
(35, 24, 6, 117, 95),
(36, 24, 6, 118, 92),
(37, 25, 6, 115, 65),
(38, 25, 6, 116, 88),
(39, 25, 6, 117, 95),
(40, 25, 6, 118, 99),
(41, 26, 6, 115, 100),
(42, 26, 6, 116, 55),
(43, 26, 6, 117, 100),
(44, 26, 6, 118, 100),
(50, 30, 6, 116, 50),
(51, 30, 6, 117, 52),
(52, 30, 6, 118, 54),
(53, 30, 6, 115, 100),
(54, 23, 6, 121, 50),
(55, 24, 6, 121, 50),
(56, 25, 6, 121, 50),
(57, 26, 6, 121, 50),
(58, 32, 6, 115, 10);

-- --------------------------------------------------------

--
-- Table structure for table `Students`
--

CREATE TABLE `Students` (
  `StudentID` int(11) NOT NULL,
  `FirstName` varchar(50) DEFAULT NULL,
  `LastName` varchar(50) DEFAULT NULL,
  `DateOfBirth` date DEFAULT NULL,
  `TeacherID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Students`
--

INSERT INTO `Students` (`StudentID`, `FirstName`, `LastName`, `DateOfBirth`, `TeacherID`) VALUES
(23, 'Antony', 'H', '1993-02-12', 6),
(24, 'Eirini', 'P', '1992-12-05', 6),
(25, 'Miltos', 'P', '1993-05-12', 6),
(26, 'Bruno', 'T', '1993-04-01', 6),
(30, 'mailre', 'Test2', '2023-10-29', 6),
(32, 'Pakhs', 'C', '1980-12-15', 6);

-- --------------------------------------------------------

--
-- Table structure for table `Teachers`
--

CREATE TABLE `Teachers` (
  `TeacherID` int(11) NOT NULL,
  `FirstName` varchar(50) DEFAULT NULL,
  `LastName` varchar(50) DEFAULT NULL,
  `Username` varchar(50) DEFAULT NULL,
  `Password` varchar(255) DEFAULT NULL,
  `Email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Teachers`
--

INSERT INTO `Teachers` (`TeacherID`, `FirstName`, `LastName`, `Username`, `Password`, `Email`) VALUES
(1, 'John', 'Smith', 'jsmith1234', 'password1', NULL),
(2, 'Sarah', 'Johnson', 'sjohnson5678', 'password2', NULL),
(3, 'Michael', 'Brown', 'mbrown8765', 'password3', NULL),
(6, 'Antonis', 'Chalkias', 'Revinianz13', '$2y$10$miSImgKco8PkbWi9Tt2NhuB6jzYGROXsnVnoRoAC12l24c/3c3eNG', 'ant.f.chlks@gmail.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `CourseAverages`
--
ALTER TABLE `CourseAverages`
  ADD PRIMARY KEY (`CourseID`);

--
-- Indexes for table `Courses`
--
ALTER TABLE `Courses`
  ADD PRIMARY KEY (`CourseID`);

--
-- Indexes for table `Scores`
--
ALTER TABLE `Scores`
  ADD PRIMARY KEY (`ScoreID`),
  ADD KEY `StudentID` (`StudentID`),
  ADD KEY `Scores_ibfk_2` (`CourseID`);

--
-- Indexes for table `StudentAverages`
--
ALTER TABLE `StudentAverages`
  ADD PRIMARY KEY (`StudentID`,`CourseID`),
  ADD UNIQUE KEY `unique_student_course` (`StudentID`,`CourseID`),
  ADD UNIQUE KEY `unique_student_course_gpa` (`StudentID`,`CourseID`),
  ADD KEY `CourseID` (`CourseID`);

--
-- Indexes for table `StudentCourses`
--
ALTER TABLE `StudentCourses`
  ADD PRIMARY KEY (`ScoreID`),
  ADD KEY `StudentID` (`StudentID`),
  ADD KEY `TeacherID` (`TeacherID`),
  ADD KEY `CourseID` (`CourseID`);

--
-- Indexes for table `Students`
--
ALTER TABLE `Students`
  ADD PRIMARY KEY (`StudentID`);

--
-- Indexes for table `Teachers`
--
ALTER TABLE `Teachers`
  ADD PRIMARY KEY (`TeacherID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Courses`
--
ALTER TABLE `Courses`
  MODIFY `CourseID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=122;

--
-- AUTO_INCREMENT for table `Scores`
--
ALTER TABLE `Scores`
  MODIFY `ScoreID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=149;

--
-- AUTO_INCREMENT for table `StudentCourses`
--
ALTER TABLE `StudentCourses`
  MODIFY `ScoreID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `Students`
--
ALTER TABLE `Students`
  MODIFY `StudentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `Teachers`
--
ALTER TABLE `Teachers`
  MODIFY `TeacherID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `CourseAverages`
--
ALTER TABLE `CourseAverages`
  ADD CONSTRAINT `fk_course_averages_course` FOREIGN KEY (`CourseID`) REFERENCES `Courses` (`CourseID`) ON DELETE CASCADE;

--
-- Constraints for table `Scores`
--
ALTER TABLE `Scores`
  ADD CONSTRAINT `fk_scores_student` FOREIGN KEY (`StudentID`) REFERENCES `Students` (`StudentID`) ON DELETE CASCADE;

--
-- Constraints for table `StudentAverages`
--
ALTER TABLE `StudentAverages`
  ADD CONSTRAINT `StudentAverages_ibfk_1` FOREIGN KEY (`StudentID`) REFERENCES `Students` (`StudentID`) ON DELETE CASCADE,
  ADD CONSTRAINT `StudentAverages_ibfk_2` FOREIGN KEY (`CourseID`) REFERENCES `Courses` (`CourseID`) ON DELETE CASCADE;

--
-- Constraints for table `StudentCourses`
--
ALTER TABLE `StudentCourses`
  ADD CONSTRAINT `fk_student_courses_student` FOREIGN KEY (`StudentID`) REFERENCES `Students` (`StudentID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;