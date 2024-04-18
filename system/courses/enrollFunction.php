<?php
session_start();
require_once ("../../helper/dbHelper.php");
require_once ("../../helper/authHelper.php");

$permittedRole = ["admin"];

if (!authorization($permittedRole, $_SESSION["UserId"])) {
    header('location: ../auth/logout.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET["StudentId"]) && isset($_GET["CourseId"])) {
    enrollController();
} else {
    $_SESSION["error"] = "Tidak menemukan permintaan yang valid!";
    header("location: dataCourse.php");
    exit;
}

function enrollController()
{
    try {
        // Get studentId and courseId from the request
        $studentId = htmlspecialchars($_GET["StudentId"]);
        $courseId = htmlspecialchars($_GET["CourseId"]);
        $connection = getConnection();

        // Query to check the student's status
        $statusQuery = "SELECT users.Status FROM students
                        INNER JOIN users ON students.StudentId = users.StudentId
                        WHERE students.StudentId = :studentId";
        $statusStmt = $connection->prepare($statusQuery);
        $statusStmt->bindParam("studentId", $studentId);
        $statusStmt->execute();
        $statusResult = $statusStmt->fetch(PDO::FETCH_ASSOC);

        // Check if the student is active (users.Status = 1)
        if ($statusResult['Status'] !== 1) {
            // If the student is not active, send an error message and exit
            $_SESSION["error"] = "Mahasiswa ini telah tidak aktif.";
            header("Location: enrollCourseStudent.php?CourseId=" . $courseId);
            exit;
        }

        // Check if there's an existing enrollment for the student and course
        $enrollmentQuery = "SELECT * FROM enrollments WHERE StudentId = :studentId AND CourseId = :courseId";
        $enrollmentStmt = $connection->prepare($enrollmentQuery);
        $enrollmentStmt->bindParam("studentId", $studentId);
        $enrollmentStmt->bindParam("courseId", $courseId);
        $enrollmentStmt->execute();
        $enrollmentResult = $enrollmentStmt->rowCount();

        if ($enrollmentResult > 0) {
            // Enrollment already exists, send an error message
            $_SESSION["error"] = "Pendaftaran untuk mahasiswa ini dalam kursus yang dipilih sudah ada.";
            header("Location: enrollCourseStudent.php?CourseId=" . $courseId);
            exit;
        } else {
            // No existing enrollment, proceed with insertion
            $insertQuery = "INSERT INTO enrollments (StudentId, CourseId) VALUES (:studentId, :courseId)";
            $insertStmt = $connection->prepare($insertQuery);
            $insertStmt->bindParam("studentId", $studentId);
            $insertStmt->bindParam("courseId", $courseId);
            $insertStmt->execute();

            // Set success message
            $_SESSION["success"] = "Sukses mendaftarkan mahasiswa pada mata kuliah ini.";

            // Redirect to a success page or wherever needed
            header("Location: enrollCourseStudent.php?CourseId=" . $courseId);
            exit;
        }
    } catch (Exception $e) {
        // Handle any exceptions
        $_SESSION["error"] = "Error: " . $e->getMessage();
        header("Location: enrollCourseStudent.php?CourseId=" . $courseId);
        exit;
    }
}