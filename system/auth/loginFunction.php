<?php
session_start();
require_once ("../../helper/dbHelper.php");


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["login"])) {
    loginController();
}
function loginController()
{
    $email = htmlspecialchars($_POST["email"]);
    $password = htmlspecialchars($_POST["password"]);

    if (empty($email) || empty($password)) {
        $_SESSION["error"] = "Email atau password kosong!";
        return;
    }

    $password = md5($_POST['password']);

    try {
        $statement = loginModel($email, $password);

        // Check if the user was found
        if ($statement->rowCount() <= 0) {
            $_SESSION["error"] = "User tidak ditemukan!";
            return;
        }

        // Fetch user information
        $user = $statement->fetch(PDO::FETCH_ASSOC);

        // Check if the user's status is 0 (deactivated)
        if ($user['Status'] === 0) {
            $_SESSION["error"] = "User sudah dinonaktifkan!";
            return;
        }

        // Save the user ID in the session
        $_SESSION["UserId"] = $user["UserId"];

        // Redirect to the dashboard
        header('location: ../dashboard/dashboard.php');
    } catch (PDOException $e) {
        $_SESSION["error"] = "Terjadi kesalahan pada database!";
        return;
    }
}



function loginModel($email, $password)
{
    $connection = getConnection();
    $statement = null;

    try {
        $sql = "SELECT UserId FROM Users WHERE email = :email AND password = :password FOR UPDATE";

        $statement = $connection->prepare($sql);
        $statement->bindParam('email', $email);
        $statement->bindParam('password', $password);
        $statement->execute();
    } catch (PDOException $e) {
        throw $e;
    }


    $connection = null;

    return $statement;
}