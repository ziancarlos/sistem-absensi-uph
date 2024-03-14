<?php
session_start();
require_once("../../helper/dbHelper.php");
require_once("../../helper/authHelper.php");
$permittedRole = ["lecturer", "admin"];
$pageName = "Sistem Absensi UPH - Tambah Dosen";
$data = [];
if (!authorization($permittedRole, $_SESSION["UserId"])) {
    header('location: ../auth/login.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["add"])) {
    addLecturerController();
}
function addLecturerController()
{
    $name = htmlspecialchars($_POST["name"]);
    $email = htmlspecialchars($_POST["email"]);
    $password = htmlspecialchars($_POST["password"]);


    // Check if any required field is empty
    if (empty($name) || empty($email) || empty($password)) {
        $_SESSION["error"] = "Nama, NIP, email, atau password kosong, silakan isi semuanya!";

        return;
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION["error"] = "Format email tidak valid!";
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

        // Check if the name already exists in the Users table
        $nameCheckQuery = "SELECT * FROM Users WHERE Name = :name";
        $nameCheckStmt = $connection->prepare($nameCheckQuery);
        $nameCheckStmt->bindParam(':name', $name);
        $nameCheckStmt->execute();

        if ($nameCheckStmt->rowCount() > 0) {
            $_SESSION["error"] = "Nama sudah terdaftar!";
            $connection->rollBack();
            return;
        }

        // Insert data into Users table
        $insertUserQuery = "INSERT INTO Users (UzName, Email, Password, Role) VALUES (:nip, :name, :email, :password, 1)";
        $insertUserStmt = $connection->prepare($insertUserQuery);
        $insertUserStmt->bindParam(':nip', $nim);
        $insertUserStmt->bindParam(':name', $name);
        $insertUserStmt->bindParam(':email', $email);
        $insertUserStmt->bindParam(':password', $hashedPassword);
        $insertUserStmt->execute();

        $connection->commit();
        $_SESSION["success"] = "Data dosen berhasil ditambahkan!";
    } catch (PDOException $e) {
        $_SESSION["error"] = "Tidak berhasil menambahkan data dosen!";
        $connection->rollBack();
    }

    $connection = null;
}