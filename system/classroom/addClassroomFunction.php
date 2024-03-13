<?php
    session_start();
    require_once("../../helper/dbHelper.php");
    require_once("../../helper/authHelper.php");
    $permittedRole = ["lecturer", "admin"];
    $pageName = "Sistem Absensi UPH - Data Mahasiswa";
    $data = [];
    if (!authorization($permittedRole, $_SESSION["UserId"])) {
        header('location: ../auth/logout.php');
    }

    dataClassroomView();

    function dataClassroomView()
    {
        global $data;
        $userId = $_SESSION["UserId"];
        $connection = getConnection();

        // Check user role
        $userRole = getUserRole($userId);

        // Retrieve courses and classrooms information for both admin and lecturer
        try {
            $stmt = $connection->prepare("
            SELECT classrooms.Capacity, CONCAT(buildings.Letter, classrooms.Code) AS Room 
            FROM classrooms INNER JOIN buildings ON classrooms.BuildingId = buildings.BuildingId;
            ");
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            $_SESSION["error"] = $e->getMessage();
            header("location: dataStudent.php");
            exit;
        }
    }

    function getBuildings()
    {
        global $data;
        $connection = getConnection();

        try {
            $stmt = $connection->prepare("
                SELECT BuildingId, Letter 
                FROM buildings
            ");
            $stmt->execute();
            $buildings = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $buildings;
        } catch (Exception $e) {
            // Tangani exception jika terjadi kesalahan saat mengambil data bangunan
            $_SESSION["error"] = "Error fetching buildings data: " . $e->getMessage();
            return false;
        }
    }


    function addClassroom($buildingId, $roomNumber, $capacity)
    {
        $connection = getConnection();

        try {
            $stmt = $connection->prepare("
                INSERT INTO classrooms (BuildingId, Code, Capacity) 
                VALUES (:buildingId, :roomNumber, :capacity)
            ");
            $stmt->bindParam(':buildingId', $buildingId);
            $stmt->bindParam(':roomNumber', $roomNumber);
            $stmt->bindParam(':capacity', $capacity);
            $stmt->execute();

            // Redirect to avoid resubmission on refresh
            header("location: addClassroom.php");
            exit();
        } catch (Exception $e) {
            $_SESSION["error"] = $e->getMessage();
            header("location: addClassroom.php");
            exit;
        }
    }

?>

