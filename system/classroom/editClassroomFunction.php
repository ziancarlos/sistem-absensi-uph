<?php
session_start();
require_once("../../helper/dbHelper.php");
require_once("../../helper/authHelper.php");
$permittedRole = ["admin", "Lecturer"];
$pageName = "Sistem Absensi UPH - Edit Dosen";
$data = [];
if (!authorization($permittedRole, $_SESSION["UserId"])) {
    header('location: ../auth/login.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["editClassroom"])) {
    // Panggil fungsi getClassroomById untuk mendapatkan informasi ruang kelas
    $classroomId = $_POST["ClassroomId"]; // Perbaikan di sini
    $data["classroom"] = getClassroomById($classroomId);

    // Periksa apakah ruang kelas ditemukan
    if (!$data["classroom"]) {
        $_SESSION["error"] = "Ruang kelas tidak ditemukan!";
        header("location: addClassroom.php");
        exit;
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["ubah"])) {
    // Panggil fungsi updateClassroomController() untuk memproses pembaruan ruang kelas
    updateClassroomController();
}


function getClassroomById($classroomId)
{
    try {
        // Koneksi ke database
        $connection = getConnection();

        // Query untuk mengambil data ruang kelas berdasarkan ClassroomId
        $stmt = $connection->prepare("SELECT classrooms.code, classrooms.ClassroomId, classrooms.Capacity, CONCAT(buildings.Letter) AS Room FROM classrooms INNER JOIN buildings ON classrooms.BuildingId = buildings.BuildingId WHERE classrooms.ClassroomId = :classroomId");
        $stmt->bindParam(':classroomId', $classroomId);
        $stmt->execute();

        // Mengembalikan hasil query sebagai array asosiatif
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        // Tangani kesalahan jika terjadi
        $_SESSION["error"] = "Error: " . $e->getMessage();
        return false;
    }
}

// Fungsi untuk mengontrol pembaruan kapasitas ruang kelas
function updateClassroomController()
{
    // Periksa apakah semua data yang diperlukan dikirim melalui metode POST
    if (isset($_POST['ClassroomId'], $_POST['capacity'])) {
        $classroomId = $_POST['ClassroomId'];
        $newCapacity = $_POST['capacity'];

        try {
            // Koneksi ke database
            $connection = getConnection();

            // Query untuk memperbarui kapasitas ruang kelas
            $stmt = $connection->prepare("UPDATE classrooms SET Capacity = :newCapacity WHERE ClassroomId = :classroomId");
            $stmt->bindParam(':newCapacity', $newCapacity);
            $stmt->bindParam(':classroomId', $classroomId);
            $stmt->execute();

            // Redirect ke halaman editClassroom.php setelah berhasil melakukan pembaruan
            $_SESSION['success'] = "Kapasitas ruang kelas berhasil diperbarui.";
            header("location: addClassroom.php");
            exit;
        } catch (Exception $e) {
            // Tangani kesalahan jika terjadi
            $_SESSION["error"] = "Error: " . $e->getMessage();
            header("location: editClassroom.php?ClassroomId=" . $classroomId);
            exit;
        }
    } else {
        // Redirect ke halaman sebelumnya jika data tidak lengkap
        $_SESSION["error"] = "Semua field harus diisi";
        header("location: addClassroom.php");
        exit;
    }
}

?>


