<?php
session_start();
require_once("../../helper/dbHelper.php");
require_once("../../helper/authHelper.php");

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
}

/**
 * Fungsi untuk memperbarui data kehadiran
 */
function updateAttendanceController()
{
    // Memeriksa apakah semua data yang diperlukan tersedia
    if (
        isset($_POST["attendanceId"]) && isset($_POST["date"])
        && isset($_POST["studentName"]) && isset($_POST["courseCode"])
        && isset($_POST["courseName"]) && isset($_POST["status"])
    ) {
        $attendanceId = $_POST["attendanceId"];
        $date = $_POST["date"];
        $studentName = $_POST["studentName"];
        $courseCode = $_POST["courseCode"];
        $courseName = $_POST["courseName"];
        $status = $_POST["status"];

        try {
            // Koneksi ke database
            $connection = getConnection();

            // Query SQL untuk memperbarui data kehadiran
            $stmt = $connection->prepare("UPDATE attendance SET Date = :date, StudentName = :studentName, CourseCode = :courseCode, CourseName = :courseName, Status = :status WHERE AttendanceId = :attendanceId");
            $stmt->bindParam(':date', $date);
            $stmt->bindParam(':studentName', $studentName);
            $stmt->bindParam(':courseCode', $courseCode);
            $stmt->bindParam(':courseName', $courseName);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':attendanceId', $attendanceId);
            $stmt->execute();

            // Redirect ke halaman viewAttendance.php setelah pembaruan berhasil
            header("location: historyAttendance.php");
            exit;
        } catch (Exception $e) {
            // Tangani kesalahan jika terjadi
            $_SESSION["error"] = "Error: " . $e->getMessage();
            header("location: historyAttendance.php");
            exit;
        }
    } else {
        // Jika data yang diperlukan tidak tersedia, tampilkan pesan error
        $_SESSION["error"] = "Semua data diperlukan untuk melakukan pembaruan kehadiran!";
        header("location: historyAttendance.php");
        exit;
    }
}

function getAttendanceByStudentIdAndDateAndCourseCode($studentId, $date, $courseCode)
{
    try {
        // Koneksi ke database
        $connection = getConnection();

        // Query untuk mengambil data absensi berdasarkan StudentId, Date, dan CourseCode
        $stmt = $connection->prepare("
            SELECT 
            DATE(attendances.FingerprintTimeIn) AS Date, 
            users.Name, 
            courses.Code, 
            courses.Name AS ClassName, 
            CONCAT(buildings.Letter, classrooms.Code) AS Room, 
            DATE_FORMAT(schedules.DateTime, '%H:%i:%s') AS DateTime, 
            DATE_FORMAT(attendances.FingerprintTimeIn, '%H:%i:%s') AS TimeIn,
            attendances.StudentId 
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
            WHERE 
                attendances.StudentId = :studentId 
                AND DATE(attendances.FingerprintTimeIn) = :date 
                AND courses.Code = :courseCode
        ");
        $stmt->bindParam(':studentId', $studentId);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':courseCode', $courseCode);
        $stmt->execute();

        // Mengembalikan hasil query sebagai array asosiatif
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        // Tangani kesalahan jika terjadi
        $_SESSION["error"] = "Error: " . $e->getMessage();
        return false;
    }
}

?>
