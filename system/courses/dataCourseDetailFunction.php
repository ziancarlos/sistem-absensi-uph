<?php
session_start();
require_once ("../../helper/dbHelper.php");
require_once ("../../helper/authHelper.php");
$permittedRole = ["lecturer", "admin", "student"];
$pageName = "Sistem Absensi UPH - List Mahasiswa - Enroll";
$data = [];
if (!authorization($permittedRole, $_SESSION["UserId"])) {
    header('location: ../auth/login.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET["CourseId"])) {
    courseDetailView();
} else {
    $_SESSION["error"] = "Tidak menemukan permintaan yang valid!";
    header("location: dataCourse.php");
    exit;
}

function courseDetailView()
{
    global $data;

    // Check if CourseId is provided in the URL
    if (!isset($_GET["CourseId"])) {
        $_SESSION["error"] = "CourseId tidak ditemukan.";
        header("location: dataCourse.php");
        exit;
    }

    // Sanitize and retrieve CourseId
    $courseId = htmlspecialchars($_GET["CourseId"]);

    // Database connection settings
    $connection = getConnection();

    // Check if the courseId exists in the Courses table
    $sql_check_course = "SELECT * FROM Courses WHERE CourseId = :courseId";

    try {
        // Prepare and execute the query
        $stmt_check_course = $connection->prepare($sql_check_course);
        $stmt_check_course->bindParam(':courseId', $courseId);
        $stmt_check_course->execute();

        // Fetch the result
        $course = $stmt_check_course->fetch(PDO::FETCH_ASSOC);

        // Check if the course exists
        if (!$course) {
            $_SESSION["error"] = "Mata kuliah dengan CourseId $courseId tidak ditemukan.";
            header("location: dataCourse.php");
            exit;
        }

        // Prepare SQL query to retrieve lecturers associated with the course
        $sql_lecturers = "SELECT u.UserId, u.Name, u.Email, u.Status 
                FROM users u 
                INNER JOIN lecturerhascourses lhc ON u.UserId = lhc.LecturerId
                WHERE lhc.CourseId = :courseId AND u.status = 1";

        // Prepare and execute the query for lecturers
        $stmt_lecturers = $connection->prepare($sql_lecturers);
        $stmt_lecturers->bindParam(':courseId', $courseId);
        $stmt_lecturers->execute();

        // Fetch all lecturers as an associative array
        $data["lecturers"] = $stmt_lecturers->fetchAll(PDO::FETCH_ASSOC);

        // Prepare SQL query to retrieve students enrolled in the course
        $sql_students = "SELECT enrollments.EnrollmentId, enrollments.Status AS EnrollmentStatus, users.Name, users.Email, enrollments.StudentId, students.YearIn 
                FROM users
                INNER JOIN enrollments  ON users.StudentId = enrollments.StudentId
                INNER JOIN students ON enrollments.StudentId = students.StudentId
                WHERE users.role = 0 AND enrollments.CourseId = :courseId AND users.Status = 1";

        // Prepare and execute the query for students
        $stmt_students = $connection->prepare($sql_students);
        $stmt_students->bindParam(':courseId', $courseId);
        $stmt_students->execute();

        // Fetch all students as an associative array
        $data["students"] = $stmt_students->fetchAll(PDO::FETCH_ASSOC);

        // Close the connection
        $connection = null;
    } catch (PDOException $e) {
        // Handle query execution errors
        $_SESSION["error"] = "Pencarian gagal: " . $e->getMessage();
        header("location: dataCourse.php");
        exit;
    }
}
?>