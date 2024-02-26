<?php
session_start();
require_once("../../helper/dbHelper.php");
require_once("../../helper/authHelper.php");
$permittedRole = ["student", "lecturer", "admin"];
$pageName = "Sistem Absensi UPH - Dashboard";
authorization($permittedRole, $_SESSION["UserId"]);