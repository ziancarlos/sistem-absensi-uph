<?php
session_start();
require_once("../../helper/dbHelper.php");
require_once("../../helper/authHelper.php");
$permittedRole = ["admin"];
$pageName = "Sistem Absensi UPH - Detail Mata Kuliah Dosen";
$data = [];
if (!authorization($permittedRole, $_SESSION["UserId"])) {
    header('location: ../auth/login.php');
}

dataLecturerDetailView();


function dataLecturerDetailView()
{
    global $data;

    try {
        // Ambil ID dosen dari formulir yang dikirimkan
        $lecturerId = $_POST['lecturerId'];

        // Panggil fungsi dataLecturerDetailModel() dengan menyertakan $lecturerId
        $statement = dataLecturerDetailModel($lecturerId);

        if ($statement) {
            // Fetch data jika statement berhasil dieksekusi
            $data["users"] = $statement->fetchAll();
        } else {
            // Menangani kesalahan jika statement tidak berhasil dieksekusi
            $_SESSION["error"] = "Gagal mengeksekusi perintah SQL";
            // Lakukan tindakan sesuai dengan kebutuhan aplikasi Anda, misalnya, redirect ke halaman kesalahan
            header('location: error.php');
            exit(); // Penting untuk menghentikan eksekusi skrip setelah melakukan redirect
        }
    } catch (PDOException $e) {
        $_SESSION["error"] = "Data tidak dapat diambil, Hubungi admin jika masalah ini terus terjadi!";
        // Lakukan tindakan sesuai dengan kebutuhan aplikasi Anda, misalnya, redirect ke halaman kesalahan
        header('location: error.php');
        exit(); // Penting untuk menghentikan eksekusi skrip setelah melakukan redirect
    }
}


function dataLecturerDetailModel($lecturerId)
{
    $statement = null;

    try {
        $connection = getConnection();

        $sql = "SELECT 
        users.Name AS LecturerName, 
        courses.CourseId,
        courses.StartDate, 
        courses.EndDate, 
        courses.Code AS CourseCode, 
        courses.Name AS CourseName, 
        courses.Status,
        CONCAT(buildings.Letter, classrooms.Code) AS Class
    FROM 
        Users 
    JOIN 
        lecturerhascourses ON users.UserId = lecturerhascourses.LecturerId 
    JOIN 
        courses ON lecturerhascourses.CourseId = courses.CourseId 
    JOIN 
        classrooms ON courses.ClassroomId = classrooms.ClassroomId 
    JOIN 
        buildings ON classrooms.BuildingId = buildings.BuildingId 
    WHERE 
        users.UserId = :lecturerId;";

        $statement = $connection->prepare($sql);
        $statement->bindParam(':lecturerId', $lecturerId);
        $statement->execute();
    } catch (PDOException $e) {
        throw $e;
    }

    $connection = null;

    return $statement;
}