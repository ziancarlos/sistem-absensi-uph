<?php
session_start();
require_once ("../../helper/dbHelper.php");
require_once ("../../helper/authHelper.php");

$permittedRole = ["admin"];

if (!authorization($permittedRole, $_SESSION["UserId"])) {
    header('location: ../auth/logout.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset ($_GET["enrollmentId"])) {
    unenrollController();
} else {
    $_SESSION["error"] = "Tidak menemukan permintaan yang valid!";
    header("location: dataCourse.php");
    exit;
}

function unenrollController()
{
    try {
        $enrollmentId = htmlspecialchars($_GET["enrollmentId"]);
        $connection = getConnection();

        // Prepare and execute SQL query to delete enrollment
        $stmt = $connection->prepare("DELETE FROM enrollments WHERE EnrollmentId = :i");
        $stmt->bindParam("i", $enrollmentId);

        if ($stmt->execute()) {
            $_SESSION["success"] = "Enrollment successfully deleted.";
            header("Location: dataCourse.php");
            exit;
        } else {
            $_SESSION["error"] = "Failed to delete enrollment.";
            header("Location: dataCourse.php");
            exit;
        }
    } catch (Exception $e) {
        $_SESSION["error"] = "Error: " . $e->getMessage();
        header("Location: dataCourse.php");
        exit;
    }
}