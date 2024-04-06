<?php
session_start();
require_once ("../../helper/dbHelper.php");
require_once ("../../helper/authHelper.php");

$permittedRole = ["lecturer", "admin"];
$pageName = "Sistem Absensi UPH - Edit Absensi";
$data = [];

// Memeriksa izin akses pengguna
if (!authorization($permittedRole, $_SESSION["UserId"])) {
    header('location: ../auth/login.php');
}

// Memproses pembaruan kehadiran jika tombol "updateAttendance" diklik
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["updateAttendance"])) {
    updateAttendanceController();
} else if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET["StudentId"]) && isset($_GET["ScheduleId"])) {
    updateAttendanceView();
} else {
    $_SESSION["error"] = "Tidak menemukan permintaan yang valid!";

    header("location: historyAttendance.php");
    exit;
}

// Function to handle updating attendance
function updateAttendanceController()
{
    try {
        // Get data from the form submission
        $studentId = htmlspecialchars($_POST["studentId"]);
        $scheduleId = htmlspecialchars($_POST["scheduleId"]);
        $status = htmlspecialchars($_POST["status"]);

        // Validate status value
        if (!in_array($status, [0, 1, 2])) {
            $_SESSION["error"] = "Invalid status value.";
            header("location: updateAttendance.php?StudentId=$studentId&ScheduleId=$scheduleId"); // Redirect back to the form with query parameters
            exit();
        }

        // Connect to the database
        $conn = getConnection();

        // Check if the attendance record exists for the student and schedule
        $stmt = $conn->prepare("SELECT * FROM attendances WHERE StudentId = :studentId AND ScheduleId = :scheduleId");
        $stmt->bindParam(':studentId', $studentId);
        $stmt->bindParam(':scheduleId', $scheduleId);
        $stmt->execute();
        $attendance = $stmt->fetch(PDO::FETCH_ASSOC);

        // If the attendance record exists, update it; otherwise, send an error message
        if ($attendance) {
            $stmt = $conn->prepare("UPDATE attendances SET Status = :status WHERE StudentId = :studentId AND ScheduleId = :scheduleId");
            $stmt->bindParam(':studentId', $studentId);
            $stmt->bindParam(':scheduleId', $scheduleId);
            $stmt->bindParam(':status', $status);
            $stmt->execute();

            // Redirect to a success page
            $_SESSION["success"] = "Attendance updated successfully.";
            header("location: historyAttendance.php");
            exit();
        } else {
            // Attendance record not found
            $_SESSION["error"] = "Attendance record not found.";
            header("location: updateAttendance.php?StudentId=$studentId&ScheduleId=$scheduleId"); // Redirect back to the form with query parameters
            exit();
        }
    } catch (Exception $e) {
        // Handle errors
        $_SESSION["error"] = "Error updating attendance: " . $e->getMessage();
        header("location: updateAttendance.php?StudentId=$studentId&ScheduleId=$scheduleId"); // Redirect back to the form with query parameters
        exit();
    }
}


function updateAttendanceView()
{
    global $data;
    try {
        // Get the StudentId and ScheduleId from the query string
        $studentId = htmlspecialchars($_GET["StudentId"]);
        $scheduleId = htmlspecialchars($_GET["ScheduleId"]);




        // Connect to the database
        $conn = getConnection(); // Assuming you have a function to establish a PDO connection

        // Prepare and execute SQL query to retrieve the required data
        $stmt = $conn->prepare("
        SELECT s.Date, u.Name AS UserName, c.Code AS CourseCode, c.Name AS CourseName, s.StartTime, s.EndTime, a.Status
        FROM schedules s
        INNER JOIN courses c ON s.CourseId = c.CourseId
        INNER JOIN users u ON u.StudentId = :studentId
            LEFT JOIN attendances a ON s.ScheduleId = a.ScheduleId AND u.StudentId = a.StudentId
        WHERE s.ScheduleId = :scheduleId;
        
        ");
        $stmt->bindParam(':studentId', $studentId);
        $stmt->bindParam(':scheduleId', $scheduleId);
        $stmt->execute();

        // Fetch the data
        $attendanceData = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$attendanceData) {
            $_SESSION["error"] = "Data kehadiran tidak ditemukan."; // Set error message in session
            header("location: historyAttendance.php"); // Redirect to an error page
            exit; // Terminate the script
        }

        // Assign the retrieved data to the $data variable for use in the view
        $data = $attendanceData;


    } catch (Exception $e) {
        $_SESSION["error"] = "Error: " . $e->getMessage(); // Set error message in session
        header("location: historyAttendance.php"); // Redirect to an error page
        exit; // Terminate the script
    }
}