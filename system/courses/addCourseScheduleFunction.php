<?php
session_start();
require_once ("../../helper/dbHelper.php");
require_once ("../../helper/authHelper.php");
$permittedRole = ["lecturer", "admin"];
$pageName = "Sistem Absensi UPH - Tambah Mahasiswa";
$data = [];
if (!authorization($permittedRole, $_SESSION["UserId"])) {
    header('location: ../auth/logout.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset ($_POST["tambah"])) {
    addCourseScheduleController();
}


function addCourseScheduleController()
{
    // Check if CourseId and schedule are provided
    if (!isset ($_POST["tambah"]) || !isset ($_POST["schedule"])) {
        $_SESSION["error"] = "Parameter tidak lengkap.";
        header("location: dataCourse.php");
        exit;
    }

    // Sanitize CourseId and schedule
    $courseId = htmlspecialchars($_POST["tambah"]);
    $schedule = htmlspecialchars($_POST["schedule"]);

    // Calculate the end time by adding 1 hour and 30 minutes to the schedule time
    $endTime = date('Y-m-d H:i:s', strtotime($schedule . ' + 90 minutes'));

    // Convert schedule to a consistent format (Y-m-d H:i:s)
    $scheduleFormatted = date('Y-m-d H:i:s', strtotime($schedule));

    // Database connection settings
    $connection = getConnection();

    try {
        // Check if the same schedule exists within the specified time frame
        $sql_check_schedule = "SELECT COUNT(*) as count FROM schedules WHERE CourseId = :courseId AND DateTime BETWEEN :schedule AND :endTime";

        // Prepare and execute the query to count existing schedules
        $stmt_check_schedule = $connection->prepare($sql_check_schedule);
        $stmt_check_schedule->bindParam(':courseId', $courseId);
        $stmt_check_schedule->bindParam(':schedule', $scheduleFormatted);
        $stmt_check_schedule->bindParam(':endTime', $endTime);
        $stmt_check_schedule->execute();

        // Fetch the count of existing schedules
        $scheduleCount = $stmt_check_schedule->fetch(PDO::FETCH_ASSOC)['count'];

        // If there are existing schedules, do not insert
        if ($scheduleCount > 0) {
            $_SESSION["error"] = "Jadwal yang sama + 90 menit sudah ada dalam database.";
            header("location: dataCourse.php");
            exit;
        }

        // SQL query to insert new course schedule
        $sql_insert_schedule = "INSERT INTO schedules (CourseId, DateTime) VALUES (:courseId, :schedule)";

        // Prepare and execute the query
        $stmt_insert_schedule = $connection->prepare($sql_insert_schedule);
        $stmt_insert_schedule->bindParam(':courseId', $courseId);
        $stmt_insert_schedule->bindParam(':schedule', $scheduleFormatted);
        $stmt_insert_schedule->execute();

        // Close the connection
        $connection = null;

        // Redirect with success message
        $_SESSION["success"] = "Jadwal berhasil ditambahkan.";
        header("location: dataCourse.php");
        exit;
    } catch (PDOException $e) {
        // Handle query execution errors
        $_SESSION["error"] = "Gagal menambahkan jadwal: " . $e->getMessage();
        header("location: dataCourse.php");
        exit;
    }
}