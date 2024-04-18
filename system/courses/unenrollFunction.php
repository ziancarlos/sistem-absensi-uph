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
        $stmt = $connection->prepare("SELECT CourseId, StudentId FROM enrollments WHERE EnrollmentId = :enrollmentId");
        $stmt->bindParam(":enrollmentId", $enrollmentId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            $_SESSION["error"] = "Pendaftaran tidak ditemukan.";
            header("Location: dataCourse.php");
            exit;
        }

        $courseId = $result["CourseId"];
        $studentId = $result["StudentId"];

        // Check the student's status from the 'users' table
        $statusQuery = "SELECT users.Status FROM students
                        INNER JOIN users ON students.StudentId = users.StudentId
                        WHERE students.StudentId = :studentId";
        $statusStmt = $connection->prepare($statusQuery);
        $statusStmt->bindParam(":studentId", $studentId);
        $statusStmt->execute();
        $statusResult = $statusStmt->fetch(PDO::FETCH_ASSOC);

        // If the user's status is not 1 (active), return an error message
        if ($statusResult['Status'] !== 1) {
            $_SESSION["error"] = "Mahasiswa tidak aktif.";
            header("Location: enrollCourseStudent.php?CourseId=" . $courseId);
            exit;
        }

        // Prepare and execute SQL query to delete enrollment
        $stmt = $connection->prepare("DELETE FROM enrollments WHERE EnrollmentId = :enrollmentId");
        $stmt->bindParam(":enrollmentId", $enrollmentId);

        // Check the execution of the statement and redirect based on the outcome
        if ($stmt->execute()) {
            // Success message in Indonesian
            $_SESSION["success"] = "Pendaftaran berhasil dihapus.";
            header("Location: enrollCourseStudent.php?CourseId=" . $courseId);
            exit;
        } else {
            // Error message in Indonesian
            $_SESSION["error"] = "Gagal menghapus pendaftaran.";
            header("Location: enrollCourseStudent.php?CourseId=" . $courseId);
            exit;
        }
    } catch (Exception $e) {
        // Error message in Indonesian
        $_SESSION["error"] = "Error: " . $e->getMessage();
        header("Location: enrollCourseStudent.php?CourseId=" . $courseId);
        exit;
    }
}