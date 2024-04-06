<?php
session_start();
require_once ("../../helper/dbHelper.php");
require_once ("../../helper/authHelper.php");

$permittedRole = ["admin"];

if (!authorization($permittedRole, $_SESSION["UserId"])) {
    header('location: ../auth/logout.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET["enrollmentId"])) {
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

        // Prepare and execute SQL query to fetch CourseId associated with the enrollment
        $stmt = $connection->prepare("SELECT CourseId FROM enrollments WHERE EnrollmentId = :i");
        $stmt->bindParam(":i", $enrollmentId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            $_SESSION["error"] = "Enrollment not found.";
            header("Location: dataCourse.php");
            exit;
        }

        $courseId = $result["CourseId"];

        // Prepare and execute SQL query to delete enrollment
        $stmt = $connection->prepare("DELETE FROM enrollments WHERE EnrollmentId = :i");
        $stmt->bindParam("i", $enrollmentId);

        if ($stmt->execute()) {
            $_SESSION["success"] = "Enrollment successfully deleted.";
            header("Location: enrollCourseStudent.php?CourseId=" . $courseId);
            exit;
        } else {
            $_SESSION["error"] = "Failed to delete enrollment.";
            header("Location: enrollCourseStudent.php?CourseId=" . $courseId);
            exit;
        }
    } catch (Exception $e) {
        $_SESSION["error"] = "Error: " . $e->getMessage();
        header("Location: enrollCourseStudent.php?CourseId=" . $courseId);
        exit;
    }
}