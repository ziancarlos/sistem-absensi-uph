<?php
require_once ("../helper/dbHelper.php");


// API handling
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['cardId'])) {
    // Handle GET request to retrieve student by card ID
    $cardId = $_GET['cardId'];
    $student = getStudentByCardId($cardId);
    if ($student) {
        echo json_encode($student);
    } else {
        echo json_encode(array("error" => "Student not found"));
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateCard'])) {
    // Handle POST request to update card
    echo json_encode(updateCard($_POST['studentId'], $_POST['cardId']));
}

/**
 * Update a card for a specific student in the system.
 * 
 * @param string $studentId The unique identifier of the student.
 * @param string $cardId The unique identifier of the card to be update.
 * @return array An associative array containing the result of the registration attempt.
 */
function updateCard($studentId, $cardId)
{
    try {
        // Validate input
        if (!preg_match("/^\d{11,}$/", $studentId)) {
            return array("error" => "Student ID must consist of 11 or more digits");
        }

        // Database connection
        $connection = getConnection();

        // Check if the card is already registered
        if (isCardExist($cardId)) {
            return array("error" => "Card ID has already been registered");
        }

        // Check if the student exists
        if (!isStudentExist($studentId)) {
            return array("error" => "Student ID not found in the database");
        }

        // SQL query to update the card for the specified student
        $sqlUpdateCard = "UPDATE students SET Card = :cardId WHERE StudentId = :studentId";

        // Prepare and execute the query
        $stmtUpdateCard = $connection->prepare($sqlUpdateCard);
        $stmtUpdateCard->bindParam(':cardId', $cardId);
        $stmtUpdateCard->bindParam(':studentId', $studentId);
        $success = $stmtUpdateCard->execute();

        // Close the connection
        $connection = null;

        // Return success message if card registration was successful, otherwise return error message
        return $success ? array("success" => "Student card updated successfully") : array("error" => "Failed to register card");
    } catch (PDOException $e) {
        // Handle database errors
        return array("error" => "Database error: " . $e->getMessage());
    }
}

/**
 * Checks if a card ID exists in the database.
 * 
 * @param string $cardId The card ID to check.
 * @return bool True if the card ID exists, false otherwise.
 */
function isCardExist($cardId)
{
    try {
        // Database connection
        $connection = getConnection();

        // SQL query to check if the card ID exists
        $sql = "SELECT COUNT(*) FROM students WHERE Card = :cardId";

        // Prepare and execute the query
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(':cardId', $cardId);
        $stmt->execute();

        // Fetch the count of rows
        $count = $stmt->fetchColumn();

        // Close the connection
        $connection = null;

        // Return true if the card ID exists, false otherwise
        return ($count > 0);
    } catch (PDOException $e) {
        // Handle database errors
        return true; // Assume card ID exists to avoid any risk
    }
}

/**
 * Checks if a student ID exists in the database.
 * 
 * @param string $studentId The student ID to check.
 * @return bool True if the student ID exists, false otherwise.
 */
function isStudentExist($studentId)
{
    try {
        // Database connection
        $connection = getConnection();

        // SQL query to check if the student ID exists
        $sql = "SELECT COUNT(*) FROM students WHERE StudentId = :studentId";

        // Prepare and execute the query
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(':studentId', $studentId);
        $stmt->execute();

        // Fetch the count of rows
        $count = $stmt->fetchColumn();

        // Close the connection
        $connection = null;

        // Return true if the student ID exists, false otherwise
        return ($count > 0);
    } catch (PDOException $e) {
        // Handle database errors
        return false;
    }
}

/**
 * Retrieves student information by card ID.
 * 
 * @param string $cardId The card ID to retrieve student information.
 * @return array|null An associative array containing student information if found, 
 *                     or null if no student is found or an error occurs.
 */
function getStudentByCardId($cardId)
{
    try {
        // Database connection
        $connection = getConnection();

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

        // Return the user information
        return $user ? $user : null;
    } catch (PDOException $e) {
        // Handle database errors
        return null;
    }
}