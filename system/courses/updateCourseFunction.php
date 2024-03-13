<?php
session_start();
require_once("../../helper/dbHelper.php");
require_once("../../helper/authHelper.php");
$permittedRole = ["lecturer", "admin"];
$pageName = "Sistem Absensi UPH - Edit Mahasiswa";
$data = [];
if (!authorization($permittedRole, $_SESSION["UserId"])) {
    header('location: ../auth/logout.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["edit"])) {
    updateCourseView();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["update"])) {
    updateCourseController();
}


function updateCourseController()
{
    global $data;
    $courseId = htmlspecialchars($_POST["update"]);
    $courseName = htmlspecialchars($_POST["name"]);
    $courseCode = htmlspecialchars($_POST["kode"]);
    $classroomId = htmlspecialchars($_POST["ruang"]);

    // Validate input
    if (empty($courseName) || empty($courseCode) || empty($classroomId)) {
        $_SESSION["error"] = "Error: Semua kolom harus diisi";
        header('location: dataCourse.php');
        exit;

    }

    $nameLength = strlen($courseName);
    if ($nameLength < 4 || $nameLength > 45) {
        $_SESSION["error"] = "Error: Nama harus terdiri dari 4 hingga 45 karakter";
        header('location: dataCourse.php');
        exit;

    }

    $codeLength = strlen($courseCode);
    if ($codeLength < 3 || $codeLength > 5) {
        $_SESSION["error"] = "Error: Kode harus terdiri dari 3 hingga 5 karakter";
        header('location: dataCourse.php');
        exit;

    }

    if ($classroomId === "select") {
        $_SESSION["error"] = "Error: Silakan pilih ruang kelas";
        header('location: dataCourse.php');
        exit;
    }

    try {
        $connection = getConnection();
        // Check for changes in course data
        $stmt_check = $connection->prepare("
            SELECT Name, Code, ClassroomId
            FROM courses
            WHERE CourseId = :courseId;
        ");
        $stmt_check->bindParam(':courseId', $courseId);
        $stmt_check->execute();
        $currentData = $stmt_check->fetch(PDO::FETCH_ASSOC);

        if ($currentData['Name'] === $courseName && $currentData['Code'] === $courseCode && $currentData['ClassroomId'] == $classroomId) {
            $_SESSION["error"] = "Error: Tidak ada perubahan dalam data Mata Kuliah";
            header('location: dataCourse.php');
            exit;
        }


        // Update course information
        $stmt_update = $connection->prepare("
            UPDATE courses
            SET Name = :name, Code = :code, ClassroomId = :classroomId
            WHERE CourseId = :courseId;
        ");
        $stmt_update->bindParam(':name', $courseName);
        $stmt_update->bindParam(':code', $courseCode);
        $stmt_update->bindParam(':classroomId', $classroomId);
        $stmt_update->bindParam(':courseId', $courseId);
        $stmt_update->execute();

        // Redirect to the course list page after successful update
        $_SESSION["success"] = "Sukses mengubah mata kuliah.";
        header('location: dataCourse.php');
        exit;
    } catch (Exception $e) {
        $_SESSION["error"] = "Terjadi kesalahan saat memperbarui data Mata Kuliah.";
        header('location: dataCourse.php'); // Redirect back to the edit page with an error message
    } finally {
        // Menutup koneksi database
        if ($connection) {
            $connection = null;
        }
    }
}


function updateCourseView()
{
    global $data;
    $courseId = htmlspecialchars($_POST["edit"]);

    try {
        $connection = getConnection();
        // Mengambil informasi kursus
        $stmt_course = $connection->prepare("
            SELECT courses.CourseId,  courses.Name, courses.Code, courses.ClassroomId
            FROM courses
            WHERE courses.CourseId = :courseId;
        ");
        $stmt_course->bindParam(':courseId', $courseId);
        $stmt_course->execute();
        $data["courseDetail"] = $stmt_course->fetch(PDO::FETCH_ASSOC);

        if (!$data["courseDetail"]) {
            $_SESSION["error"] = "Mata Kuliah tidak ditemukan.";
            return;
        }

        // Mengambil semua ruang kelas
        $stmt_classrooms = $connection->prepare("
            SELECT classrooms.ClassroomId, CONCAT(buildings.Letter, classrooms.Code) AS Room, classrooms.Capacity
            FROM classrooms INNER JOIN buildings ON classrooms.BuildingId = buildings.BuildingId;
        ");
        $stmt_classrooms->execute();
        $classrooms = $stmt_classrooms->fetchAll(PDO::FETCH_ASSOC);

        // Memasukkan informasi ruang kelas ke dalam array data
        $data['classrooms'] = $classrooms;
    } catch (Exception $e) {
        $_SESSION["error"] = "Terjadi kesalahan saat memproses permintaan.";
        return;
    } finally {
        // Menutup koneksi database
        if ($connection) {
            $connection = null;
        }
    }
}