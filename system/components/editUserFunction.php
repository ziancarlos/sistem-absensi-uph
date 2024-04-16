<?php
session_start();
require_once("../../helper/dbHelper.php");
require_once("../../helper/authHelper.php");

$permittedRole = ["student", "lecturer", "admin"];
$pageName = "Sistem Absensi UPH - Edit Profil";
$data = [];

if (!authorization($permittedRole, $_SESSION["UserId"])) {
    header('location: ../auth/login.php');
}

// Fungsi untuk mendapatkan informasi pengguna berdasarkan UserId
function getUserById($userId) {
    $connection = getConnection();
    $statement = null;

    try {
        $sql = "SELECT Name, Email, Password FROM Users WHERE UserId = :userId";
        $statement = $connection->prepare($sql);
        $statement->bindParam(':userId', $userId);
        $statement->execute();
        $user = $statement->fetch(PDO::FETCH_ASSOC);
        return $user;
    } catch (PDOException $e) {
        // Tangani kesalahan jika terjadi
        return null;
    }
}

// Fungsi untuk mengupdate informasi pengguna ke dalam database
function updateUser($userId, $name, $email, $password) {
    $connection = getConnection();

    try {
        // Enkripsi password sebelum menyimpannya ke dalam database
        $hashedPassword = md5($password);

        $sql = "UPDATE Users SET Name = :name, Email = :email, Password = :password WHERE UserId = :userId";
        $statement = $connection->prepare($sql);
        $statement->bindParam(':name', $name);
        $statement->bindParam(':email', $email);
        $statement->bindParam(':password', $hashedPassword);
        $statement->bindParam(':userId', $userId);
        $statement->execute();
        return true; // Return true jika pembaruan berhasil
    } catch (PDOException $e) {
        // Tangani kesalahan jika terjadi
        return false; // Return false jika terjadi kesalahan saat pembaruan
    }
}

// Memperbarui informasi pengguna
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Memastikan informasi pengguna tersedia
    $user = getUserById($_SESSION["UserId"]);
    if ($user) {
        $updateResult = updateUser($_SESSION["UserId"], $_POST['name'], $_POST['email'], $_POST['password']); // Memperbarui informasi pengguna
        if ($updateResult) {
            $_SESSION['success'] = "Informasi pengguna berhasil diperbarui";
        } else {
            $_SESSION['error'] = "Gagal memperbarui informasi pengguna";
        }
    } else {
        $_SESSION['error'] = "Informasi pengguna tidak ditemukan";
    }

    // Redirect kembali ke halaman editUser.php
    header('location: editUser.php');
} else {
    // Redirect jika metode request bukan POST
    header('location: editUser.php');
}
