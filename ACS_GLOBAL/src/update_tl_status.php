<?php
include "connection.php"; // Include your database connection code

// Check if POST data is received
if (isset($_POST['projectId']) && isset($_POST['tlStatus'])) {
    $projectId = $_POST['projectId'];
    $tlStatus = $_POST['tlStatus'];

    // Update the tl_status in the projects table
    $updateSql = "UPDATE projects SET TL_STATUS = ? WHERE PROJECTID = ?";
    $stmt = $conn->prepare($updateSql);

    if ($stmt === false) {
        echo "failure"; // Failed to prepare statement
    } else {
        // Bind the parameters
        $stmt->bind_param("ss", $tlStatus, $projectId);

        // Execute the update statement
        if ($stmt->execute() === TRUE) {
            echo "success"; // Status updated successfully
        } else {
            echo "failure"; // Status update failed
        }

        // Close the statement
        $stmt->close();
    }
} else {
    echo "Invalid data received"; // Invalid POST data
}
?>
