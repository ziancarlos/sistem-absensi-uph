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
            // Periksa apakah nomor ruang sudah ada di database untuk gedung yang sama
            $stmt_check = $connection->prepare("
                SELECT COUNT(*) as count FROM classrooms 
                WHERE BuildingId = :buildingId AND Code = :roomNumber
            ");
            $stmt_check->bindParam(':buildingId', $buildingId);
            $stmt_check->bindParam(':roomNumber', $roomNumber);
            $stmt_check->execute();
            $result = $stmt_check->fetch(PDO::FETCH_ASSOC);

            if ($result['count'] > 0) {
                // Jika nomor ruang sudah ada, kembalikan error
                $_SESSION["error"] = "Nomor ruang sudah ada untuk gedung yang dipilih";
                header("location: addClassroom.php");
                exit;
            }

            // Jika nomor ruang belum ada, tambahkan ruangan baru ke database
            $stmt_insert = $connection->prepare("
                INSERT INTO classrooms (BuildingId, Code, Capacity) 
                VALUES (:buildingId, :roomNumber, :capacity)
            ");
            $stmt_insert->bindParam(':buildingId', $buildingId);
            $stmt_insert->bindParam(':roomNumber', $roomNumber);
            $stmt_insert->bindParam(':capacity', $capacity);
            $stmt_insert->execute();

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

