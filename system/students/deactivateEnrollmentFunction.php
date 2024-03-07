<?php

session_start();
require_once("../../helper/dbHelper.php");
require_once("../../helper/authHelper.php");

$permittedRole = ["lecturer", "admin"];

if (!authorization($permittedRole, $_SESSION["UserId"])) {
    header('location: ../auth/login.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["deactivate"])) {
    deactivateStudentController();
} else {
    $_SESSION["error"] = "Tidak menemukan permintaan yang valid!";
    header("location: dataStudent.php");
    exit;
}

function deactivateStudentController()
{
    try {
        $enrollmentId = htmlspecialchars($_POST["deactivate"]);
        $connection = getConnection();

        $stmt = $connection->prepare("SELECT Status FROM enrollments WHERE EnrollmentId = :enrollmentId");
        $stmt->bindParam(':enrollmentId', $enrollmentId);
        $stmt->execute();

        $enrollment = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($enrollment) {
            if ($enrollment['Status'] == 0) {
                throw new Exception("Enrollment sudah tidak aktif!");
            }

            $updateStmt = $connection->prepare("UPDATE enrollments SET Status = 0 WHERE EnrollmentId = :enrollmentId");
            $updateStmt->bindParam(':enrollmentId', $enrollmentId);
            $updateStmt->execute();

            $_SESSION["success"] = "Enrollment berhasil dinonaktifkan!";
        } else {
            throw new Exception("ID Enrollment tidak valid!");
        }

        header("Location: dataStudent.php");
        exit;
    } catch (Exception $e) {
        $_SESSION["error"] = "Error: " . $e->getMessage();
        header("Location: dataStudent.php");
        exit;
    }
}
?>