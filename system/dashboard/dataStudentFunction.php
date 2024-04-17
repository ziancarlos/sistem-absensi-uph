<?php
session_start();
require_once("../../helper/dbHelper.php");
require_once("../../helper/authHelper.php");
$permittedRole = ["lecturer", "admin"];
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
        SELECT students.StudentId, users.Name, 
            SUM(CASE WHEN attendances.Status = 1 THEN 1 ELSE 0 END) AS AttendanceCount,
            SUM(CASE WHEN attendances.Status = 0 THEN 1 ELSE 0 END) AS AbsenceCount
            FROM attendances
            INNER JOIN users ON attendances.StudentId = users.StudentId
            INNER JOIN students ON attendances.StudentId = students.StudentId
            INNER JOIN schedules ON attendances.ScheduleId = schedules.ScheduleId
            WHERE  schedules.Date <= CURDATE()
            GROUP BY attendances.StudentId
            ORDER BY COUNT(*) DESC;  
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
