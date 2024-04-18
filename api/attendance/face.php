<?php
require_once ("../../helper/dbHelper.php");
date_default_timezone_set('Asia/Jakarta');


// Check if it's a POST request and attendance action is requested
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['attendance'])) {
    // Get the card ID from the POST parameters
    $faceId = $_POST['faceId'];

    // Call the function to validate the card ID and update attendance
    $result = checkAndUpdateAttendance($faceId);

    // Prepare and send response to client based on the result
    if ($result !== "") {
        // Attendance update success or failure, send appropriate message
        http_response_code(200); // OK
        echo json_encode(array("message" => $result));
    } else {
        // Attendance update failure, send error message
        http_response_code(500); // Internal Server Error
        echo json_encode(array("error" => "Unknown error occurred."));
    }
} else {
    // Method not allowed for other request types
    http_response_code(405); // Method Not Allowed
    echo json_encode(array("error" => "Method not allowed."));
}

/**
 * Validates the card ID, checks student enrollment, and updates attendance accordingly.
 * 
 * @param string $faceId The card ID to be validated.
 * @return string A message indicating the result of the attendance update.
 */
function checkAndUpdateAttendance($faceId)
{
    try {
        // Connect to the database
        $connection = getConnection();

        // Query to check if the card ID is registered to any student
        $sqlCheckCard = "SELECT * FROM students INNER JOIN users ON students.StudentId = users.StudentId WHERE Face = :faceId";
        $stmtCheckCard = $connection->prepare($sqlCheckCard);
        $stmtCheckCard->bindParam(':faceId', $faceId);
        $stmtCheckCard->execute();
        $student = $stmtCheckCard->fetch(PDO::FETCH_ASSOC);

        // If student is found
        if ($student) {
            // Get the current date and time
            $currentDate = date('Y-m-d');
            $currentTime = date('H:i:s');

            // Check if the student is enrolled in any class on the current day
            $sqlCheckEnrollment = "SELECT * FROM enrollments 
                                    INNER JOIN schedules ON enrollments.CourseId = schedules.CourseId 
                                    INNER JOIN courses ON enrollments.CourseId = courses.CourseId
                                    WHERE enrollments.StudentId = :studentId 
                                    AND schedules.Date = :currentDate 
                                    AND schedules.StartTime <= :currentTime 
                                    AND schedules.EndTime >= :currentTime";
            $stmtCheckEnrollment = $connection->prepare($sqlCheckEnrollment);
            $stmtCheckEnrollment->bindParam(':studentId', $student['StudentId']);
            $stmtCheckEnrollment->bindParam(':currentDate', $currentDate);
            $stmtCheckEnrollment->bindParam(':currentTime', $currentTime);
            $stmtCheckEnrollment->execute();
            $enrollment = $stmtCheckEnrollment->fetch(PDO::FETCH_ASSOC);

            // If student is enrolled in a class on the current day
            if ($enrollment) {
                // Check if attendance has already been recorded for the student today
                $sqlCheckAttendance = "SELECT * FROM attendances 
                                        WHERE StudentId = :studentId 
                                        AND ScheduleId = :scheduleId 
                                        AND (FaceTimeIn IS NOT NULL OR FingerprintTimeIn IS NOT NULL OR CardTimeIn IS NOT NULL)";
                $stmtCheckAttendance = $connection->prepare($sqlCheckAttendance);
                $stmtCheckAttendance->bindParam(':studentId', $student['StudentId']);
                $stmtCheckAttendance->bindParam(':scheduleId', $enrollment['ScheduleId']);
                $stmtCheckAttendance->execute();
                $existingAttendance = $stmtCheckAttendance->fetch(PDO::FETCH_ASSOC);

                if ($existingAttendance) {
                    // Attendance already recorded using FaceTimeIn, FingerprintTimeIn, or CardTimeIn
                    return $student["Name"] . "sudah masuk ke kelas " . $enrollment["Name"] . ".";
                } else {
                    // Update attendance in the attendances table
                    $attendanceDate = date('Y-m-d H:i:s');
                    $attendanceStatus = ($currentTime <= date('H:i:s', strtotime($enrollment['StartTime'] . '+15 minutes'))) ? 1 : 3; // Mark as present if within 15 minutes, otherwise mark as late

                    // Query to update attendance
                    $sqlUpdateAttendance = "UPDATE attendances 
                                            SET FaceTimeIn = :attendanceDate, Status = :attendanceStatus 
                                            WHERE StudentId = :studentId AND ScheduleId = :scheduleId";
                    $stmtUpdateAttendance = $connection->prepare($sqlUpdateAttendance);
                    $stmtUpdateAttendance->bindParam(':attendanceDate', $attendanceDate);
                    $stmtUpdateAttendance->bindParam(':attendanceStatus', $attendanceStatus);
                    $stmtUpdateAttendance->bindParam(':studentId', $student['StudentId']);
                    $stmtUpdateAttendance->bindParam(':scheduleId', $enrollment['ScheduleId']);
                    $stmtUpdateAttendance->execute();

                    // Return success message
                    $message = "Absensi " . $student["Name"] . " di kelas " . $enrollment["Name"] . "telah direkap.";

                    if ($attendanceStatus == 3) {
                        $message .= " Mahasiswa telat.";
                    }

                    return $message;
                }
            } else {
                // Student is not enrolled in any class on the current day or arrived more than 15 minutes after the class starts
                return $student["Name"] . " tidak terdaftar di kelas manapun.";
            }
        } else {
            // Card ID is not registered to any student
            return "Muka tidak terasosiasi dengan mahasiswa.";
        }
    } catch (PDOException $e) {
        echo $e;
        // Handle database connection or query execution errors
        return $e;
    }
}