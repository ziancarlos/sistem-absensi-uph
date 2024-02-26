<?php
session_start();
require_once("../../helper/dbHelper.php");


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["login"])) {
    loginController();
} else if (isset($_SESSION["ID_USER"])) {

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

    $connection = getConnection();

    try {
        $statement = loginModel($email, $password);
    } catch (PDOException $e) {
        $connection->rollback();

        $connection = null;

        $_SESSION["error"] = "default";

        return;
    }

    if ($statement->rowCount() <= 0) {
        $_SESSION["error"] = "User tidak ditemukan!";
        return;
    }

    $userId = $statement->fetch()["UserId"];

    $_SESSION["UserId"] = $userId;

    header('location: ../dashboard/dashboard.php');

    return;
}


function loginModel($email, $password)
{
    $connection = getConnection();
    $statement = null;

    try {
        $sql = "SELECT UserId FROM users WHERE email = :email AND password = :password FOR UPDATE";

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