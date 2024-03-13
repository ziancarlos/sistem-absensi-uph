<?php
session_start();
require_once("../../helper/dbHelper.php");
require_once("../../helper/authHelper.php");
$permittedRole = ["lecturer", "admin"];
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
        $statement = dataLecturerDetailModel();
    } catch (PDOException $e) {
        $_SESSION["error"] = "Data tidak dapat diambil, Hubungi admin jika masalah ini terus terjadi!";
    }

    if ($statement === null) {
        $_SESSION["error"] = "Gagal memuat database, hubungi admin!";
        return;
    }

    $data["users"] = $statement->fetchAll();


}


function dataLecturerDetailModel()
{
    $statement = null;

    try {
        $connection = getConnection();

        $sql = "SELECT 
        users.Name AS LecturerName, 
        courses.StartDate, 
        courses.EndDate, 
        courses.Code AS CourseCode, 
        courses.Name AS CourseName, 
        CONCAT(building.Letter, classrooms.Code) AS Class, 
        schedules.DateTime 
    FROM 
        Users 
    JOIN 
        lecturerhascourses ON users.UserId = lecturerhascourses.LecturerId 
    JOIN 
        courses ON lecturerhascourses.CourseId = courses.CourseId 
    JOIN 
        classrooms ON courses.ClassroomId = classrooms.ClassroomId 
    JOIN 
        building ON classrooms.BuildingId = building.BuildingId 
    JOIN 
        schedules ON courses.CourseId = schedules.CourseId 
    WHERE 
        users.Role='1';
    ;";

        $statement = $connection->prepare($sql);
        $statement->execute();


    } catch (PDOException $e) {
        throw $e;
    }

    $connection = null;

    return $statement;
}