<?php
require_once ("helper/dbHelper.php");

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

// Check if there is a GET request named 'getAllStudents'
if (isset ($_GET['getAllStudents'])) {
    // Call the function to get all students
    $allStudentsJson = getAllStudents();

    // Check if student data is found
    if ($allStudentsJson) {
        // Print the JSON data
        echo $allStudentsJson;
    } else {
        // Print JSON with an error message
        echo json_encode(array("error" => "No students found"));
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset ($_POST['registerCard'])) {
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

// Define the function to get all students
function getAllStudents()
{
    // Database connection settings
    $connection = getConnection();

    try {
        // SQL query to retrieve all students
        $sql = "SELECT * FROM students INNER JOIN users ON students.StudentId = users.StudentId";

        // Prepare and execute the query
        $stmt = $connection->query($sql);

        // Fetch all students
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Close the connection
        $connection = null;

        // Return the students as JSON data
        return json_encode($students);
    } catch (PDOException $e) {
        // Handle query execution errors
        echo "Error: " . $e->getMessage();
        return null;
    }
}

// Define the function to get student by card ID
function getStudentByCardId($cardId)
{
    // Database connection settings
    $connection = getConnection();

    try {
        // SQL query to retrieve user with the given card ID
        $sql = "SELECT * FROM students INNER JOIN users ON students.StudentId = users.StudentId  WHERE Card = :cardId";

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