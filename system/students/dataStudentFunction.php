<?php
session_start();
require_once("../../helper/dbHelper.php");
require_once("../../helper/authHelper.php");
$permittedRole = ["lecturer", "admin"];
$pageName = "Sistem Absensi UPH - Data Mahasiswa";
$data = [];
if (!authorization($permittedRole, $_SESSION["UserId"])) {
    header('location: ../auth/login.php');
}

dataStudentView();



function dataStudentView()
{
    global $data;

    try {
        $statement = dataStudentModel();
    } catch (PDOException $e) {
        $_SESSION["error"] = "Data tidak dapat diambil, Hubungi admin jika masalah ini terus terjadi!";
    }

    if ($statement === null) {
        $_SESSION["error"] = "Gagal memuat database, hubungi admin!";
        return;
    }

    $data["students"] = $statement->fetchAll();


}


function dataStudentModel()
{
    $statement = null;

    try {
        $connection = getConnection();

        $sql = "SELECT users.Name, students.StudentId, YEAR(students.YearIn) AS YearIn FROM users INNER JOIN students ON users.StudentId = students.StudentId ORDER BY users.UserId DESC;";

        $statement = $connection->prepare($sql);
        $statement->execute();


    } catch (PDOException $e) {
        throw $e;
    }

    $connection = null;

    return $statement;
}