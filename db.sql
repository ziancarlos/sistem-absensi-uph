CREATE TABLE Students (
    StudentId VARCHAR(13) UNIQUE NOT NULL PRIMARY KEY,
    YearIn YEAR NOT NULL,
    Face VARCHAR(255) UNIQUE,
    Fingerprint VARCHAR(255) UNIQUE,
    Card VARCHAR(255) UNIQUE
);

CREATE TABLE Users (
    UserId INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    StudentId VARCHAR(13),
    Name VARCHAR(100) NOT NULL,
    Email VARCHAR(255) NOT NULL UNIQUE,
    Password VARCHAR(32) NOT NULL,
    Role INT(1) NOT NULL DEFAULT 0,
    Status INT(1) NOT NULL DEFAULT 1,
    FOREIGN KEY (StudentId) REFERENCES Students(StudentId)
);


CREATE TABLE Courses (
    CourseId INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(45) NOT NULL,
    Code VARCHAR(5) NOT NULL,
    Classroom VARCHAR(6) NOT NULL,
    UNIQUE (CourseId)
);

-- Create LecturerHasCourses table
CREATE TABLE LecturerHasCourses (
    LecturerId INT NOT NULL,
    CourseId INT NOT NULL,
    FOREIGN KEY (LecturerId) REFERENCES Users(UserId),
    FOREIGN KEY (CourseId) REFERENCES Courses(CourseId),
    PRIMARY KEY (LecturerId, CourseId)
);

-- Create Schedules table
CREATE TABLE Schedules (
    ScheduleId INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    CourseId INT NOT NULL,
    DateTime DATETIME NOT NULL,
    FOREIGN KEY (CourseId) REFERENCES Courses(CourseId),
    UNIQUE (ScheduleId)
);

-- Create Attendances table
CREATE TABLE Attendances (
    StudentId VARCHAR(13) NOT NULL,
    ScheduleId INT NOT NULL,
    FaceTimeIn DATETIME DEFAULT NULL,
    FingerprintTimeIn DATETIME DEFAULT NULL,
    CardTimeIn DATETIME DEFAULT NULL,
    FOREIGN KEY (StudentId) REFERENCES Students(StudentId),
    FOREIGN KEY (ScheduleId) REFERENCES Schedules(ScheduleId),
    PRIMARY KEY (StudentId, ScheduleId)
);


CREATE TABLE Enrollments (
    StudentId VARCHAR(13) NOT NULL,
    CourseId INT NOT NULL,
    FOREIGN KEY (StudentId) REFERENCES Students(StudentId),
    FOREIGN KEY (CourseId) REFERENCES Courses(CourseId),
    PRIMARY KEY (StudentId, CourseId)
);
