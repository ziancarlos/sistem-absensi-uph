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
        isset($_POST["studentId"]) && isset($_POST["date"])
        && isset($_POST["status"]) && isset($_POST["courseCode"])
    ) {
        $studentId = $_POST["studentId"];
        $date = $_POST["date"];
        $status = $_POST["status"];
        $courseCode = $_POST["courseCode"];

        // Memeriksa apakah status memiliki nilai yang valid (0 atau 1)
        if ($status !== "0" && $status !== "1") {
            // Jika status tidak valid, tampilkan pesan error
            $_SESSION["error"] = "Status tidak valid.";
            header("location: historyAttendance.php");
            exit;
        }

        try {
            // Koneksi ke database
            $connection = getConnection();

            // Query SQL untuk memperbarui data kehadiran hanya pada status
            $stmt = $connection->prepare("
            UPDATE 
                attendances
            INNER JOIN 
                schedules ON attendances.ScheduleId = schedules.ScheduleId 
            INNER JOIN 
                courses ON schedules.CourseId = courses.CourseId 
            SET 
                attendances.Status = :status 
            WHERE 
                attendances.StudentId = :studentId  
                AND DATE(schedules.DateTime) = :date
                AND courses.Code = :courseCode 
            ");
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':studentId', $studentId);
            $stmt->bindParam(':date', $date);
            $stmt->bindParam(':courseCode', $courseCode);
            $stmt->execute();

            // Redirect ke halaman historyAttendance.php setelah pembaruan berhasil
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
            attendances.StudentId,
            attendances.Status 
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
