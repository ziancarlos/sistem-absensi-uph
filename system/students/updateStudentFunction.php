<?php
session_start();
require_once ("../../helper/dbHelper.php");
require_once ("../../helper/authHelper.php");
$permittedRole = ["lecturer", "admin"];
$pageName = "Sistem Absensi UPH - Ubah Mahasiswa";
$data = [];
if (!authorization($permittedRole, $_SESSION["UserId"])) {
    header('location: ../auth/logout.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["ubahView"])) {
    updateStudentView();

    if ($data == null) {
        $_SESSION["error"] = "Data tidak dapat diambil, Hubungi admin jika masalah ini terus terjadi!";

        header("location: dataStudent.php");
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["ubah"])) {
    updateStudentController();
}


function updateStudentController()
{
    try {
        // Ambil nilai-nilai yang dikirimkan dalam permintaan POST
        $studentId = htmlspecialchars($_POST["studentId"]);
        $name = htmlspecialchars($_POST["name"]);
        $email = htmlspecialchars($_POST["email"]);
        $yearIn = htmlspecialchars($_POST["yearIn"]);
        $password = htmlspecialchars($_POST["password"]);



        // Cek apakah ada perubahan dalam data
        $connection = getConnection(); // Mengasumsikan Anda memiliki fungsi bernama getConnection() untuk membuat koneksi PDO

        // Query untuk mendapatkan data mahasiswa sebelum perubahan
        $stmt = $connection->prepare("SELECT * FROM Students INNER JOIN Users  ON Students.StudentId = Users.StudentId WHERE Students.StudentId = :studentId");
        $stmt->bindParam(':studentId', $studentId);
        $stmt->execute();
        $existingStudent = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$existingStudent) {
            throw new Exception("Data mahasiswa tidak ditemukan!");
        }

        // Bandingkan nilai-nilai yang baru dengan nilai-nilai yang ada dalam database
        if ($existingStudent['Name'] === $name && $existingStudent['Email'] === $email && $existingStudent['YearIn'] === $yearIn && empty($password)) {
            throw new Exception("Tidak ada perubahan yang dilakukan!");
        }

        // Lakukan perubahan jika ada perubahan dalam data
        $updateStmt = $connection->prepare("UPDATE Students SET YearIn = :yearIn WHERE StudentId = :studentId");
        $updateStmt->bindParam(':yearIn', $yearIn);
        $updateStmt->bindParam(':studentId', $studentId);
        $updateStmt->execute();

        $updateUserStmt = $connection->prepare("UPDATE Users SET Name = :name, Email = :email WHERE StudentId = :studentId");
        $updateUserStmt->bindParam(':name', $name);
        $updateUserStmt->bindParam(':email', $email);
        $updateUserStmt->bindParam(':studentId', $studentId);
        $updateUserStmt->execute();

        // Jika password tidak kosong, update password
        if (!empty($password)) {
            $hashedPassword = md5($password);
            $updatePasswordStmt = $connection->prepare("UPDATE Users SET Password = :password WHERE StudentId = :studentId");
            $updatePasswordStmt->bindParam(':password', $hashedPassword);
            $updatePasswordStmt->bindParam(':studentId', $studentId);
            $updatePasswordStmt->execute();
        }

        $_SESSION["success"] = "Data mahasiswa berhasil diperbarui!";
    } catch (Exception $e) {
        $_SESSION["error"] = "Error: " . $e->getMessage();
    }

    header("Location: dataStudent.php");
    exit;
}



function updateStudentView()
{
    global $data;

    $userId = htmlspecialchars($_POST["ubahView"]);

    try {
        $connection = getConnection(); // Assuming you have a function named getConnection() to establish a PDO connection

        // Query to fetch the required information from Students and Users tables
        $stmt = $connection->prepare("SELECT Students.StudentId, Users.Name, Users.Email, Students.YearIn 
        FROM Users INNER JOIN Students ON Users.StudentId = Students.StudentId WHERE Users.UserId = :userId");
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();

        $data["student"] = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            throw new Exception("Data mahasiswa tidak ditemukan!");
        }
    } catch (Exception $e) {
        $_SESSION["error"] = "Error: " . $e->getMessage();

        return;
    }
}