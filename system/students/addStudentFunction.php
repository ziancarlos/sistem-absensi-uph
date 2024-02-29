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
        // var_dump("halo");

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

    // Connect to database using PDO
    $connection = getConnection();

    try {
        $connection->beginTransaction();

        // Check if the nim already exists in the Students table
        $nimCheckQuery = "SELECT * FROM Students WHERE StudentId = :nim";
        $nimCheckStmt = $connection->prepare($nimCheckQuery);
        $nimCheckStmt->bindParam(':nim', $nim);
        $nimCheckStmt->execute();

        if ($nimCheckStmt->rowCount() > 0) {
            $_SESSION["error"] = "NIM sudah digunakan!";
            $connection->rollBack();
            return;
        }

        // Check if the email already exists in the Users table
        $emailCheckQuery = "SELECT * FROM Users WHERE email = :email";
        $emailCheckStmt = $connection->prepare($emailCheckQuery);
        $emailCheckStmt->bindParam(':email', $email);
        $emailCheckStmt->execute();

        if ($emailCheckStmt->rowCount() > 0) {
            $_SESSION["error"] = "Alamat email sudah terdaftar!";
            $connection->rollBack();
            return;
        }

        // Insert data into Students table
        $insertStudentQuery = "INSERT INTO Students (StudentId, YearIn) VALUES (:nim, :yearIn)";
        $insertStudentStmt = $connection->prepare($insertStudentQuery);
        $insertStudentStmt->bindParam(':nim', $nim);
        $insertStudentStmt->bindParam(':yearIn', $yearIn);
        $insertStudentStmt->execute();

        // Insert data into Users table
        $insertUserQuery = "INSERT INTO Users (Name, Email, StudentId, Password) VALUES (:name, :email, :nim, :password)";
        $insertUserStmt = $connection->prepare($insertUserQuery);
        $insertUserStmt->bindParam(':name', $name);
        $insertUserStmt->bindParam(':email', $email);
        $insertUserStmt->bindParam(':nim', $nim);
        $insertUserStmt->bindParam(':password', $hashedPassword);
        $insertUserStmt->execute();

        $connection->commit();
        $_SESSION["success"] = "Data mahasiswa berhasil ditambahkan!";
    } catch (PDOException $e) {
        $_SESSION["error"] = "Tidak berhasil menambahkan data mahasiswa!";
        $connection->rollBack();
    }

    $connection = null;
}