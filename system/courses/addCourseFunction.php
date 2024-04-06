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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["add"])) {
    addCourseController();
} else {
    $_SESSION["error"] = "Tidak menemukan permintaan yang valid!";
    header("location: dataCourse.php");
    exit;
}

addCourseView();

function addCourseController()
{
    // Periksa apakah semua kolom yang diperlukan diisi
    if (!isset($_POST["name"], $_POST["kode"], $_POST["ruang"])) {
        // Tangani kesalahan kolom yang kosong
        $_SESSION["error"] = "Error: Kolom yang diperlukan belum diisi";
        return;
    }

    // Bersihkan nilai input
    $name = htmlspecialchars($_POST["name"]);
    $code = htmlspecialchars($_POST["kode"]);
    $classroomId = htmlspecialchars($_POST["ruang"]);

    // Validasi nilai input
    if (empty($name) || empty($code) || empty($classroomId)) {
        // Tangani kesalahan kolom yang kosong
        $_SESSION["error"] = "Error: Semua kolom harus diisi";
        return;
    }

    // Periksa panjang nama
    $nameLength = strlen($name);
    if ($nameLength < 4 || $nameLength > 45) {
        // Tangani kesalahan panjang nama
        $_SESSION["error"] = "Error: Nama harus terdiri dari 4 hingga 45 karakter";
        return;
    }

    // Periksa panjang kode
    $codeLength = strlen($code);
    if ($codeLength < 3 || $codeLength > 5) {
        // Tangani kesalahan panjang kode
        $_SESSION["error"] = "Error: Kode harus terdiri dari 3 hingga 5 karakter";
        return;
    }

    // Periksa apakah ClassroomId dipilih
    if ($classroomId === "select") {
        // Tangani kesalahan ClassroomId tidak dipilih
        $_SESSION["error"] = "Error: Silakan pilih ruang kelas";
        return;
    }

    // Hubungkan ke database
    $connection = getConnection();

    // Periksa apakah ClassroomId ada dalam database
    $stmt = $connection->prepare("SELECT ClassroomId FROM classrooms WHERE ClassroomId = :classroomId");
    $stmt->bindParam(':classroomId', $classroomId);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$result) {
        // Tangani kesalahan ClassroomId tidak ditemukan dalam database
        $_SESSION["error"] = "Error: ID Ruang kelas tidak ditemukan";
        return;
    }

    // Menambahkan kursus ke dalam tabel courses
    try {
        $insertStmt = $connection->prepare("INSERT INTO courses (Name, Code, ClassroomId) VALUES (:name, :code, :classroomId)");
        $insertStmt->bindParam(':name', $name);
        $insertStmt->bindParam(':code', $code);
        $insertStmt->bindParam(':classroomId', $classroomId);
        $insertStmt->execute();

        // Kembalikan pesan sukses
        $_SESSION["success"] = "Kursus berhasil ditambahkan";
        header("location: dataCourse.php");
    } catch (PDOException $e) {
        // Tangani kesalahan saat menambahkan kursus
        $_SESSION["error"] = "Error: Gagal menambahkan kursus: " . $e->getMessage();
    }

    // Tutup koneksi
    $connection = null;
}



function addCourseView()
{
    global $data;
    $connection = getConnection();
    // If user is an admin, retrieve all courses
    try {
        $stmt = $connection->prepare("
                SELECT classrooms.ClassroomId, CONCAT(buildings.Letter, classrooms.Code) AS Room, classrooms.Capacity
                FROM  classrooms INNER JOIN buildings ON classrooms.BuildingId = buildings.BuildingId;
            ");
        $stmt->execute();
        $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Populate data array with course information
        $data['classrooms'] = $courses;
    } catch (Exception $e) {
        $_SESSION["error"] = $e->getMessage();
        return;
    }

}