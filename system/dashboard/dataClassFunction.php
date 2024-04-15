<?php
session_start();
require_once("../../helper/dbHelper.php");
require_once("../../helper/authHelper.php");
$permittedRole = ["student", "lecturer", "admin"];
$pageName = "Sistem Absensi UPH - Dashboard";
$data = [];
if (!authorization($permittedRole, $_SESSION["UserId"])) {
    header('location: ../auth/logout.php');
}

// Panggil fungsi untuk mendapatkan data kelas paling banyak dihadiri
dataMostAttendedView();

function dataMostAttendedView()
{
    global $data;
    $connection = getConnection();

    try {
        $stmt = $connection->query("
            SELECT 
                courses.Name, courses.Code,
                SUM(CASE WHEN attendances.Status = 1 THEN 1 ELSE 0 END) AS AttendanceCount,
                SUM(CASE WHEN attendances.Status = 0 THEN 1 ELSE 0 END) AS AbsenceCount
            FROM 
                attendances
            INNER JOIN 
                schedules ON attendances.ScheduleId = schedules.ScheduleId
            INNER JOIN 
                courses ON schedules.CourseId = courses.CourseId
            WHERE 
                schedules.Date <= CURDATE()
            GROUP BY 
                courses.CourseId, courses.Name
            ORDER BY 
                AttendanceCount DESC;  
        ");
        $attendance = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Populate data array with all attendance information
        $data['attendances'] = $attendance;
    } catch (Exception $e) {
        $_SESSION["error"] = $e->getMessage();
        header("location: dashboard.php");
        exit;
    }
}
?>
