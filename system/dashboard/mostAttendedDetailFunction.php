<?php
session_start();
require_once ("../../helper/dbHelper.php");
require_once ("../../helper/authHelper.php");

$permittedRole = ["admin", "lecturer", "student"];
$pageName = "Sistem Absensi UPH - Dashboard";
$data = [];

// Memeriksa izin akses pengguna
if (!authorization($permittedRole, $_SESSION["UserId"])) {
    header('location: ../auth/logout.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET["Code"])) {
        dataAttendanceClassView();
    } else if (isset($_GET["StudentId"])) {
        dataAttendanceStudentView();
    } else {
        $_SESSION["error"] = "Tidak menemukan permintaan yang valid!";
        header("location: dashboard.php");
        exit;
    }
}

// Fungsi untuk menampilkan semua data absensi berdasarkan kelas
function dataAttendanceClassView()
{
    global $data;

    if (!isset($_GET["Code"])) {
        $_SESSION["error"] = "CourseId tidak ditemukan.";
        header("location: dashboard.php");
        exit;
    }
    $courseId = htmlspecialchars($_GET["Code"]);

    $connection = getConnection();

    try {
        $stmt = $connection->prepare("
        SELECT        
            students.StudentId,
            attendances.ScheduleId,    
            users.Name, 
            courses.Code, 
            courses.Name AS ClassName, 
            CONCAT(buildings.Letter, classrooms.Code) AS Room, 
            schedules.Date, schedules.StartTime, schedules.EndTime,
            DATE_FORMAT(attendances.FingerprintTimeIn, '%H:%i:%s') AS TimeIn,
            attendances.StudentId,
            attendances.Status 
        FROM 
            attendances 
        LEFT JOIN 
            students ON attendances.StudentId = students.StudentId 
        LEFT JOIN 
            users ON students.StudentId = users.StudentId 
        LEFT JOIN 
            schedules ON attendances.ScheduleId = schedules.ScheduleId 
        LEFT JOIN 
            courses ON schedules.CourseId = courses.CourseId 
        LEFT JOIN 
            classrooms ON courses.ClassroomId = classrooms.ClassroomId 
        LEFT JOIN 
            buildings ON classrooms.BuildingId = buildings.BuildingId
        WHERE courses.Code = :courseId
        ");
        $stmt->bindParam(':courseId', $courseId);
        $stmt->execute();
        $attendances = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Populate data array with all attendance information
        $data['attendances'] = $attendances;
    } catch (Exception $e) {
        $_SESSION["error"] = $e->getMessage();
        header("location: dashboard.php");
        exit;
    }
}

// Fungsi untuk menampilkan semua data absensi berdasarkan mahasiswa
function dataAttendanceStudentView()
{
    global $data;

    if (!isset($_GET["StudentId"])) {
        $_SESSION["error"] = "StudentId tidak ditemukan.";
        header("location: dashboard.php");
        exit;
    }
    $studentId = htmlspecialchars($_GET["StudentId"]);

    $connection = getConnection();

    try {
        $stmt = $connection->prepare("
        SELECT        
            students.StudentId,
            attendances.ScheduleId,    
            users.Name, 
            courses.Code, 
            courses.Name AS ClassName, 
            CONCAT(buildings.Letter, classrooms.Code) AS Room, 
            schedules.Date, schedules.StartTime, schedules.EndTime,
            DATE_FORMAT(attendances.FingerprintTimeIn, '%H:%i:%s') AS TimeIn,
            attendances.StudentId,
            attendances.Status 
        FROM 
            attendances 
        LEFT JOIN 
            students ON attendances.StudentId = students.StudentId 
        LEFT JOIN 
            users ON students.StudentId = users.StudentId 
        LEFT JOIN 
            schedules ON attendances.ScheduleId = schedules.ScheduleId 
        LEFT JOIN 
            courses ON schedules.CourseId = courses.CourseId 
        LEFT JOIN 
            classrooms ON courses.ClassroomId = classrooms.ClassroomId 
        LEFT JOIN 
            buildings ON classrooms.BuildingId = buildings.BuildingId
        WHERE attendances.StudentId = :studentId
        ");
        $stmt->bindParam(':studentId', $studentId);
        $stmt->execute();
        $attendances = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Populate data array with all attendance information
        $data['attendances'] = $attendances;
    } catch (Exception $e) {
        $_SESSION["error"] = $e->getMessage();
        header("location: dashboard.php");
        exit;
    }
}
?>
