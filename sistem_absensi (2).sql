-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 26, 2024 at 03:42 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

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

--
-- Dumping data for table `attendances`
--

INSERT INTO `attendances` (`StudentId`, `ScheduleId`, `FaceTimeIn`, `FingerprintTimeIn`, `CardTimeIn`, `Status`) VALUES
('1000000004', 1, '2024-03-15 15:52:14', '2024-03-15 15:52:14', '2024-03-15 15:52:14', 1);

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
(6, 'F'),
(7, 'G'),
(8, 'H'),
(9, 'I'),
(10, 'J'),
(11, 'K');

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
(1, 10, 402, 2),
(2, 12, 301, 2),
(3, 60, 342, 2);

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `CourseId` int(11) NOT NULL,
  `Name` varchar(45) NOT NULL,
  `Code` varchar(5) NOT NULL,
  `ClassroomId` int(11) NOT NULL,
  `StartDate` date DEFAULT NULL,
  `EndDate` date DEFAULT NULL,
  `Status` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`CourseId`, `Name`, `Code`, `ClassroomId`, `StartDate`, `EndDate`, `Status`) VALUES
(1, 'Struktur Data Data', 'SYS1', 1, '2024-03-13', '2024-03-14', 1),
(2, 'Sistem Basis Data', 'SYS2', 2, '2024-03-15', '2024-03-16', 1),
(3, 'Teknologi Web', 'SYS3', 1, '2024-03-05', '2024-03-27', 1),
(4, 'Struktur Data', 'SYS 3', 1, NULL, NULL, 1),
(5, 'strukdat', 'sys2', 3, NULL, NULL, 1);

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

--
-- Dumping data for table `lecturerhascourses`
--

INSERT INTO `lecturerhascourses` (`LecturerId`, `CourseId`) VALUES
(1000000002, 1),
(1000000002, 3),
(1000000003, 1),
(1000000003, 2),
(1000000005, 1);

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

CREATE TABLE `schedules` (
  `ScheduleId` int(11) NOT NULL,
  `CourseId` int(11) NOT NULL,
  `DateTime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schedules`
--

INSERT INTO `schedules` (`ScheduleId`, `CourseId`, `DateTime`) VALUES
(1, 1, '2024-03-15 09:52:00'),
(2, 4, '2024-03-20 15:15:00'),
(3, 1, '2024-03-25 15:22:00'),
(4, 1, '2024-04-02 15:23:00'),
(5, 1, '2024-03-20 15:29:00'),
(6, 1, '2024-03-22 15:31:00'),
(7, 1, '2024-03-21 15:32:00'),
(8, 1, '2024-03-21 15:33:00');

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
('01081210001', 2021, NULL, NULL, NULL),
('01081210007', 2021, NULL, NULL, NULL),
('01081210012', 2021, NULL, NULL, NULL),
('01081210013', 2021, NULL, NULL, NULL),
('1000000004', 2021, 'face', 'fingerprint', 'card');

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
(1000000001, NULL, 'admin', 'admin@uph.edu', '25f9e794323b453885f5181f1b624d0b', 2, 1),
(1000000002, NULL, 'kusno prasetya', 'kusno@gmail.com', '202cb962ac59075b964b07152d234b70', 1, 1),
(1000000003, NULL, 'arnold aribowoo', 'arnold@gmail.com', '25f9e794323b453885f5181f1b624d0b', 1, 1),
(1000000004, NULL, 'mahasiswa', 'mhs@uph.edu', '25f9e794323b453885f5181f1b624d0b', 0, 1),
(1000000005, NULL, 'calandra', 'calandra@uph.edu', '25f9e794323b453885f5181f1b624d0b', 1, 1),
(1000000006, '01081210012', 'Kelvin', '01081210012@uph.edu', '25f9e794323b453885f5181f1b624d0b', 0, 1),
(1000000007, '01081210007', 'Nathania Michaela Lisandi', '01081210007@student.uph.edu', '25f9e794323b453885f5181f1b624d0b', 0, 1),
(1000000008, '01081210001', 'Yoana Sonia Wijaya', '01081210001@student.uph.edu', '25f9e794323b453885f5181f1b624d0b', 0, 1),
(1000000009, '01081210013', 'Zian Carlos Wong', '01081210013@student.uph.edu', '25f9e794323b453885f5181f1b624d0b', 0, 1);

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
  MODIFY `BuildingId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `classrooms`
--
ALTER TABLE `classrooms`
  MODIFY `ClassroomId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `CourseId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `EnrollmentId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `ScheduleId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1000000010;

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
