<?php
session_start();
require_once("../../helper/dbHelper.php");
require_once("../../helper/authHelper.php");
$permittedRole = ["student", "lecturer", "admin"];
$pageName = "Sistem Absensi UPH - Dashboard";
if (!authorization($permittedRole, $_SESSION["UserId"])) {
    header('location: ../auth/logout.php');
}

// Mengambil data kelas paling banyak dihadiri
function getMostAttendedClass() {
    try {
        $conn = getConnection();
        
        $query = "SELECT courses.Name, COUNT(*) as AttendanceCount
                  FROM attendances
                  INNER JOIN schedules ON attendances.ScheduleId = schedules.ScheduleId
                  INNER JOIN courses ON schedules.CourseId = courses.CourseId
                  WHERE attendances.Status = 1 AND schedules.Date <= CURDATE()
                  GROUP BY courses.CourseId
                  ORDER BY COUNT(*) DESC
                  LIMIT 1";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Mengambil data kelas paling jarang dihadiri
function getLeastAttendedClass() {
    try {
        $conn = getConnection();
        $query = "SELECT courses.Name, COUNT(*) as AttendanceCount
                  FROM attendances
                  INNER JOIN schedules ON attendances.ScheduleId = schedules.ScheduleId
                  INNER JOIN courses ON schedules.CourseId = courses.CourseId
                  WHERE attendances.Status = 0 AND schedules.Date <= CURDATE()
                  GROUP BY courses.CourseId
                  ORDER BY COUNT(*) DESC
                  LIMIT 1";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Mengambil data mahasiswa paling rajin
function getMostActiveStudent() {
    try {
        $conn = getConnection();
        $query = "SELECT students.StudentId, users.Name
                  FROM attendances
                    INNER JOIN users ON attendances.StudentId = users.StudentId
                    INNER JOIN students ON attendances.StudentId = students.StudentId
                    INNER JOIN schedules ON attendances.ScheduleId = schedules.ScheduleId
                    WHERE attendances.Status = 1 AND schedules.Date <= CURDATE()
                    GROUP BY attendances.StudentId
                    ORDER BY COUNT(*) DESC";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Mengambil data mahasiswa potensi cekal
function getSuspectStudent() {
    try {
        $conn = getConnection();
        $query = "SELECT students.StudentId, users.Name
                  FROM attendances
                    INNER JOIN users ON attendances.StudentId = users.StudentId
                    INNER JOIN students ON attendances.StudentId = students.StudentId
                    INNER JOIN schedules ON attendances.ScheduleId = schedules.ScheduleId
                    WHERE attendances.Status = 0 AND schedules.Date <= CURDATE()
                    GROUP BY attendances.StudentId
                    ORDER BY COUNT(*) DESC";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Function to get daily attendance data
function getDailyAttendanceData() {
    try {
        $conn = getConnection();
        // Query untuk mengambil data absensi harian
        $query = "SELECT DATE(DATE_SUB(CURRENT_DATE(), INTERVAL seq.seq DAY)) AS Date,
        COALESCE(AttendanceCount, 0) AS AttendanceCount
        FROM (
            SELECT 0 AS seq UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL
            SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6
        ) AS seq
        LEFT JOIN (
            SELECT DATE(CardTimeIn) AS Date,
                    COUNT(*) AS AttendanceCount
            FROM attendances
            WHERE DATE(CardTimeIn) BETWEEN DATE_SUB(CURRENT_DATE(), INTERVAL 6 DAY) AND CURRENT_DATE()
                AND (Status = 1 OR Status IS NULL)
            GROUP BY Date
        ) AS att ON DATE(DATE_SUB(CURRENT_DATE(), INTERVAL seq.seq DAY)) = att.Date
        ORDER BY Date ASC;";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Inisialisasi array untuk menyimpan label dan data absensi harian
        $labels = array();
        $data = array();

        // Loop melalui hasil query dan mengisi array dengan data absensi harian
        foreach ($result as $row) {
            $labels[] = $row['Date']; // Tambahkan tanggal sebagai label
            $data[] = $row['AttendanceCount']; // Tambahkan jumlah kehadiran sebagai data
        }

        // Return array yang berisi label dan data absensi harian
        return array(
            'labels' => $labels,
            'data' => $data
        );
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Function to get monthly attendance data
function getMonthlyAttendanceData() {
    try {
        $conn = getConnection();
        // Query untuk mengambil data absensi bulanan
        $query = "SELECT DATE_FORMAT(CardTimeIn, '%Y-%m') as Month, 
            SUM(CASE WHEN Status = 1 THEN 1 ELSE 0 END) as AttendanceCount 
            FROM attendances 
            WHERE CardTimeIn IS NOT NULL 
            GROUP BY DATE_FORMAT(CardTimeIn, '%Y-%m') 
            ORDER BY Month ASC;";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Inisialisasi array untuk menyimpan label dan data absensi bulanan
        $labels = array();
        $data = array();

        // Loop melalui hasil query dan mengisi array dengan data absensi bulanan
        foreach ($result as $row) {
            $labels[] = $row['Month']; // Tambahkan bulan sebagai label
            $data[] = $row['AttendanceCount']; // Tambahkan jumlah kehadiran sebagai data
        }

        // Return array yang berisi label dan data absensi bulanan
        return array(
            'labels' => $labels,
            'data' => $data
        );
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

?>
