<?php
session_start();
require_once ("../../helper/dbHelper.php");
require_once ("../../helper/authHelper.php");
$permittedRole = ["admin"];
$pageName = "Sistem Absensi UPH - Edit Admin";
$data = [];
if (!authorization($permittedRole, $_SESSION["UserId"])) {
    header('location: ../auth/login.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["ubahView"])) {
    updateAdminView();

    if ($data == null) {
        $_SESSION["error"] = "Data tidak dapat diambil, Hubungi admin jika masalah ini terus terjadi!";

        header("location: dataAdmin.php");
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["ubah"])) {
    updateAdminController();
}


function updateAdminController()
{
    try {
        // Ambil nilai-nilai yang dikirimkan dalam permintaan POST
        $name = htmlspecialchars($_POST["name"]);
        $email = htmlspecialchars($_POST["email"]);
        $password = htmlspecialchars($_POST["password"]);
        $userId = htmlspecialchars($_POST["UserId"]); // Mengambil UserId dari form

        // Validasi email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Alamat email tidak valid!");
        }

        // Validasi password minimum 8 karakter
        if (!empty($password) && strlen($password) < 8) {
            throw new Exception("Password harus memiliki minimal 8 karakter!");
        }

        // Cek apakah ada perubahan dalam data
        $connection = getConnection(); // Mengasumsikan Anda memiliki fungsi bernama getConnection() untuk membuat koneksi PDO

        // Query untuk mendapatkan data dosen sebelum perubahan
        $stmt = $connection->prepare("SELECT * FROM users WHERE UserId = :userId");
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();

        // Periksa apakah ada hasil yang dikembalikan
        $existingAdmin = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$existingAdmin) {
            throw new Exception("Data dosen tidak ditemukan!");
        }

        // Bandingkan nilai-nilai yang baru dengan nilai-nilai yang ada dalam database
        if ($existingAdmin['Name'] === $name && $existingAdmin['Email'] === $email && empty($password)) {
            throw new Exception("Tidak ada perubahan yang dilakukan!");
        }

        // Lakukan pengecekan apakah nama atau email sudah ada dalam database
        $nameCheckQuery = "SELECT * FROM users WHERE Name = :name AND UserId != :userId";
        $emailCheckQuery = "SELECT * FROM users WHERE Email = :email AND UserId != :userId";

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
        $updateUserStmt = $connection->prepare("UPDATE users SET Name = :name, Email = :email WHERE UserId = :userId");
        $updateUserStmt->bindParam(':name', $name);
        $updateUserStmt->bindParam(':email', $email);
        $updateUserStmt->bindParam(':userId', $userId);
        $updateUserStmt->execute();

        // Jika password tidak kosong, update password
        if (!empty($password)) {
            $hashedPassword = md5($password);
            $updatePasswordStmt = $connection->prepare("UPDATE users SET Password = :password WHERE UserId = :userId");
            $updatePasswordStmt->bindParam(':password', $hashedPassword);
            $updatePasswordStmt->bindParam(':userId', $userId);
            $updatePasswordStmt->execute();
        }

        $_SESSION["success"] = "Data admin berhasil diperbarui!";
    } catch (Exception $e) {
        $_SESSION["error"] = "Error: " . $e->getMessage();
    }

    header("Location: dataAdmin.php");
    exit;
}


function updateAdminView()
{
    global $data;

    $userId = htmlspecialchars($_POST["ubahView"]); // Mengambil UserId dari form

    try {
        $connection = getConnection(); // Mengasumsikan Anda memiliki fungsi bernama getConnection() untuk membuat koneksi PDO

        // Query untuk mengambil informasi dosen
        $stmt = $connection->prepare("SELECT Name, UserId, Email FROM users WHERE UserId = :userId");
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();

        // Ambil data dosen
        $data["admin"] = $stmt->fetch(PDO::FETCH_ASSOC);

        // Periksa apakah data dosen ditemukan
        if (!$data["admin"]) {
            throw new Exception("Data admin tidak ditemukan!");
        }
    } catch (Exception $e) {
        $_SESSION["error"] = "Error: " . $e->getMessage();

        return;
    }
}