<?php
session_start();
require_once("../../helper/dbHelper.php");
require_once("../../helper/authHelper.php");

$permittedRole = ["admin"];

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

        $stmt = $connection->prepare("SELECT Status FROM courses WHERE Code = :CourseCode");
        $stmt->bindParam(':CourseCode', $userId);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if ($user['Status'] == 1) {
                throw new Exception("Pengguna sudah aktif!");
            }

            $updateStmt = $connection->prepare("UPDATE courses SET Status = 1 WHERE Code = :CourseCode");
            $updateStmt->bindParam(':CourseCode', $userId);
            $updateStmt->execute();

            $_SESSION["success"] = "Mata kuliah berhasil diaktifkan kembali!";
        } else {
            throw new Exception("Mata kuliah tidak valid!");
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