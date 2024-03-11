<?php
session_start();
require_once("../../helper/dbHelper.php");
require_once("../../helper/authHelper.php");
$permittedRole = ["lecturer", "admin", "student"];
$pageName = "Sistem Absensi UPH - Data Mahasiswa";
$data = [];
if (!authorization($permittedRole, $_SESSION["UserId"])) {
    header('location: ../auth/login.php');
}

addCourseView();

function addCourseView()
{
    global $data;
    $connection = getConnection();
    // If user is an admin, retrieve all courses
    try {
        $stmt = $connection->prepare("
                SELECT classrooms.ClassroomId, CONCAT(buildings.Letter, classrooms.Code) AS Room, classrooms.Capacity
                FROM  classrooms INNER JOIN buildings ON classrooms.BuildingId = buildings.BuildingId;
            ");
        $stmt->execute();
        $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Populate data array with course information
        $data['classrooms'] = $courses;
    } catch (Exception $e) {
        $_SESSION["error"] = $e->getMessage();
        return;
    }

}