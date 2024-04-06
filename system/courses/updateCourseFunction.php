<?php
session_start();
require_once ("../../helper/dbHelper.php");
require_once ("../../helper/authHelper.php");
$permittedRole = ["lecturer", "admin"];
$pageName = "Sistem Absensi UPH - Edit Mahasiswa";
$data = [];
if (!authorization($permittedRole, $_SESSION["UserId"])) {
    header('location: ../auth/logout.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["edit"])) {
    updateCourseView();
} else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["update"])) {
    updateCourseController();
} else {
    $_SESSION["error"] = "Tidak menemukan permintaan yang valid!";
    header("location: dataCourse.php");
    exit;
}


function updateCourseController()
{
    global $data;
    $courseId = htmlspecialchars($_POST["update"]);
    $courseName = htmlspecialchars($_POST["name"]);
    $courseCode = htmlspecialchars($_POST["kode"]);
    $classroomId = htmlspecialchars($_POST["ruang"]);
    $selectedLecturers = isset($_POST["lecturers"]) ? $_POST["lecturers"] : [];

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

        // Begin a transaction
        $connection->beginTransaction();

        // Update course information
        $stmt_update_course = $connection->prepare("
            UPDATE courses
            SET Name = :name, Code = :code, ClassroomId = :classroomId
            WHERE CourseId = :courseId;
        ");
        $stmt_update_course->bindParam(':name', $courseName);
        $stmt_update_course->bindParam(':code', $courseCode);
        $stmt_update_course->bindParam(':classroomId', $classroomId);
        $stmt_update_course->bindParam(':courseId', $courseId);
        $stmt_update_course->execute();

        // Remove existing lecturer-course relationships
        $stmt_delete_relationships = $connection->prepare("
            DELETE FROM lecturerhascourses
            WHERE CourseId = :courseId;
        ");
        $stmt_delete_relationships->bindParam(':courseId', $courseId);
        $stmt_delete_relationships->execute();

        // Insert new lecturer-course relationships
        foreach ($selectedLecturers as $lecturerId) {
            $stmt_insert_relationship = $connection->prepare("
                INSERT INTO lecturerhascourses (LecturerId, CourseId)
                VALUES (:lecturerId, :courseId);
            ");
            $stmt_insert_relationship->bindParam(':lecturerId', $lecturerId);
            $stmt_insert_relationship->bindParam(':courseId', $courseId);
            $stmt_insert_relationship->execute();
        }

        // Commit the transaction
        $connection->commit();

        // Redirect to the course list page after successful update
        $_SESSION["success"] = "Sukses mengubah mata kuliah.";
        header('location: dataCourse.php');
        exit;
    } catch (Exception $e) {
        // Rollback the transaction in case of error
        $connection->rollBack();
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

        // Mengambil semua dosen
        $stmt_lecturers = $connection->prepare("
            SELECT users.UserId as LecturerId, users.Name
            FROM users
            WHERE users.Role = '1';
        ");
        $stmt_lecturers->execute();
        $lecturers = $stmt_lecturers->fetchAll(PDO::FETCH_ASSOC);

        // Memasukkan informasi dosen ke dalam array data
        $data['lecturers'] = $lecturers;

        // Mendapatkan dosen yang terpilih untuk mata kuliah ini
        $stmt_selected_lecturers = $connection->prepare("
            SELECT LecturerId
            FROM lecturerhascourses
            WHERE CourseId = :courseId;
        ");
        $stmt_selected_lecturers->bindParam(':courseId', $courseId);
        $stmt_selected_lecturers->execute();
        $selectedLecturers = $stmt_selected_lecturers->fetchAll(PDO::FETCH_COLUMN);

        // Memasukkan dosen terpilih ke dalam array data
        $data['selectedLecturers'] = $selectedLecturers;
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