<?php
session_start();
require_once("../../helper/dbHelper.php");
require_once("../../helper/authHelper.php");
$permittedRole = ["lecturer", "admin"];
$pageName = "Sistem Absensi UPH - Tambah Mahasiswa";
$data = [];
if (!authorization($permittedRole, $_SESSION["UserId"])) {
    header('location: ../auth/login.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["add"])) {
    addStudentController();
}
function addStudentController()
{
    $name = htmlspecialchars($_POST["name"]);
    $nim = htmlspecialchars($_POST["nim"]);
    $email = htmlspecialchars($_POST["email"]);
    $yearIn = htmlspecialchars($_POST["yearIn"]);
    $password = htmlspecialchars($_POST["password"]);

    // Check if any required field is empty
    if (empty($name) || empty($nim) || empty($email) || empty($yearIn) || empty($password)) {
        $_SESSION["error"] = "Nama, NIM, email, tahun masuk, atau password kosong, silakan isi semuanya!";
        return;
    }

    // Validate NIM length and format
    if (!preg_match("/^\d{11,}$/", $nim)) {
        $_SESSION["error"] = "NIM harus terdiri dari 11 karakter atau lebih dan hanya boleh berisi angka!";
        return;
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION["error"] = "Format email tidak valid!";
        return;
    }

    // Validate yearIn as a valid year
    if (!preg_match("/^(19|20)\d{2}$/", $yearIn)) {
        $_SESSION["error"] = "Tahun masuk harus berupa tahun (1900-2099)!";
        return;
    }

    // Validate password length
    if (strlen($password) < 8) {
        $_SESSION["error"] = "Password harus terdiri dari minimal 8 karakter!";
        return;
    }

    // Hash the password using MD5
    $hashedPassword = md5($password);

    // Add data to the 'users' table
    $connection = getConnection();
    $sqlUsers = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
    $statementUsers = $connection->prepare($sqlUsers);
    $statementUsers->bindParam(':name', $name);
    $statementUsers->bindParam(':email', $email);
    $statementUsers->bindParam(':password', $hashedPassword);
    $statementUsers->execute();

    // Get the ID of the inserted user
    $userId = $connection->lastInsertId();

    // Add data to the 'students' table
    $sqlStudents = "INSERT INTO students (user_id, nim, yearIn) VALUES (:userId, :nim, :yearIn)";
    $statementStudents = $connection->prepare($sqlStudents);
    $statementStudents->bindParam(':userId', $userId);
    $statementStudents->bindParam(':nim', $nim);
    $statementStudents->bindParam(':yearIn', $yearIn);
    $statementStudents->execute();

    // Close connection
    $connection = null;

    // Redirect or show a success message
    // For example:
    header("Location: success_page.php");
    exit();
}