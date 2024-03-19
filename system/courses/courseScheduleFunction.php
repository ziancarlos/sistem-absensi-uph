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

courseScheduleView();


function courseScheduleView()
{
    // Database connection settings
    $connection = getConnection();

    try {
        // SQL query to retrieve all courses
        $sql_courses = "SELECT * FROM Courses";

        // Prepare and execute the query
        $stmt_courses = $connection->prepare($sql_courses);
        $stmt_courses->execute();

        // Fetch all courses as an associative array
        $courses = $stmt_courses->fetchAll(PDO::FETCH_ASSOC);

        // Store courses in $data["courses"]
        global $data;
        $data["courses"] = $courses;

        // Close the connection
        $connection = null;
    } catch (PDOException $e) {
        // Handle query execution errors
        $_SESSION["error"] = "Query failed: " . $e->getMessage();
        header("location: dataCourse.php");
        exit;
    }
}