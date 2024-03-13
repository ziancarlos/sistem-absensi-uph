<?php
session_start();
require_once("../../helper/dbHelper.php");
require_once("../../helper/authHelper.php");

$permittedRole = ["lecturer", "admin"];

if (!authorization($permittedRole, $_SESSION["UserId"])) {
    header('location: ../auth/login.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["activate"])) {
    activateStudentController();
} else {
    $_SESSION["error"] = "Tidak menemukan permintaan yang valid!";

    header("location: dataLecturer.php");
    exit;
}

function activateStudentController()
{
    try {
        $userId = htmlspecialchars($_POST["activate"]);

        $connection = getConnection(); // Mengasumsikan Anda memiliki fungsi bernama getConnection() untuk membuat koneksi PDO

        $stmt = $connection->prepare("SELECT Status FROM Users WHERE UserId = :userId");
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if ($user['Status'] == 1) {
                throw new Exception("Pengguna sudah aktif!");
            }

            $updateStmt = $connection->prepare("UPDATE Users SET Status = 1 WHERE UserId = :userId");
            $updateStmt->bindParam(':userId', $userId);
            $updateStmt->execute();

            $_SESSION["success"] = "Pengguna berhasil diaktifkan kembali!";
        } else {
            throw new Exception("ID pengguna tidak valid!");
        }

        header("Location: dataLecturer.php");
        exit;
    } catch (Exception $e) {
        $_SESSION["error"] = "Error: " . $e->getMessage();
        header("Location: dataLecturer.php");
        exit;
    }
}
?>