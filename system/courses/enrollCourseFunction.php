<?php
session_start();
require_once ("../../helper/dbHelper.php");
require_once ("../../helper/authHelper.php");
$permittedRole = ["lecturer", "admin"];
$pageName = "Sistem Absensi UPH - Enroll Mata Kuliah";
$data = [];
if (!authorization($permittedRole, $_SESSION["UserId"])) {
    header('location: ../auth/login.php');
}


if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset ($_GET["CourseId"])) {
    enrollCourseView();
} else {
    $_SESSION["error"] = "Tidak menemukan permintaan yang valid!";
    header("location: dataCourse.php");
    exit;
}

function enrollCourseView()
{

    global $data;

    // Check if CourseId is provided in the URL
    if (!isset ($_GET["CourseId"])) {
        $_SESSION["error"] = "CourseId tidak ditemukan.";
        header("location: dataCourse.php");
        exit;
    }

    // Sanitize and retrieve CourseId
    $courseId = htmlspecialchars($_GET["CourseId"]);

    // Database connection settings
    $connection = getConnection();

    // Check if the courseId exists in the Courses table
    $sql_check_course = "SELECT * FROM Courses WHERE CourseId = :courseId";

    try {
        // Prepare and execute the query
        $stmt_check_course = $connection->prepare($sql_check_course);
        $stmt_check_course->bindParam(':courseId', $courseId);
        $stmt_check_course->execute();

        // Fetch the result
        $course = $stmt_check_course->fetch(PDO::FETCH_ASSOC);

        // Check if the course exists
        if (!$course) {
            $_SESSION["error"] = "Mata kuliah dengan CourseId $courseId tidak ditemukan.";
            header("location: dataCourse.php");
            exit;
        }


        $connection = null;
    } catch (PDOException $e) {
        // Handle query execution errors
        $_SESSION["error"] = "Pencarian gagal: " . $e->getMessage();
        header("location: dataCourse.php");
        exit;
    }
}