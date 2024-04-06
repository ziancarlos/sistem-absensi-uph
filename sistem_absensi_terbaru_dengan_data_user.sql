-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 06, 2024 at 06:21 PM
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
-- Database: `sistem_absensi`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendances`
--

CREATE TABLE `attendances` (
  `StudentId` varchar(13) NOT NULL,
  `ScheduleId` int(11) NOT NULL,
  `FaceTimeIn` datetime DEFAULT NULL,
  `FingerprintTimeIn` datetime DEFAULT NULL,
  `CardTimeIn` datetime DEFAULT NULL,
  `Status` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `buildings`
--

CREATE TABLE `buildings` (
  `BuildingId` int(11) NOT NULL,
  `Letter` varchar(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `buildings`
--

INSERT INTO `buildings` (`BuildingId`, `Letter`) VALUES
(1, 'A'),
(2, 'B'),
(3, 'C'),
(4, 'D'),
(5, 'E'),
(6, 'F');

-- --------------------------------------------------------

--
-- Table structure for table `classrooms`
--

CREATE TABLE `classrooms` (
  `ClassroomId` int(11) NOT NULL,
  `Capacity` int(3) NOT NULL,
  `Code` int(5) NOT NULL,
  `BuildingId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `classrooms`
--

INSERT INTO `classrooms` (`ClassroomId`, `Capacity`, `Code`, `BuildingId`) VALUES
(1, 20, 302, 2),
(2, 10, 310, 2),
(3, 12, 354, 2),
(4, 14, 356, 2);

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `CourseId` int(11) NOT NULL,
  `Name` varchar(45) NOT NULL,
  `Code` varchar(5) NOT NULL,
  `ClassroomId` int(11) NOT NULL,
  `Status` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `enrollments`
--

