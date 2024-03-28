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
        header("location: courseSchedule.php");
        exit;
    }

    // Sanitize CourseId and schedule
    $courseId = htmlspecialchars($_POST["tambah"]);
    $schedule = htmlspecialchars($_POST["schedule"]);

    // Validate if the schedule is greater than today's date
    $today = date('Y-m-d');
    if ($schedule <= $today) {
        $_SESSION["error"] = "Jadwal harus diatur di masa depan.";
        header("location: courseSchedule.php");
        exit;
    }

    // Convert schedule to a consistent format (Y-m-d H:i:s)
    $scheduleFormatted = date('Y-m-d H:i:s', strtotime($schedule));

    // Database connection settings
    $connection = getConnection();

    try {
        // Check if the same schedule exists for the courseId
        $sql_check_schedule = "SELECT COUNT(*) as count FROM schedules WHERE CourseId = :courseId AND DATE(DateTime) = DATE(:schedule)";

        // Prepare and execute the query to count existing schedules
        $stmt_check_schedule = $connection->prepare($sql_check_schedule);
        $stmt_check_schedule->bindParam(':courseId', $courseId);
        $stmt_check_schedule->bindParam(':schedule', $scheduleFormatted);
        $stmt_check_schedule->execute();

        // Fetch the count of existing schedules
        $scheduleCount = $stmt_check_schedule->fetch(PDO::FETCH_ASSOC)['count'];

        // If there are existing schedules, do not insert
        if ($scheduleCount > 0) {
            $_SESSION["error"] = "Jadwal yang sama sudah ada dalam database.";
            header("location: courseSchedule.php");
            exit;
        }

        // Start a transaction
        $connection->beginTransaction();

        // SQL query to insert new course schedule
        $sql_insert_schedule = "INSERT INTO schedules (CourseId, DateTime) VALUES (:courseId, :schedule)";

        // Prepare and execute the query to insert schedule
        $stmt_insert_schedule = $connection->prepare($sql_insert_schedule);
        $stmt_insert_schedule->bindParam(':courseId', $courseId);
        $stmt_insert_schedule->bindParam(':schedule', $scheduleFormatted);
        $stmt_insert_schedule->execute();

        // Get the inserted schedule ID
        $scheduleId = $connection->lastInsertId();

        // SQL query to insert students into the attendances table
        $sql_insert_attendance = "INSERT INTO attendances (StudentId, ScheduleId) 
                                SELECT e.StudentId, :scheduleId 
                                FROM enrollments e 
                                WHERE e.CourseId = :courseId";

        // Prepare and execute the query to insert attendances
        $stmt_insert_attendance = $connection->prepare($sql_insert_attendance);
        $stmt_insert_attendance->bindParam(':scheduleId', $scheduleId);
        $stmt_insert_attendance->bindParam(':courseId', $courseId);
        $stmt_insert_attendance->execute();

        // Commit the transaction
        $connection->commit();

        // Close the connection
        $connection = null;

        // Redirect with success message
        $_SESSION["success"] = "Jadwal berhasil ditambahkan dan kehadiran mahasiswa telah direkam.";
        header("location: updateCourseSchedule.php?CourseId={$courseId}");
        exit;
    } catch (PDOException $e) {
        // Rollback the transaction on error
        $connection->rollback();

        // Handle query execution errors
        $_SESSION["error"] = "Gagal menambahkan jadwal: " . $e->getMessage();
        header("location: updateCourseSchedule.php?CourseId={$courseId}");
        exit;
    }
}


?>
