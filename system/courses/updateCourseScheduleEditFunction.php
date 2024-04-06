<?php
session_start();
require_once ("../../helper/dbHelper.php");
require_once ("../../helper/authHelper.php");

$permittedRole = ["lecturer", "admin"];
$pageName = "Sistem Absensi UPH - Edit Jadwal Mata Kuliah";
$data = [];

if (!authorization($permittedRole, $_SESSION["UserId"])) {
    header('location: ../auth/login.php');
}



if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET["ScheduleId"])) {
    updateCourseScheduleEditView();
} else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["update"])) {

    updateCourseScheduleEditController();
} else {
    $_SESSION["error"] = "Tidak menemukan permintaan yang valid!";
    header("location: dataCourse.php");
    exit;
}

function updateCourseScheduleEditView()
{

    global $data;

    try {
        // Get the ScheduleId from the query string
        $scheduleId = htmlspecialchars($_GET["ScheduleId"]);

        // Connect to the database
        $conn = getConnection(); // Assuming you have a function to establish a PDO connection

        // Prepare and execute SQL query to retrieve all columns from schedules table for the given ScheduleId
        $stmt = $conn->prepare("SELECT * FROM schedules WHERE ScheduleId = :scheduleId");
        $stmt->bindParam(':scheduleId', $scheduleId);
        $stmt->execute();

        // Fetch the schedule data
        $scheduleData = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$scheduleData) {
            $_SESSION["error"] = "Schedule not found."; // Set error message in session
            header("location: dataCourse.php"); // Redirect to an error page
            exit; // Terminate the script
        }

        $data = $scheduleData;

    } catch (Exception $e) {
        $_SESSION["error"] = "Error: " . $e->getMessage(); // Set error message in session
        header("location: dataCourse.php"); // Redirect to an error page
        exit; // Terminate the script
    }
}

function updateCourseScheduleEditController()
{


    try {
        // Check if required form fields are provided
        if (empty($_POST["date"]) || empty($_POST["timeStart"]) || empty($_POST["timeEnd"])) {
            $_SESSION["error"] = "Mohon lengkapi semua kolom."; // Set error message in session
            header("location: updateCourseSchedule.php?CourseId=" . $_POST["CourseId"]); // Redirect back to the schedule edit view with an error message
            exit; // Terminate the script
        }

        // Get the ScheduleId from the form data
        $scheduleId = htmlspecialchars($_POST["update"]);

        // Get the updated schedule data from the form
        $date = htmlspecialchars($_POST["date"]);
        $timeStart = htmlspecialchars($_POST["timeStart"]);
        $timeEnd = htmlspecialchars($_POST["timeEnd"]);

        // Validate date format
        if (!strtotime($date)) {
            $_SESSION["error"] = "Format tanggal tidak valid."; // Set error message in session
            header("location: updateCourseSchedule.php?CourseId=" . $_POST["CourseId"]); // Redirect back to the schedule edit view with an error message
            exit; // Terminate the script
        }

        // Validate time format
        if (!strtotime($timeStart) || !strtotime($timeEnd)) {
            $_SESSION["error"] = "Format waktu tidak valid."; // Set error message in session
            header("location: updateCourseSchedule.php?CourseId=" . $_POST["CourseId"]); // Redirect back to the schedule edit view with an error message
            exit; // Terminate the script
        }

        // Validate if date is greater than or equal to today
        if (strtotime($date) < strtotime(date("Y-m-d"))) {
            $_SESSION["error"] = "Tanggal harus sama atau setelah hari ini."; // Set error message in session
            header("location: updateCourseSchedule.php?CourseId=" . $_POST["CourseId"]); // Redirect back to the schedule edit view with an error message
            exit; // Terminate the script
        }

        // Validate if timeStart is before timeEnd
        if (strtotime($timeStart) >= strtotime($timeEnd)) {
            $_SESSION["error"] = "Waktu mulai harus sebelum waktu selesai."; // Set error message in session
            header("location: updateCourseSchedule.php?CourseId=" . $_POST["CourseId"]); // Redirect back to the schedule edit view with an error message
            exit; // Terminate the script
        }

        // Connect to the database
        $conn = getConnection(); // Assuming you have a function to establish a PDO connection

        $query = "SELECT ClassroomId FROM courses INNER JOIN schedules ON schedules.CourseId = courses.CourseId WHERE schedules.ScheduleId = :scheduleId";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':scheduleId', $scheduleId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            $_SESSION["error"] = "CourseId tidak valid."; // Set error message in session
            header("location: updateCourseSchedule.php?CourseId=" . $_POST["CourseId"]); // Redirect to the courseSchedule.php page
            exit; // Terminate the script
        }

        $classroomId = $result['ClassroomId'];

        // Prepare SQL statement to check for overlapping schedules, excluding the current schedule being edited
        $overlapQuery = "SELECT * FROM schedules 
        INNER JOIN courses ON courses.CourseId = schedules.CourseId
        INNER JOIN classrooms ON classrooms.ClassroomId = courses.ClassroomId
        WHERE classrooms.ClassroomId = :classroomId
        AND
        ScheduleId != :scheduleId
      AND Date = :date 
      AND ((StartTime < :endTime AND EndTime > :startTime) 
      OR (StartTime >= :startTime AND StartTime < :endTime) 
      OR (EndTime > :startTime AND EndTime <= :endTime))";


        $overlapStmt = $conn->prepare($overlapQuery);
        $overlapStmt->bindParam(':classroomId', $classroomId);
        $overlapStmt->bindParam(':scheduleId', $scheduleId);
        $overlapStmt->bindParam(':date', $date);
        $overlapStmt->bindParam(':startTime', $timeStart);
        $overlapStmt->bindParam(':endTime', $timeEnd);
        $overlapStmt->execute();

        $overlapResult = $overlapStmt->fetch(PDO::FETCH_ASSOC);

        if ($overlapResult) {
            $_SESSION["error"] = "Jadwal tumpang tindih dengan jadwal lain."; // Set error message in session
            header("location: updateCourseSchedule.php?CourseId=" . $_POST["CourseId"]); // Redirect back to the schedule edit view with an error message
            exit; // Terminate the script
        }

        // Proceed to update the schedule in the database
        // Prepare and execute SQL query to update the schedule in the database
        $updateStmt = $conn->prepare("UPDATE schedules SET Date = :date, StartTime = :timeStart, EndTime = :timeEnd WHERE ScheduleId = :scheduleId");
        $updateStmt->bindParam(':date', $date);
        $updateStmt->bindParam(':timeStart', $timeStart);
        $updateStmt->bindParam(':timeEnd', $timeEnd);
        $updateStmt->bindParam(':scheduleId', $scheduleId);
        $updateStmt->execute();

        // Redirect to a success page or back to the schedule edit view with a success message
        $_SESSION["success"] = "Jadwal berhasil diperbarui.";
        header("location: updateCourseSchedule.php?CourseId=" . $_POST["CourseId"]); // Redirect to the schedule edit view
        exit; // Terminate the script

    } catch (Exception $e) {
        $_SESSION["error"] = "Error: " . $e->getMessage(); // Set error message in session
        header("location: updateCourseSchedule.php?CourseId=" . $_POST["CourseId"]); // Redirect back to the schedule edit view with an error message
        exit; // Terminate the script
    }
}