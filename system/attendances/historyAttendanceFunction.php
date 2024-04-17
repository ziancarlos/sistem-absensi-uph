<?php
session_start();
require_once("../../helper/dbHelper.php");
require_once("../../helper/authHelper.php");

$permittedRole = ["admin", "lecturer", "student"];
$pageName = "Sistem Absensi UPH - Histori Absensi";
$data = [];

// Memeriksa izin akses pengguna
if (!authorization($permittedRole, $_SESSION["UserId"])) {
    header('location: ../auth/logout.php');
}

// Memproses pencarian jika tombol "Cari" diklik
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET["inputKodeMK"]) && isset($_GET["inputTanggal"])) {
    $kodeMK = $_GET["inputKodeMK"];
    $tanggal = $_GET["inputTanggal"];
    filterAttendanceData($kodeMK, $tanggal);
} else {
    // Jika pencarian tidak dilakukan, tampilkan semua data absensi
    dataAttendanceView();
}

/**
 * Fungsi untuk melakukan filter data absensi berdasarkan form pencarian
 */
function filterAttendanceData($kodeMK = null, $tanggal = null)
{
    global $data;
    $connection = getConnection();

    try {
        $sql = "
        SELECT   
        students.StudentId,
        attendances.ScheduleId,     
        users.Name, 
        courses.Code, 
        courses.Name AS ClassName, 
        CONCAT(buildings.Letter, classrooms.Code) AS Room, 
        schedules.Date, schedules.StartTime, schedules.EndTime,
        DATE_FORMAT(attendances.FingerprintTimeIn, '%H:%i:%s') AS TimeIn,
        attendances.StudentId,
        attendances.Status 
    FROM 
        attendances 
    LEFT JOIN 
        students ON attendances.StudentId = students.StudentId 
    LEFT JOIN 
        users ON students.StudentId = users.StudentId 
    LEFT JOIN 
        schedules ON attendances.ScheduleId = schedules.ScheduleId 
    LEFT JOIN 
        courses ON schedules.CourseId = courses.CourseId 
    LEFT JOIN 
        classrooms ON courses.ClassroomId = classrooms.ClassroomId 
    LEFT JOIN 
        buildings ON classrooms.BuildingId = buildings.BuildingId

        WHERE 1 ";

        if (!empty($kodeMK)) {
            $sql .= " AND courses.Code = :kodeMK ";
        }

        if (!empty($tanggal)) {
            $sql .= " AND DATE_FORMAT(DATE(schedules.Date), '%Y-%m-%d') = :tanggal ";
        }

        $stmt = $connection->prepare($sql);

        if (!empty($kodeMK)) {
            $stmt->bindParam(':kodeMK', $kodeMK);
        }

        if (!empty($tanggal)) {
            $stmt->bindParam(':tanggal', $tanggal);
        }

        $stmt->execute();
        $attendances = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Populate data array with all attendance information
        $data['attendances'] = $attendances;
    } catch (Exception $e) {
        $_SESSION["error"] = $e->getMessage();
        header("location: historyAttendance.php");
        exit;
    }
}

// Fungsi untuk menampilkan semua data absensi
function dataAttendanceView()
{
    global $data;
    $connection = getConnection();

    try {
        // Mendapatkan role pengguna dari session
        $role = getUserRole($_SESSION["UserId"]);

        // Query untuk menampilkan data absensi
        $sql = "
        SELECT        
            students.StudentId,
            attendances.ScheduleId,    
            users.Name, 
            courses.Code, 
            courses.Name AS ClassName, 
            CONCAT(buildings.Letter, classrooms.Code) AS Room, 
            schedules.Date, schedules.StartTime, schedules.EndTime,
            DATE_FORMAT(attendances.FingerprintTimeIn, '%H:%i:%s') AS TimeIn,
            attendances.StudentId,
            attendances.Status 
        FROM 
            attendances 
        LEFT JOIN 
            students ON attendances.StudentId = students.StudentId 
        LEFT JOIN 
            users ON students.StudentId = users.StudentId 
        LEFT JOIN 
            schedules ON attendances.ScheduleId = schedules.ScheduleId 
        LEFT JOIN 
            courses ON schedules.CourseId = courses.CourseId 
        LEFT JOIN 
            classrooms ON courses.ClassroomId = classrooms.ClassroomId 
        LEFT JOIN 
            buildings ON classrooms.BuildingId = buildings.BuildingId";

        // Jika role adalah "student", tambahkan kondisi where
        if ($role === "student") {
            $sql .= " WHERE users.UserId = :userId";
        }

        $stmt = $connection->prepare($sql);

        // Bind parameter jika role adalah "student"
        if ($role === "student") {
            $stmt->bindParam(':userId', $_SESSION["UserId"]);
        }

        $stmt->execute();
        $attendances = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Populate data array with all attendance information
        $data['attendances'] = $attendances;
    } catch (Exception $e) {
        $_SESSION["error"] = $e->getMessage();
        header("location: historyAttendance.php");
        exit;
    }
}
?>
