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

function getCourseScheduleById($scheduleId)
{
    try {
        // Establish database connection
        $connection = getConnection();

        // Prepare SQL statement
        $stmt = $connection->prepare("SELECT courses.Code, schedules.DateTime FROM schedules INNER JOIN courses WHERE courses.CourseId = schedules.CourseId AND ScheduleId = :scheduleId");

        // Bind parameters
        $stmt->bindParam(':scheduleId', $scheduleId);

        // Execute the query
        $stmt->execute();

        // Fetch the result as an associative array
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Close the connection
        $connection = null;

        // Return the result
        return $result;
    } catch (PDOException $e) {
        // If an error occurs, you can handle it here
        echo "Error: " . $e->getMessage();
        return null;
    }
}

// Memproses pembaruan jadwal mata kuliah jika tombol "update" diklik
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset ($_POST["update"]) && isset ($_GET['ScheduleId'])) {
    updateCourseScheduleController();
}

// Memperbarui jadwal mata kuliah dan mengambil CourseId dari jadwal yang diperbarui
function updateCourseScheduleController()
{
    // Memeriksa apakah semua data yang diperlukan tersedia
    if (isset ($_POST["tanggal_kuliah"]) && isset ($_GET['ScheduleId'])) {
        $scheduleId = $_GET['ScheduleId']; // Ambil ScheduleId dari URL
        $tanggalKuliah = $_POST["tanggal_kuliah"];

        $today = date('Y-m-d');
        if ($tanggalKuliah <= $today) {
            $_SESSION["error"] = "Jadwal harus diatur di masa depan.";
            header("location: dataCourse.php");
            exit;
        }

        try {
            // Koneksi ke database
            $connection = getConnection();

            // Query SQL untuk memperbarui jadwal mata kuliah
            $stmt = $connection->prepare("UPDATE schedules SET DateTime = :tanggalKuliah WHERE ScheduleId = :scheduleId");
            $stmt->bindParam(':tanggalKuliah', $tanggalKuliah);
            $stmt->bindParam(':scheduleId', $scheduleId);
            $stmt->execute();

            // Ambil CourseId dari jadwal yang diperbarui
            $stmt = $connection->prepare("SELECT CourseId FROM schedules WHERE ScheduleId = :scheduleId");
            $stmt->bindParam(':scheduleId', $scheduleId);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $courseId = $result['CourseId'];

            // Redirect ke halaman updateCourseSchedule.php dengan menyertakan CourseId
            header("location: updateCourseSchedule.php?CourseId=$courseId");
            exit;
        } catch (PDOException $e) {
            // Tangani kesalahan jika terjadi
            $_SESSION["error"] = "Error: " . $e->getMessage();
            header("location: updateCourseScheduleEdit.php?ScheduleId=" . $scheduleId);
            exit;
        }
    } else {
        // Jika data yang diperlukan tidak tersedia, tampilkan pesan error
        $_SESSION["error"] = "Semua data diperlukan untuk melakukan pembaruan jadwal mata kuliah!";
        header("location: dataCourse.php");
        exit;
    }
}