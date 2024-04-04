<?php
require_once ("../helper/dbHelper.php");

// Set the default timezone to Asia/Jakarta
date_default_timezone_set('Asia/Jakarta');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['studentCardAttendance'])) {
    // Get the StudentId and Card from the POST parameters
    $cardId = $_POST['cardId'];

    // Call the function to register the card
    $result = studentCardAttendance($cardId);

    // Prepare and send response to Python based on the result
    if ($result !== "") {
        // Card attendance success or failure, send appropriate message to Python
        echo json_encode(array("success" => $result)); // Assuming $result contains success message
    } else {
        // Card attendance failure, send error message to Python
        echo json_encode(array("error" => "Unknown error occurred."));
    }
}

// Function to check if cardId exists in the database
function isCardExist($cardId)
{
    // Database connection settings
    $connection = getConnection();

    try {
        // SQL query to check if the cardId exists
        $sql = "SELECT COUNT(*) FROM students WHERE Card = :cardId";

        // Prepare and execute the query
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(':cardId', $cardId);
        $stmt->execute();

        // Fetch the count of rows
        $count = $stmt->fetchColumn();

        // Close the connection
        $connection = null;

        // Return true if cardId exists, false otherwise
        return ($count > 0);
    } catch (PDOException $e) {
        // Handle query execution errors
        echo "Error: " . $e->getMessage();
        return true; // Assume cardId exists to avoid any risk
    }
}

// Define the function for student attendance
function studentCardAttendance($cardId)
{
    // Database connection settings
    $connection = getConnection();

    try {
        // Check if the cardId exists in the students table
        if (!isCardExist($cardId)) {
            return "Card ID not registered.";
        }

        // Get the current date and time
        $currentDateTime = date("Y-m-d H:i:s");

        // SQL query to get student name and check if there is a class scheduled 90 minutes later for the student
        $sql = "SELECT users.Name, attendances.StudentId, attendances.Status, schedules.ScheduleId, schedules.DateTime
                FROM attendances 
                INNER JOIN students ON attendances.StudentId = students.StudentId 
                INNER JOIN users ON students.StudentId = users.StudentId 
                INNER JOIN schedules ON attendances.ScheduleId = schedules.ScheduleId 
                WHERE students.Card = :cardId 
                AND schedules.DateTime <= DATE_ADD(:scheduleDateTime, INTERVAL 90 MINUTE)
                ORDER BY schedules.DateTime DESC LIMIT 1";
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(':cardId', $cardId);
        $stmt->bindParam(':scheduleDateTime', $currentDateTime);
        $stmt->execute();
        $attendanceInfo = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if there is any class scheduled 90 minutes later
        if (!$attendanceInfo) {
            return "No class scheduled 90 minutes later.";
        }

        // Get student name from the query result
        $studentName = $attendanceInfo['Name'];

        // Get the schedule ID and student ID
        $scheduleId = $attendanceInfo['ScheduleId'];
        $studentId = $attendanceInfo['StudentId'];

        // SQL query to update attendance and check for late attendance
        $sqlUpdateAttendance = "UPDATE attendances SET CardTimeIn = :currentTime, 
                Status = 
                    CASE
                        WHEN (TIMESTAMPDIFF(MINUTE, (SELECT DateTime FROM schedules WHERE ScheduleId = :scheduleId), :currentTime) > 30) THEN 2  -- Late
                        WHEN (:currentTime < (SELECT DateTime FROM schedules WHERE ScheduleId = :scheduleId)) THEN 0 -- Class not started
                        ELSE 1 -- On time
                    END
                WHERE 
                    StudentId = :studentId
                    AND ScheduleId = :scheduleId";

        // Prepare and execute the query
        $stmtUpdateAttendance = $connection->prepare($sqlUpdateAttendance);
        $stmtUpdateAttendance->bindParam(':currentTime', $currentDateTime);
        $stmtUpdateAttendance->bindParam(':scheduleId', $scheduleId);
        $stmtUpdateAttendance->bindParam(':studentId', $studentId);
        $stmtUpdateAttendance->execute();

        // Check the status of attendance update
        $rowsAffected = $stmtUpdateAttendance->rowCount();
        
        if ($rowsAffected > 0) {
            // Get the updated status from the database
            $sqlGetUpdatedStatus = "SELECT Status FROM attendances WHERE StudentId = :studentId AND ScheduleId = :scheduleId";
            $stmtGetUpdatedStatus = $connection->prepare($sqlGetUpdatedStatus);
            $stmtGetUpdatedStatus->bindParam(':studentId', $studentId);
            $stmtGetUpdatedStatus->bindParam(':scheduleId', $scheduleId);
            $stmtGetUpdatedStatus->execute();
            $updatedStatus = $stmtGetUpdatedStatus->fetchColumn();
        
            if ($updatedStatus == 1) {
                // Class started on time
                return "Dear $studentName, you have successfully attended the class on time!";
            } elseif ($updatedStatus == 2) {
                // Late notification
                return "Dear $studentName, you are late for the class!";
            }
        } else {
            // No rows affected, possibly student not found or schedule not found
            return "Student not found or schedule not available.";
        }

        // Close the connection
        $connection = null;
        
    } catch (PDOException $e) {
        // Handle query execution errors
        return "Error: " . $e->getMessage();
    }
}


?>
