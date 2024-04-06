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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["tambah"])) {
    addCourseScheduleController();
}


function addCourseScheduleController()
{
    // Check if CourseId and schedule are provided
    if (empty($_POST["date"]) || empty($_POST["timeStart"]) || empty($_POST["timeEnd"])) {
        $_SESSION["error"] = "Mohon lengkapi semua kolom."; // Set error message in session
        header("location: updateCourseSchedule.php?CourseId=" . $_POST["tambah"]); // Redirect to the courseSchedule.php page
        exit; // Terminate the script
    }

    // Sanitize CourseId and schedule
    $courseId = htmlspecialchars($_POST["tambah"]);
    $date = htmlspecialchars($_POST["date"]);
    $timeStart = htmlspecialchars($_POST["timeStart"]);
    $timeEnd = htmlspecialchars($_POST["timeEnd"]);

    // Connect to the database
    $conn = getConnection(); // Assuming you have this function to establish a PDO connection

    // Retrieve ClassroomId based on CourseId
    $query = "SELECT ClassroomId FROM courses WHERE CourseId = :courseId";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':courseId', $courseId);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        $_SESSION["error"] = "CourseId tidak valid."; // Set error message in session
        header("location: updateCourseSchedule.php?CourseId=" . $courseId); // Redirect to the courseSchedule.php page
        exit; // Terminate the script
    }

    $classroomId = $result['ClassroomId'];

    // Prepare SQL statement to check for overlapping schedules
    $query = "SELECT * FROM schedules 
              WHERE CourseId = :courseId 
              AND Date = :date 
              AND ((StartTime < :endTime AND EndTime > :startTime) 
              OR (StartTime >= :startTime AND StartTime < :endTime) 
              OR (EndTime > :startTime AND EndTime <= :endTime))";

    $stmt = $conn->prepare($query);
    $stmt->bindParam(':courseId', $courseId);
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':startTime', $timeStart);
    $stmt->bindParam(':endTime', $timeEnd);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $_SESSION["error"] = "Kelas sudah terjadwal untuk waktu tersebut."; // Set error message in session
        header("location: updateCourseSchedule.php?CourseId=" . $courseId); // Redirect to the courseSchedule.php page
        exit; // Terminate the script
    }

    // Validate date format
    if (!strtotime($date)) {
        $_SESSION["error"] = "Format tanggal tidak valid."; // Set error message in session
       header("location: updateCourseSchedule.php?CourseId=" . $courseId);
        exit; // Terminate the script
    }

    // Validate time format
    if (!strtotime($timeStart) || !strtotime($timeEnd)) {
        $_SESSION["error"] = "Format waktu tidak valid."; // Set error message in session
       header("location: updateCourseSchedule.php?CourseId=" . $courseId);
        exit; // Terminate the script
    }

    // Validate if date is greater than or equal to today
    if (strtotime($date) < strtotime(date("Y-m-d"))) {
        $_SESSION["error"] = "Tanggal harus sama atau setelah hari ini."; // Set error message in session
        header("location: updateCourseSchedule.php?CourseId=" . $courseId); // Redirect to the courseSchedule.php page
        exit; // Terminate the script
    }

    // Validate if timeStart is before timeEnd
    if (strtotime($timeStart) >= strtotime($timeEnd)) {
        $_SESSION["error"] = "Waktu mulai harus sebelum waktu selesai."; // Set error message in session
        header("location: updateCourseSchedule.php?CourseId=" . $courseId); // Redirect to the courseSchedule.php page
        exit; // Terminate the script
    }


    // Insert the schedule into the schedules table
    $insertQuery = "INSERT INTO schedules (CourseId, Date, StartTime, EndTime) 
VALUES (:courseId, :date, :timeStart, :timeEnd)";
    $insertStmt = $conn->prepare($insertQuery);
    $insertStmt->bindParam(':courseId', $courseId);
    $insertStmt->bindParam(':date', $date);
    $insertStmt->bindParam(':timeStart', $timeStart);
    $insertStmt->bindParam(':timeEnd', $timeEnd);
    $insertStmt->execute();

    // Retrieve the last inserted ScheduleId
    $scheduleId = $conn->lastInsertId();

    // Insert attendances for enrolled students
    $sqlInsertAttendance = "INSERT INTO attendances (StudentId, ScheduleId) 
        SELECT e.StudentId, :scheduleId 
        FROM enrollments e 
        WHERE e.CourseId = :courseId";

    // Prepare and execute the query to insert attendances
    $stmtInsertAttendance = $conn->prepare($sqlInsertAttendance);
    $stmtInsertAttendance->bindParam(':scheduleId', $scheduleId);
    $stmtInsertAttendance->bindParam(':courseId', $courseId);
    $stmtInsertAttendance->execute();


    // Redirect to the success page or wherever necessary
    header("location: updateCourseSchedule.php?CourseId=" . $_POST["tambah"]); // Redirect to the courseSchedule.php page
    $_SESSION["success"] = "Sukses menambahkan jadwal mata kuliah!.";
    exit;
}



?>