<?php
session_start();
require_once("../../helper/dbHelper.php");
require_once("../../helper/authHelper.php");
$permittedRole = ["admin", "lecturer", "student"];
$pageName = "Sistem Absensi UPH - Histori Absensi";
$data = [];
if (!authorization($permittedRole, $_SESSION["UserId"])) {
    header('location: ../auth/logout.php');
}

dataAttendanceView();

function dataAttendanceView()
{
    global $data;
    $userId = $_SESSION["UserId"];
    $connection = getConnection();

    try {
        $stmt = $connection->prepare("
            SELECT 
            DATE(attendances.FingerprintTimeIn) AS Date, 
            users.Name, 
            courses.Code, 
            courses.Name AS ClassName, 
            CONCAT(buildings.Letter, classrooms.Code) AS Room, 
            DATE_FORMAT(schedules.DateTime, '%H:%i:%s') AS DateTime, 
            DATE_FORMAT(attendances.FingerprintTimeIn, '%H:%i:%s') AS TimeIn 
        FROM 
            attendances 
        INNER JOIN 
            students ON attendances.StudentId = students.StudentId 
        INNER JOIN 
            users ON students.StudentId = users.UserId 
        INNER JOIN 
            schedules ON attendances.ScheduleId = schedules.ScheduleId 
        INNER JOIN 
            courses ON schedules.CourseId = courses.CourseId 
        INNER JOIN 
            classrooms ON courses.ClassroomId = classrooms.ClassroomId 
        INNER JOIN 
            buildings ON classrooms.BuildingId = buildings.BuildingId;
        ");
        $stmt->execute();
        $attendances = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Populate data array with attendance information
        $data['attendances'] = $attendances;
    } catch (Exception $e) {
        $_SESSION["error"] = $e->getMessage();
        header("location: historyAttendance.php");
        exit;
    }
}
?>
