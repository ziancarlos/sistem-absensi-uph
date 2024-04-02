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

// Define the function to register a card based on StudentId
function registerCard($studentId, $cardId)
{
    // Database connection settings
    $connection = getConnection();

    try {
        // Check if cardId is already registered
        if (isCardExist($cardId)) {
            // CardId is already registered, return error message
            return "cardId sudah terdeteksi di database";
        }

        // Check if studentId exists and has 11 digits
        if (!isStudentExist($studentId) || strlen($studentId) !== 11) {
            // Either studentId is not found or it doesn't have 11 digits
            return "studentId tidak ditemukan di database";
        }

        // SQL query to update the Card for the specified StudentId
        $sqlUpdateCard = "UPDATE students SET Card = :cardId WHERE StudentId = :studentId";

        // Prepare and execute the query
        $stmtUpdateCard = $connection->prepare($sqlUpdateCard);
        $stmtUpdateCard->bindParam(':cardId', $cardId);
        $stmtUpdateCard->bindParam(':studentId', $studentId);
        $success = $stmtUpdateCard->execute();

        // Close the connection
        $connection = null;

        // Return true if card registration was successful, "updated" if card was updated
        return $success ? "updated" : false;
    } catch (PDOException $e) {
        // Handle query execution errors
        echo "Error: " . $e->getMessage();
        return false;
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

// Function to check if studentId exists in the database
function isStudentExist($studentId)
{
    // Database connection settings
    $connection = getConnection();

    try {
        // SQL query to check if the studentId exists
        $sql = "SELECT COUNT(*) FROM students WHERE StudentId = :studentId";

        // Prepare and execute the query
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(':studentId', $studentId);
        $stmt->execute();

        // Fetch the count of rows
        $count = $stmt->fetchColumn();

        // Close the connection
        $connection = null;

        // Return true if studentId exists, false otherwise
        return ($count > 0);
    } catch (PDOException $e) {
        // Handle query execution errors
        echo "Error: " . $e->getMessage();
        return false;
    }
}

// Usage example:
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registerCard'])) {
    // Get the StudentId and Card from the POST parameters
    $studentId = $_POST['studentId'];
    $cardId = $_POST['cardId'];

    // Call the function to register the card
    $result = registerCard($studentId, $cardId);

    // Prepare and send response to Python based on the result
    if ($result === true) {
        // Card registration success
        echo json_encode(array("success" => "Card registered successfully"));
    } elseif ($result === "updated") {
        // Card update success
        echo json_encode(array("success" => "Card updated successfully"));
    } else {
        // Card registration failure, send error message to Python
        echo json_encode(array("error" => $result));
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
