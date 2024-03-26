<?php
session_start();
require_once ("../../helper/dbHelper.php");
require_once ("../../helper/authHelper.php");
$permittedRole = ["lecturer", "admin", "student"];
$pageName = "Sistem Absensi UPH - Data Mata Kuliah";
$data = [];
if (!authorization($permittedRole, $_SESSION["UserId"])) {
    header('location: ../auth/logout.php');
}

dataCourseView();

function dataCourseView()
{
    global $data;
    $userId = $_SESSION["UserId"];
    $connection = getConnection();

    // Check user role
    $userRole = getUserRole($userId);

    if ($userRole === "admin") {
        // If user is an admin, retrieve all courses
        try {
            $stmt = $connection->prepare("SELECT DISTINCT
            courses.CourseId, 
            courses.Name, 
            courses.Code, 
            courses.StartDate, 
            courses.EndDate, 
            CONCAT(buildings.Letter, classrooms.Code) AS Room, 
            courses.Status AS CoursesStatus,
            CASE WHEN schedules.CourseId IS NOT NULL THEN 1 ELSE 0 END AS SchedulingStatus
        FROM 
            courses
        INNER JOIN 
            classrooms ON courses.ClassroomId = classrooms.ClassroomId
        INNER JOIN 
            buildings ON classrooms.BuildingId = buildings.BuildingId
        LEFT JOIN 
            schedules ON courses.CourseId = schedules.CourseId
        
        
            ");
            $stmt->execute();
            $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Populate data array with course information
            $data['courses'] = $courses;
        } catch (Exception $e) {
            $_SESSION["error"] = $e->getMessage();
            header("location: dataStudent.php");
            exit;
        }
    } elseif ($userRole === "lecturer") {
        // If user is a lecturer, retrieve courses taught by the lecturer
        try {
            $stmt = $connection->prepare("
                SELECT courses.CourseId, courses.Name, courses.Code, courses.StartDate, 
                       courses.EndDate, CONCAT(buildings.Letter, classrooms.Code) AS Room, 
                       courses.Status AS CoursesStatus
                FROM courses
                INNER JOIN classrooms ON courses.ClassroomId = classrooms.ClassroomId
                INNER JOIN buildings ON classrooms.BuildingId = buildings.BuildingId
                INNER JOIN lecturerhascourses ON courses.CourseId = lecturerhascourses.CourseId
                WHERE lecturerhascourses.LecturerId = ?
            ");
            $stmt->execute([$userId]);
            $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Populate data array with course information
            $data['courses'] = $courses;
        } catch (Exception $e) {
            $_SESSION["error"] = $e->getMessage();
            header("location: dataStudent.php");
            exit;
        }
    } elseif ($userRole === "student") {
        // If user is a student, retrieve courses enrolled by the student
        try {
            $stmt = $connection->prepare("
                SELECT courses.CourseId, courses.Name, courses.Code, courses.StartDate, 
                       courses.EndDate, CONCAT(buildings.Letter, classrooms.Code) AS Room, 
                       enrollments.Status AS EnrollmentStatus
                FROM courses
                INNER JOIN classrooms ON courses.ClassroomId = classrooms.ClassroomId
                INNER JOIN buildings ON classrooms.BuildingId = buildings.BuildingId
                INNER JOIN enrollments ON courses.CourseId = enrollments.CourseId
                INNER JOIN users ON enrollments.StudentId = users.StudentId
                WHERE users.UserId = ?
            ");
            $stmt->execute([$userId]);
            $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Populate data array with course information
            $data['courses'] = $courses;
        } catch (Exception $e) {
            $_SESSION["error"] = $e->getMessage();
            header("location: dataStudent.php");
            exit;
        }
    }
}
?>
