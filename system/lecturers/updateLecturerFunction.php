<?php
session_start();
require_once("../../helper/dbHelper.php");
require_once("../../helper/authHelper.php");
$permittedRole = ["admin"];
$pageName = "Sistem Absensi UPH - Edit Dosen";
$data = [];
if (!authorization($permittedRole, $_SESSION["UserId"])) {
    header('location: ../auth/login.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["ubahView"])) {
    updateLecturerView();

    if ($data == null) {
        $_SESSION["error"] = "Data tidak dapat diambil, Hubungi admin jika masalah ini terus terjadi!";

        header("location: dataLecturer.php");
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["ubah"])) {
    updateLecturerController();
}


function updateLecturerController()
{
    try {
        // Ambil nilai-nilai yang dikirimkan dalam permintaan POST
        $name = htmlspecialchars($_POST["name"]);
        $email = htmlspecialchars($_POST["email"]);
        $password = htmlspecialchars($_POST["password"]);
        $userId = htmlspecialchars($_POST["UserId"]); // Mengambil UserId dari form

        // Cek apakah ada perubahan dalam data
        $connection = getConnection(); // Mengasumsikan Anda memiliki fungsi bernama getConnection() untuk membuat koneksi PDO

        // Query untuk mendapatkan data dosen sebelum perubahan
        $stmt = $connection->prepare("SELECT * FROM Users WHERE UserId = :userId");
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();

        // Periksa apakah ada hasil yang dikembalikan
        $existingLecturer = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$existingLecturer) {
            throw new Exception("Data dosen tidak ditemukan!");
        }

        // Bandingkan nilai-nilai yang baru dengan nilai-nilai yang ada dalam database
        if ($existingLecturer['Name'] === $name && $existingLecturer['Email'] === $email && empty($password)) {
            throw new Exception("Tidak ada perubahan yang dilakukan!");
        }

        // Lakukan pengecekan apakah nama atau email sudah ada dalam database
        $nameCheckQuery = "SELECT * FROM Users WHERE Name = :name AND UserId != :userId";
        $emailCheckQuery = "SELECT * FROM Users WHERE Email = :email AND UserId != :userId";

        $nameCheckStmt = $connection->prepare($nameCheckQuery);
        $nameCheckStmt->bindParam(':name', $name);
        $nameCheckStmt->bindParam(':userId', $userId);
        $nameCheckStmt->execute();

        if ($nameCheckStmt->rowCount() > 0) {
            throw new Exception("Nama sudah terdaftar!");
        }

        $emailCheckStmt = $connection->prepare($emailCheckQuery);
        $emailCheckStmt->bindParam(':email', $email);
        $emailCheckStmt->bindParam(':userId', $userId);
        $emailCheckStmt->execute();

        if ($emailCheckStmt->rowCount() > 0) {
            throw new Exception("Alamat email sudah terdaftar!");
        }

        // Lakukan perubahan jika ada perubahan dalam data
        $updateUserStmt = $connection->prepare("UPDATE Users SET Name = :name, Email = :email WHERE UserId = :userId");
        $updateUserStmt->bindParam(':name', $name);
        $updateUserStmt->bindParam(':email', $email);
        $updateUserStmt->bindParam(':userId', $userId);
        $updateUserStmt->execute();

        // Jika password tidak kosong, update password
        if (!empty($password)) {
            $hashedPassword = md5($password);
            $updatePasswordStmt = $connection->prepare("UPDATE Users SET Password = :password WHERE UserId = :userId");
            $updatePasswordStmt->bindParam(':password', $hashedPassword);
            $updatePasswordStmt->bindParam(':userId', $userId);
            $updatePasswordStmt->execute();
        }

        $_SESSION["success"] = "Data dosen berhasil diperbarui!";
    } catch (Exception $e) {
        $_SESSION["error"] = "Error: " . $e->getMessage();
    }

    header("Location: dataLecturer.php");
    exit;
}

function updateLecturerView()
{
    global $data;

    $userId = htmlspecialchars($_POST["ubahView"]); // Mengambil UserId dari form

    try {
        $connection = getConnection(); // Mengasumsikan Anda memiliki fungsi bernama getConnection() untuk membuat koneksi PDO

        // Query untuk mengambil informasi dosen
        $stmt = $connection->prepare("SELECT Name, UserId, Email FROM Users WHERE UserId = :userId");
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();

        // Ambil data dosen
        $data["lecturer"] = $stmt->fetch(PDO::FETCH_ASSOC);

        // Periksa apakah data dosen ditemukan
        if (!$data["lecturer"]) {
            throw new Exception("Data dosen tidak ditemukan!");
        }
    } catch (Exception $e) {
        $_SESSION["error"] = "Error: " . $e->getMessage();

        return;
    }
}
