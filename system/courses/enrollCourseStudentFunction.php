<?php
session_start();
require_once ("../../helper/dbHelper.php");
require_once ("../../helper/authHelper.php");
$permittedRole = ["lecturer", "admin"];
$pageName = "Sistem Absensi UPH - Enroll Mata Kuliah 'Kode MK'";
$data = [];
if (!authorization($permittedRole, $_SESSION["UserId"])) {
    header('location: ../auth/login.php');
}





if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset ($_GET["CourseId"])) {
    enrollCourseStudentView();
} else {
    $_SESSION["error"] = "Tidak menemukan permintaan yang valid!";
    header("location: dataCourse.php");
    exit;
}

function enrollCourseStudentView()
{
    global $data;

    // Check if CourseId is provided in the URL
    if (!isset ($_GET["CourseId"])) {
        $_SESSION["error"] = "CourseId is missing.";
        header("location: dataCourse.php");
        exit;
    }

    // Sanitize and retrieve CourseId
    $courseId = htmlspecialchars($_GET["CourseId"]);

    // Database connection settings
    $connection = getConnection();

    try {
        // Check if the courseId exists in the Courses table
        $sql_check_course = "SELECT * FROM Courses WHERE CourseId = :courseId";
        $stmt_check_course = $connection->prepare($sql_check_course);
        $stmt_check_course->bindParam(':courseId', $courseId);
        $stmt_check_course->execute();

        // Fetch the result
        $course = $stmt_check_course->fetch(PDO::FETCH_ASSOC);

        // Check if the course exists
        if (!$course) {
            $_SESSION["error"] = "Course with CourseId $courseId does not exist.";
            header("location: dataCourse.php");
            exit;
        }

        // Prepare SQL query to retrieve students and their enrollment status for the course
        $sql = "SELECT u.Name, u.StudentId, s.YearIn, 
        CASE WHEN e.StudentId IS NOT NULL THEN 1 ELSE 0 END AS EnrollmentStatus
        FROM users u
        LEFT JOIN students s ON u.StudentId = s.StudentId
        LEFT JOIN enrollments e ON u.StudentId = e.StudentId AND e.CourseId = :courseId
        WHERE u.role = 0";



        // Prepare and execute the query
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(':courseId', $courseId);
        $stmt->execute();

        // Fetch all rows as an associative array
        $data["students"] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Close the connection
        $connection = null;
    } catch (PDOException $e) {
        // Handle query execution errors
        $_SESSION["error"] = "Query failed: " . $e->getMessage();
        header("location: dataCourse.php");
        exit;
    }
}