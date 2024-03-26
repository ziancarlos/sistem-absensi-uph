<?php
session_start();
require_once ("../../helper/dbHelper.php");
require_once ("../../helper/authHelper.php");
$permittedRole = ["lecturer", "admin"];
$pageName = "Sistem Absensi UPH - Jadwal Mata Kuliah";
$data = [];
if (!authorization($permittedRole, $_SESSION["UserId"])) {
    header('location: ../auth/login.php');
}


if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset ($_GET["CourseId"])) {
    updateCourseScheduleView();
    $_SESSION["info"] = "Harap mendaftarkan mahasiswa sebelum menjadwalkan mata kuliah. Setelah penjadwalan, pendaftaran mahasiswa tidak dapat dilakukan lagi.";
} else {
    $_SESSION["error"] = "Tidak menemukan permintaan yang valid!";
    header("location: updateCourseSchedule.php");
    exit;
}

function updateCourseScheduleView()
{
    global $data;

    // Check if CourseId is provided in the URL
    if (!isset ($_GET["CourseId"])) {
        $_SESSION["error"] = "CourseId tidak ditemukan.";
        header("location: updateCourseSchedule.php");
        exit;
    }

    // Sanitize and retrieve CourseId
    $courseId = htmlspecialchars($_GET["CourseId"]);

    // Database connection settings
    $connection = getConnection();

    try {
        // Check if the course exists
        $sql_check_course = "SELECT * FROM Courses WHERE CourseId = :courseId";
        $stmt_check_course = $connection->prepare($sql_check_course);
        $stmt_check_course->bindParam(':courseId', $courseId);
        $stmt_check_course->execute();
        $course = $stmt_check_course->fetch(PDO::FETCH_ASSOC);

        // If the course does not exist, redirect with an error message
        if (!$course) {
            $_SESSION["error"] = "Mata kuliah dengan CourseId $courseId tidak ditemukan.";
            header("location: updateCourseSchedule.php");
            exit;
        }

        // SQL query to retrieve course schedules by CourseId
        $sql_schedules = "SELECT * FROM schedules WHERE CourseId = :courseId";

        // Prepare and execute the query
        $stmt_schedules = $connection->prepare($sql_schedules);
        $stmt_schedules->bindParam(':courseId', $courseId);
        $stmt_schedules->execute();

        // Fetch all schedules as an associative array
        $schedules = $stmt_schedules->fetchAll(PDO::FETCH_ASSOC);

        // Store schedules in $data["schedules"]
        $data["schedules"] = $schedules;

        // Close the connection
        $connection = null;
    } catch (PDOException $e) {
        // Handle query execution errors
        $_SESSION["error"] = "Pencarian jadwal gagal: " . $e->getMessage();
        header("location: updateCourseSchedule.php");
        exit;
    }
}