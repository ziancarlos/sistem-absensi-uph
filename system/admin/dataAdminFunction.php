<?php
session_start();
require_once ("../../helper/dbHelper.php");
require_once ("../../helper/authHelper.php");
$permittedRole = ["admin"];
$pageName = "Sistem Absensi UPH - Data Admin";
$data = [];
if (!authorization($permittedRole, $_SESSION["UserId"])) {
    header('location: ../auth/login.php');
}

dataAdminView();



function dataAdminView()
{
    global $data;

    try {
        $statement = dataAdminModel();
    } catch (PDOException $e) {
        $_SESSION["error"] = "Data tidak dapat diambil, Hubungi admin jika masalah ini terus terjadi!";
    }

    if ($statement === null) {
        $_SESSION["error"] = "Gagal memuat database, hubungi admin!";
        return;
    }

    $data["users"] = $statement->fetchAll();


}


function dataAdminModel()
{
    $statement = null;

    try {
        $connection = getConnection();

        $sql = "SELECT users.UserId, users.Name, users.Email, users.Status FROM Users WHERE users.Role='2';";

        $statement = $connection->prepare($sql);
        $statement->execute();


    } catch (PDOException $e) {
        throw $e;
    }

    $connection = null;

    return $statement;
}