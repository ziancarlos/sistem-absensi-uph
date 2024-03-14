<?php
session_start();
require_once("../../helper/dbHelper.php");
require_once("../../helper/authHelper.php");
$permittedRole = ["lecturer", "admin"];
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
        $nip = htmlspecialchars($_POST["nip"]);
        $new_nip = htmlspecialchars($_POST["new_nip"]);
        $email = htmlspecialchars($_POST["email"]);
        $password = htmlspecialchars($_POST["password"]);


        // Cek apakah ada perubahan dalam data
        $connection = getConnection(); // Mengasumsikan Anda memiliki fungsi bernama getConnection() untuk membuat koneksi PDO

        // Query untuk mendapatkan data dosen sebelum perubahan
        $stmt = $connection->prepare("SELECT * FROM Users WHERE UserId = :nip");
        $stmt->bindParam(':nip', $nip);
        $stmt->execute();

        // Periksa apakah ada hasil yang dikembalikan
        $existingLecturer = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$existingLecturer) {
            throw new Exception("Data dosen tidak ditemukan!");
        }

        // Bandingkan nilai-nilai yang baru dengan nilai-nilai yang ada dalam database
        if ($existingLecturer['Name'] === $name && $existingLecturer['UserId'] === $nip && $existingLecturer['Email'] === $email && empty($password)) {
            throw new Exception("Tidak ada perubahan yang dilakukan!");
        }

        // Lakukan perubahan jika ada perubahan dalam data
        $updateUserStmt = $connection->prepare("UPDATE Users SET Name = :name, Email = :email, UserId = :new_nip WHERE UserId = :nip");
        $updateUserStmt->bindParam(':name', $name);
        $updateUserStmt->bindParam(':email', $email);
        $updateUserStmt->bindParam(':new_nip', $nip); // Update NIP juga
        $updateUserStmt->bindParam(':nip', $existingLecturer['UserId']);
        $updateUserStmt->execute();

        // Jika password tidak kosong, update password
        if (!empty($password)) {
            $hashedPassword = md5($password);
            $updatePasswordStmt = $connection->prepare("UPDATE Users SET Password = :password WHERE UserId = :nip");
            $updatePasswordStmt->bindParam(':password', $hashedPassword);
            $updatePasswordStmt->bindParam(':nip', $existingLecturer['UserId']);
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

    $nip = htmlspecialchars($_POST["ubahView"]);

    try {
        $connection = getConnection(); // Assuming you have a function named getConnection() to establish a PDO connection

        // Query to fetch the required information from tables
        $stmt = $connection->prepare("SELECT Name, UserId, Email FROM Users WHERE UserId = :nip");
        $stmt->bindParam(':nip', $nip);
        $stmt->execute();

        $data["lecturer"] = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            throw new Exception("Data dosen tidak ditemukan!");
        }
    } catch (Exception $e) {
        $_SESSION["error"] = "Error: " . $e->getMessage();

        return;
    }
}