<?php
include "../includes/connection.php"; // Include your database connection code here

if (isset($_GET['clientname'])) {
    $clientName = $_GET['clientname'];

    // Query the database to fetch contact person and department information
    $stmt = $conn->prepare("SELECT contactperson, department FROM client WHERE clientname = ?");
    $stmt->bind_param("s", $clientName);
    $stmt->execute();
    $stmt->bind_result($contactPerson, $department);

    if ($stmt->fetch()) {
        // Client found, return the information as JSON
        $response = array(
            'success' => true,
            'contactperson' => $contactPerson,
            'department' => $department
        );
    } else {
        // Client not found
        $response = array('success' => false);
    }

    // Encode the response as JSON and echo it
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    echo json_encode(array('success' => false));
}

// Close the database connection
$stmt->close();
$conn->close();
?>
