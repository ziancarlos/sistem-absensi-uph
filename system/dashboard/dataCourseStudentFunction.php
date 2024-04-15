<?php
require_once ("../../helper/dbHelper.php");
require_once ("../../helper/authHelper.php");
$permittedRole = ["student"];
$pageName = "Sistem Absensi UPH - Dashboard";
$data = [];
if (!authorization($permittedRole, $_SESSION["UserId"])) {
    header('location: ../auth/logout.php');
}

dataCourseView();

function dataCourseView()
{
    global $data;
    $userId = $_SESSION["UserId"];
    $connection = getConnection();

    try {
        $stmt = $connection->prepare("
        SELECT 
        schedules.CourseId,
        schedules.Date,
        schedules.StartTime,
        schedules.EndTime, 
        courses.Name, 
        courses.Code, 
        CONCAT(buildings.Letter, classrooms.Code) AS Room, 
        enrollments.Status AS EnrollmentStatus,
        MIN(schedules.Date) AS EarliestSchedule,
        MAX(schedules.Date) AS LatestSchedule
    FROM 
        courses
    INNER JOIN 
        classrooms ON courses.ClassroomId = classrooms.ClassroomId
    INNER JOIN 
        buildings ON classrooms.BuildingId = buildings.BuildingId
    INNER JOIN 
        enrollments ON courses.CourseId = enrollments.CourseId
    INNER JOIN 
        users ON enrollments.StudentId = users.StudentId
    LEFT JOIN 
        schedules ON courses.CourseId = schedules.CourseId
    WHERE 
        users.UserId = ?
        AND schedules.Date = CURDATE() -- Filter untuk hari ini
    GROUP BY 
        courses.CourseId, 
        courses.Name, 
        courses.Code, 
        CONCAT(buildings.Letter, classrooms.Code),
        enrollments.Status;
    
        ");
        $stmt->execute([$userId]);
        $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Populate data array with course information
        $data['courses'] = $courses;
    } catch (Exception $e) {
        $_SESSION["error"] = $e->getMessage();
        header("location: dashboard.php");
        exit;
    }
}
?>