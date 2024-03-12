<?php
session_start();
require_once("../../helper/dbHelper.php");
require_once("../../helper/authHelper.php");

$permittedRole = ["admin"];

if (!authorization($permittedRole, $_SESSION["UserId"])) {
    header('location: ../auth/logout.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["activate"])) {
    activateCourseController();
} else {
    $_SESSION["error"] = "Tidak menemukan permintaan yang valid!";
    header("location: dataCourse.php");
    exit;
}

function activateCourseController()
{
    try {
        $courseId = htmlspecialchars($_POST["activate"]);
        $connection = getConnection();

        $stmt = $connection->prepare("SELECT Status FROM courses WHERE CourseId = :courseId");
        $stmt->bindParam(':courseId', $courseId);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if ($user['Status'] == 1) {
                throw new Exception("Mata Kuliah sudah aktif!");
            }

            $updateStmt = $connection->prepare("UPDATE courses SET Status = 1 WHERE CourseId = :courseId");
            $updateStmt->bindParam(':courseId', $courseId);
            $updateStmt->execute();

            $_SESSION["success"] = "Mata Kuliah berhasil diaktifkan!";
        } else {
            throw new Exception("ID Mata Kuliah tidak valid!");
        }

        header("Location: dataCourse.php");
        exit;
    } catch (Exception $e) {
        $_SESSION["error"] = "Error: " . $e->getMessage();
        header("Location: dataCourse.php");
        exit;
    }
}
?>