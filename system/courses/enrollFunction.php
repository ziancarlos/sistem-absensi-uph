<?php
session_start();
require_once ("../../helper/dbHelper.php");
require_once ("../../helper/authHelper.php");

$permittedRole = ["admin"];

if (!authorization($permittedRole, $_SESSION["UserId"])) {
    header('location: ../auth/logout.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset ($_GET["StudentId"]) && isset ($_GET["CourseId"])) {
    enrollController();
} else {
    $_SESSION["error"] = "Tidak menemukan permintaan yang valid!";
    header("location: dataCourse.php");
    exit;
}

function enrollController()
{
    try {
        $studentId = htmlspecialchars($_GET["StudentId"]);
        $courseId = htmlspecialchars($_GET["CourseId"]);
        $connection = getConnection();

        // Check if there's an existing enrollment for the student and course
        $query = "SELECT * FROM enrollments WHERE StudentId = :studentId AND CourseId = :courseId";
        $stmt = $connection->prepare($query);
        $stmt->bindParam("studentId", $studentId); // Corrected binding
        $stmt->bindParam("courseId", $courseId); // Corrected binding
        $stmt->execute();
        $result = $stmt->rowCount();

        if ($result > 0) {
            // Enrollment already exists, send error message
            $_SESSION["error"] = "Pendaftaran untuk mahasiswa ini dalam kursus yang dipilih sudah ada.";
            header("Location: enrollCourseStudent.php?CourseId=" . $courseId);
            exit;
        } else {
            // No existing enrollment, proceed with insertion
            $insertQuery = "INSERT INTO enrollments (StudentId, CourseId) VALUES (:studentId, :courseId)";
            $insertStmt = $connection->prepare($insertQuery);
            $insertStmt->bindParam("studentId", $studentId); // Corrected binding
            $insertStmt->bindParam("courseId", $courseId); // Corrected binding
            $insertStmt->execute();


            $_SESSION["success"] = "Sukses mendaftarkan mahasiswa pada mata kuliah ini.";

            // Redirect to a success page or wherever needed
            header("Location: enrollCourseStudent.php?CourseId=" . $courseId);
            exit;
        }
    } catch (Exception $e) {
        $_SESSION["error"] = "Error: " . $e->getMessage();
        header("Location: enrollCourseStudent.php?CourseId=" . $courseId);
        exit;
    }
}