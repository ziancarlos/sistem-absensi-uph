<?php
require_once ("../helper/dbHelper.php");

/**
 * API for retrieving and updating student information based on card ID.
 *
 * This API provides endpoints for retrieving student information by card ID and updating student card information.
 * It handles GET and POST requests, returning JSON responses.
 * 
 * Endpoints:
 *  - GET /students?cardId={cardId} : Retrieves student information by card ID.
 *  - POST /students/update : Updates student card information.
 * 
 * @package StudentCardAPI
 */
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['cardId'])) {
    // Handle GET request to retrieve student by card ID
    $cardId = $_GET['cardId'];
    $student = getStudentByCardId($cardId);
    if ($student) {
        echo json_encode($student);
    } else {
        echo json_encode(array("error" => "Student not found"));
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['getAllStudents'])) {
    $students = getAllStudents();
    echo json_encode($students);
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle POST requests
    if (isset($_POST['updateCard'])) {
        // Update card information
        echo json_encode(updateCard($_POST['studentId'], $_POST['cardId']));
    } else if (isset($_POST['updateFace'])) {
        // Update face information
        echo json_encode(updateFace($_POST['studentId'], $_POST['faceId']));
    }
}

/**
 * Retrieves all students from the database.
 * 
 * @return array An associative array of all students from the database.
 */
function getAllStudents()
{
    try {
        // Database connection
        $connection = getConnection();

        // SQL query to retrieve all students
        $sql = "SELECT * FROM users INNER JOIN students ON users.StudentId = students.StudentId WHERE users.Status = 1 AND users.Role = 0";

        // Prepare and execute the query
        $stmt = $connection->prepare($sql);
        $stmt->execute();

        // Fetch all rows as an associative array
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Close the connection
        $connection = null;

        // Return the array of students
        return $students;
    } catch (PDOException $e) {
        // Handle database errors
        return array("error" => "Database error: " . $e->getMessage());
    }
}


/**
 * Update student face information.
 * 
 * @param string $studentId The unique identifier of the student.
 * @param string $faceId The unique identifier of the face to be updated.
 * @return array An associative array containing the result of the face update attempt.
 */
function updateFace($studentId, $faceId)
{
    try {
        // Check if the student ID format is valid
        if (!preg_match("/^\d{11,}$/", $studentId)) {
            return array("error" => "Student ID must consist of 11 or more digits");
        }

        // Check if the student exists
        if (!isStudentExist($studentId)) {
            return array("error" => "Student ID not found in the database");
        }

        // Database connection
        $connection = getConnection();

        // SQL query to update the face for the specified student
        $sqlUpdateFace = "UPDATE students SET Face = :faceId WHERE StudentId = :studentId";

        // Prepare and execute the query
        $stmtUpdateFace = $connection->prepare($sqlUpdateFace);
        $stmtUpdateFace->bindParam(':faceId', $faceId);
        $stmtUpdateFace->bindParam(':studentId', $studentId);
        $success = $stmtUpdateFace->execute();

        // Close the connection
        $connection = null;

        // Return success message if face update was successful, otherwise return error message
        return $success ? array("success" => "Student face updated successfully") : array("error" => "Failed to update student face");
    } catch (PDOException $e) {
        // Handle database errors
        return array("error" => "Database error: " . $e->getMessage());
    }
}

/**
 * Update student card information.
 * 
 * @param string $studentId The unique identifier of the student.
 * @param string $cardId The unique identifier of the card to be updated.
 * @return array An associative array containing the result of the card update attempt.
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

        // Return success message if card update was successful, otherwise return error message
        return $success ? array("success" => "Student card updated successfully") : array("error" => "Failed to update student card");
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