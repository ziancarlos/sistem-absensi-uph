<?php

function authorization($permittedRole, $userId)
{
    $connection = getConnection();
    $statement = null;

    try {
        $sql = "SELECT
CASE
WHEN role = 0 THEN 'student'
WHEN role = 1 THEN 'lecturer'
WHEN role = 2 THEN 'admin'
ELSE 'unknown'
END AS user_role
FROM users
WHERE UserId = :userId FOR UPDATE";

        $statement = $connection->prepare($sql);
        $statement->bindParam('userId', $userId);
        $statement->execute();

        // Fetch the user's role from the result
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            // Check if the fetched role is in the permitted roles array
            if (!in_array($result['user_role'], $permittedRole)) {
                // User does not have the required authorization
                return false;
            }
        } else {
            // User not found or role not defined
            return false;
        }

    } catch (PDOException $e) {
        // Handle exceptions
        throw $e;
    } finally {
        // Close statement and connection
        if ($statement !== null) {
            $statement->closeCursor();
        }
        $connection = null; // Close connection
    }

    return true;
}