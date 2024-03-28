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

// Define the function to get student by card ID
function getStudentByCardId($cardId)
{
    // Database connection settings
    $connection = getConnection();

    try {
        // SQL query to retrieve user with the given card ID
        $sql = "SELECT * FROM students WHERE Card = :cardId";

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