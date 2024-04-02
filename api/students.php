<?php
require_once ("../helper/dbHelper.php");

// Set the default timezone to Asia/Jakarta
date_default_timezone_set('Asia/Jakarta');

// Check if there is a GET request named 'getStudentByCardId'
if (isset ($_GET['getStudentByCardId'])) {
    // Get the card ID from the GET parameters
    $cardId = $_GET['getStudentByCardId'];

    // Call the function to get the student by card ID
    $userJson = getStudentByCardId($cardId);
    
    // Check if user data is found
    if ($userJson) {
        // Print the JSON data
        echo $userJson;
    } else {
        // Print JSON with an error message
        echo json_encode(array("error" => "User not found"));
    }
}

// START : yoana yang tambahin

// Check if there is a POST request for registering card or mark attendance
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['registerCard'])) {
        // Get the StudentId and Card from the POST parameters
        $studentId = $_POST['studentId'];
        $cardId = $_POST['cardId'];

        // Call the function to register the card
        $success = registerCard($studentId, $cardId);

        // Check if card registration was successful
        if ($success) {
            // Print success message
            echo json_encode(array("success" => "Card registered successfully"));
        } else {
            // Print error message
            echo json_encode(array("error" => "Failed to register card"));
        }
    } elseif (isset($_POST['studentCardAttendance'])) {
        // Get the card ID from the POST parameters
        $cardId = $_POST['cardId'];

        // Call the function to mark student attendance
        $attendanceSuccess = studentCardAttendance($cardId);

        // Check if attendance update was successful
        if ($attendanceSuccess) {
            // Print success message
            echo json_encode(array("success" => "Attendance marked successfully"));
        } else {
            // Print error message
            echo json_encode(array("error" => "Failed to mark attendance"));
        }
    } else {
        // Invalid request
        echo json_encode(array("error" => "Invalid request"));
    }
}

// Define the function to check if card is already registered to another student
function isCardRegistered($cardId)
{
    // Database connection settings
    $connection = getConnection();

    try {
        // SQL query to check if the card is already registered
        $sql = "SELECT COUNT(*) FROM students WHERE Card = :cardId";

        // Prepare and execute the query
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(':cardId', $cardId);
        $stmt->execute();

        // Fetch the count of rows
        $count = $stmt->fetchColumn();

        // Close the connection
        $connection = null;

        // Return true if card is registered to another student, false otherwise
        return ($count > 0);
    } catch (PDOException $e) {
        // Handle query execution errors
        echo "Error: " . $e->getMessage();
        return true; // Assume card is registered to avoid any risk
    }
}


// Define the function to register a card based on StudentId
function registerCard($studentId, $cardId)
{
    // Database connection settings
    $connection = getConnection();

    try {
        // SQL query to update the Card for the specified StudentId
        $sql = "UPDATE students SET Card = :cardId WHERE StudentId = :studentId";

        // Prepare and execute the query
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(':cardId', $cardId);
        $stmt->bindParam(':studentId', $studentId);
        $success = $stmt->execute();

        // Close the connection
        $connection = null;

        // Return true if card registration was successful, false otherwise
        return $success;
    } catch (PDOException $e) {
        // Handle query execution errors
        echo "Error: " . $e->getMessage();
        return false;
    }
}

// Define the function for student attendance
function studentCardAttendance($cardId)
{
    // Database connection settings
    $connection = getConnection();

    try {
        // Get the current date and time
        $currentDateTime = date("Y-m-d H:i:s");

        // SQL query to update attendance and check for late attendance
        $sql = "UPDATE attendances SET CardTimeIn = :currentTime, 
                Status = 
                    CASE
                        WHEN (TIMESTAMPDIFF(MINUTE, (SELECT DateTime FROM schedules WHERE ScheduleId = attendances.ScheduleId), :currentTime) > 30) THEN 2  -- Late
                        WHEN (:currentTime < (SELECT DateTime FROM schedules WHERE ScheduleId = attendances.ScheduleId)) THEN 0 -- Class not started
                        ELSE 1 -- On time
                    END
                WHERE 
                    StudentId = (SELECT StudentId FROM students WHERE Card = :cardId)
                    AND ScheduleId IN (SELECT ScheduleId FROM schedules WHERE DateTime = :scheduleTime)";

        // Prepare and execute the query
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(':currentTime', $currentDateTime);
        $stmt->bindParam(':cardId', $cardId);
        $stmt->bindParam(':scheduleTime', $currentDateTime); // Use the same current time for checking schedule
        $stmt->execute();

        // Check the status of attendance update
        $rowsAffected = $stmt->rowCount();

        if ($rowsAffected > 0) {
            // Attendance successfully updated
            $attendanceStatus = $stmt->fetchColumn(); // Fetch the status

            if ($attendanceStatus == 2) {
                // Late notification
                return "You are late for the class!";
            } elseif ($attendanceStatus == 0) {
                // Class not started notification
                return "Class hasn't started yet!";
            } else {
                // On time notification
                return "You have successfully attended the class on time!";
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

    return $attendanceSuccess;
}

// END : yoana yang tambahin

// Define the function to get student by card ID
function getStudentByCardId($cardId)
{
    // Database connection settings
    $connection = getConnection();

    try {
        // SQL query to retrieve user with the given card ID
        $sql = "SELECT * FROM students INNER JOIN users ON students.StudentId = users.StudentId WHERE Card = :cardId";

        // Prepare and execute the query
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(':cardId', $cardId);
        $stmt->execute();

        // Fetch the user
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Close the connection
        $connection = null;

        // Return the user as JSON data
        return json_encode($user);
    } catch (PDOException $e) {
        // Handle query execution errors
        echo "Error: " . $e->getMessage();
        return null;
    }
}
?>
