<?php
session_start();
require_once("../../helper/dbHelper.php");
require_once("../../helper/authHelper.php");

$permittedRole = ["admin"];

if (!authorization($permittedRole, $_SESSION["UserId"])) {
    header('location: ../auth/login.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["deactivate"])) {
    deactivateLecturerController();
} else {
    $_SESSION["error"] = "Tidak menemukan permintaan yang valid!";
    header("location: dataLecturer.php");
    exit;
}

function deactivateLecturerController()
{
    try {
        $userId = htmlspecialchars($_POST["deactivate"]);
        $connection = getConnection();

        $stmt = $connection->prepare("SELECT Status FROM users WHERE UserId = :userId");
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if ($user['Status'] == 0) {
                throw new Exception("Pengguna sudah tidak aktif!");
            }

            $updateStmt = $connection->prepare("UPDATE users SET Status = 0 WHERE UserId = :userId");
            $updateStmt->bindParam(':userId', $userId);
            $updateStmt->execute();

            $_SESSION["success"] = "Pengguna berhasil dinonaktifkan!";
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