<?php
require_once ("../helper/dbHelper.php");

/**
 * Handles the login action for admin users.
 * 
 * This function checks if the request method is POST and if the 'login' action
 * parameter is set in the POST data. If both conditions are met, it hashes the
 * provided password using the MD5 algorithm, calls the login function, and encodes
 * the result as JSON before echoing it.
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    // Hash the password using MD5 algorithm
    $hashedPassword = md5($_POST['password']);

    // Call the login function and encode the result as JSON
    echo json_encode(login($_POST['email'], $hashedPassword));
} else {
    // Invalid request method or missing parameters
    http_response_code(400); // Bad Request
    echo json_encode(array("error" => "Invalid request."));
}

/**
 * Logs in an admin user.
 * 
 * @param string $email The email address of the admin.
 * @param string $password The hashed password of the admin.
 * @return array Returns an associative array containing the login result.
 */
function login($email, $password)
{
    try {
        // Establish database connection
        $connection = getConnection();

        // SQL query to retrieve admin user based on email, password, role, and status conditions
        $sql = "SELECT UserId, Name, Email FROM users WHERE email = :email AND password = :password AND role = 2 AND status = 1";

        // Prepare and execute the query
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->execute();

        // Fetch the admin user
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        // Close the database connection
        $connection = null;

        // Check if admin user exists
        if ($admin) {
            // Admin login successful, return success message along with user details
            return array("success" => "Admin login successful", "user" => $admin);
        } else {
            // Admin login failed
            return array("error" => "Invalid email or password");
        }
    } catch (PDOException $e) {
        // Handle database connection or query execution errors
        return array("error" => "Database error: " . $e->getMessage());
    }
}
?>