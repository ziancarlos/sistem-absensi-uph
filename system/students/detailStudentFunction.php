<?php
session_start();
require_once("../../helper/dbHelper.php");
require_once("../../helper/authHelper.php");
$permittedRole = ["lecturer", "admin"];
$pageName = "Sistem Absensi UPH - Detil Mahasiswa";
$data = [];
if (!authorization($permittedRole, $_SESSION["UserId"])) {
    header('location: ../auth/logout.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET["UserId"])) {
    detailStudentView();
} else {
    $_SESSION["error"] = "Tidak menemukan permintaan yang valid!";
    header("location: dataStudent.php");
    exit;
}

function detailStudentView()
{
    global $data;

    $studentId = $_GET["UserId"];
    $connection = getConnection();

    // Check if UserId is set and numeric
    if (!isset($studentId) || !is_numeric($studentId)) {
        $_SESSION["error"] = "Tidak menemukan permintaan yang valid!";
        header("location: dataStudent.php");
        exit;
    }

    if (getUserRole($_SESSION["UserId"]) === "admin") {
        try {
            // Prepare and execute query to check if the user exists
            $stmt = $connection->prepare("SELECT * FROM users WHERE UserId = ?");
            $stmt->execute([$studentId]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // If user does not exist, redirect to dataStudent.php
            if (!$user) {
                throw new Exception("Pengguna tidak ditemukan!");
            }

            // Proceed with other actions if needed
            $stmt = $connection->prepare("
        SELECT enrollments.EnrollmentId,  courses.Name, courses.Code AS Code,courses.StartDate, 
               courses.EndDate, CONCAT(buildings.Letter, classrooms.Code) AS Room, enrollments.Status AS EnrollmentStatus, courses.Status AS CoursesStatus
        FROM courses
        INNER JOIN classrooms ON courses.ClassroomId = classrooms.ClassroomId
        INNER JOIN buildings ON classrooms.BuildingId = buildings.BuildingId
        INNER JOIN enrollments ON courses.CourseId = enrollments.CourseId
        INNER JOIN users ON enrollments.StudentId = users.StudentId
        WHERE users.UserId = ?
    ");
            $stmt->execute([$studentId]);
            $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Populate data array with course information
            $data['courses'] = $courses;
        } catch (Exception $e) {
            $_SESSION["error"] = $e->getMessage();
            header("location: dataStudent.php");
            exit;
        }
    }
    if (getUserRole($_SESSION["UserId"]) === "lecturer") {
        try {
            // Prepare and execute query to check if the user exists
            $stmt = $connection->prepare("SELECT * FROM users WHERE UserId = ?");
            $stmt->execute([$studentId]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // If user does not exist, redirect to dataStudent.php
            if (!$user) {
                throw new Exception("Pengguna tidak ditemukan!");
            }

            // Proceed with other actions if needed
            $stmt = $connection->prepare("
        SELECT enrollments.EnrollmentId,  courses.Name, courses.Code AS Code,courses.StartDate, 
               courses.EndDate, CONCAT(buildings.Letter, classrooms.Code) AS Room, enrollments.Status AS EnrollmentStatus, courses.Status AS CoursesStatus
        FROM courses
        INNER JOIN classrooms ON courses.ClassroomId = classrooms.ClassroomId
        INNER JOIN buildings ON classrooms.BuildingId = buildings.BuildingId
        INNER JOIN enrollments ON courses.CourseId = enrollments.CourseId
        INNER JOIN users ON enrollments.StudentId = users.StudentId
        INNER JOIN lecturerhascourses ON courses.CourseId = lecturerhascourses.CourseId
        WHERE users.UserId = ? AND lecturerhascourses.LecturerId = ? ");

            $stmt->execute([$studentId, $_SESSION["UserId"]]);
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