CREATE TABLE `enrollments` (
  `EnrollmentId` int(11) NOT NULL,
  `StudentId` varchar(13) NOT NULL,
  `CourseId` int(11) NOT NULL,
  `Status` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lecturerhascourses`
--

CREATE TABLE `lecturerhascourses` (
  `LecturerId` int(11) NOT NULL,
  `CourseId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

CREATE TABLE `schedules` (
  `ScheduleId` int(11) NOT NULL,
  `CourseId` int(11) NOT NULL,
  `Date` date NOT NULL,
  `StartTime` time DEFAULT NULL,
  `EndTime` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `StudentId` varchar(13) NOT NULL,
  `YearIn` year(4) NOT NULL,
  `Face` varchar(255) DEFAULT NULL,
  `Fingerprint` varchar(255) DEFAULT NULL,
  `Card` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`StudentId`, `YearIn`, `Face`, `Fingerprint`, `Card`) VALUES
('01081210001', '2021', NULL, NULL, NULL),
('01081210003', '2021', NULL, NULL, NULL),
('01081210007', '2021', NULL, NULL, NULL),
('01081210011', '2021', NULL, NULL, NULL),
('01081210012', '2021', NULL, NULL, NULL),
('01081210013', '2021', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserId` int(11) NOT NULL,
  `StudentId` varchar(13) DEFAULT NULL,
  `Name` varchar(100) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Password` varchar(32) NOT NULL,
  `Role` tinyint(1) NOT NULL DEFAULT 0,
  `Status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserId`, `StudentId`, `Name`, `Email`, `Password`, `Role`, `Status`) VALUES
(1, NULL, 'admin', 'admin@gmail.com', '202cb962ac59075b964b07152d234b70', 2, 1),
(2, NULL, 'Kusno Prasetya', 'kusnoprasetya@gmail.com', '25d55ad283aa400af464c76d713c07ad', 1, 1),
(3, NULL, 'Calandra Alecia', 'calandraalecia@gmail.com', '25d55ad283aa400af464c76d713c07ad', 1, 1),
(4, NULL, 'Arnold Ariwibowo', 'arnold.ariwibowo@gmail.com', '25d55ad283aa400af464c76d713c07ad', 1, 1),
(5, NULL, 'Aditya', 'aditya@gmail.com', '25d55ad283aa400af464c76d713c07ad', 1, 1),
(6, '01081210013', 'ZIAN CARLOS WONG', '01081210013@student.uph.edu', '25d55ad283aa400af464c76d713c07ad', 0, 1),
(7, '01081210001', 'YOANA SONIA WIJAYA', '01081210001@student.uph.edu', '25d55ad283aa400af464c76d713c07ad', 0, 1),
(8, '01081210011', 'IMMANUEL CHRISTIAN HIRANI', '01081210011@student.uph.edu', '25d55ad283aa400af464c76d713c07ad', 0, 1),
(9, '01081210003', 'RAFAELL VICTOR CHRISTIAN', '01081210003@student.uph.edu', '25d55ad283aa400af464c76d713c07ad', 0, 1),
(10, '01081210007', 'NATHANIA MICHAELA LISANDI', '01081210007@student.uph.edu', '25d55ad283aa400af464c76d713c07ad', 0, 1),
(11, '01081210012', 'KELVIN KELVIN', '01081210012@student.uph.edu', '25d55ad283aa400af464c76d713c07ad', 0, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendances`
--
ALTER TABLE `attendances`
  ADD PRIMARY KEY (`StudentId`,`ScheduleId`),
  ADD KEY `ScheduleId` (`ScheduleId`);

--
-- Indexes for table `buildings`
--
ALTER TABLE `buildings`
  ADD PRIMARY KEY (`BuildingId`),
  ADD UNIQUE KEY `Letter` (`Letter`);

--
-- Indexes for table `classrooms`
--
ALTER TABLE `classrooms`
  ADD PRIMARY KEY (`ClassroomId`),
  ADD KEY `fk_Classrooms_Building` (`BuildingId`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`CourseId`),
  ADD UNIQUE KEY `CourseId` (`CourseId`),
  ADD KEY `courses_ibfk_1` (`ClassroomId`);

--
-- Indexes for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD PRIMARY KEY (`EnrollmentId`),
  ADD KEY `enrollments_ibfk_1` (`StudentId`),
  ADD KEY `enrollments_ibfk_2` (`CourseId`);

--
-- Indexes for table `lecturerhascourses`
--
ALTER TABLE `lecturerhascourses`
  ADD PRIMARY KEY (`LecturerId`,`CourseId`),
  ADD KEY `CourseId` (`CourseId`);

--
-- Indexes for table `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`ScheduleId`),
  ADD UNIQUE KEY `ScheduleId` (`ScheduleId`),
  ADD KEY `CourseId` (`CourseId`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`StudentId`),
  ADD UNIQUE KEY `Face` (`Face`),
  ADD UNIQUE KEY `Fingerprint` (`Fingerprint`),
  ADD UNIQUE KEY `Card` (`Card`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserId`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD KEY `StudentId` (`StudentId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `buildings`
--
ALTER TABLE `buildings`
  MODIFY `BuildingId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `classrooms`
--
ALTER TABLE `classrooms`
  MODIFY `ClassroomId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `CourseId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `EnrollmentId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `ScheduleId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendances`
--
ALTER TABLE `attendances`
  ADD CONSTRAINT `attendances_ibfk_1` FOREIGN KEY (`StudentId`) REFERENCES `students` (`StudentId`),
  ADD CONSTRAINT `attendances_ibfk_2` FOREIGN KEY (`ScheduleId`) REFERENCES `schedules` (`ScheduleId`);

--
-- Constraints for table `classrooms`
--
ALTER TABLE `classrooms`
  ADD CONSTRAINT `fk_Classrooms_Building` FOREIGN KEY (`BuildingId`) REFERENCES `buildings` (`BuildingId`);

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`ClassroomId`) REFERENCES `classrooms` (`ClassroomId`);

--
-- Constraints for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD CONSTRAINT `enrollments_ibfk_1` FOREIGN KEY (`StudentId`) REFERENCES `students` (`StudentId`),
  ADD CONSTRAINT `enrollments_ibfk_2` FOREIGN KEY (`CourseId`) REFERENCES `courses` (`CourseId`),
  ADD CONSTRAINT `fk_student_id` FOREIGN KEY (`StudentId`) REFERENCES `students` (`StudentId`);

--
-- Constraints for table `lecturerhascourses`
--
ALTER TABLE `lecturerhascourses`
  ADD CONSTRAINT `lecturerhascourses_ibfk_1` FOREIGN KEY (`LecturerId`) REFERENCES `users` (`UserId`),
  ADD CONSTRAINT `lecturerhascourses_ibfk_2` FOREIGN KEY (`CourseId`) REFERENCES `courses` (`CourseId`);

--
-- Constraints for table `schedules`
--
ALTER TABLE `schedules`
  ADD CONSTRAINT `schedules_ibfk_1` FOREIGN KEY (`CourseId`) REFERENCES `courses` (`CourseId`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`StudentId`) REFERENCES `students` (`StudentId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
