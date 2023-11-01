<?php
// Include your database connection code here
include_once "../connection.php";

// Define an array to store the data
$data = array();

// Replace 'YOUR_GROUP_COLUMN_NAME' with the actual column name in your 'new' table that represents the group of project IDs
$groupColumnName = 'YOUR_GROUP_COLUMN_NAME';

// Replace 'YOUR_COMPLETED_COLUMN_NAME' with the actual column name in your 'new' table that represents the completed values
$completedColumnName = 'YOUR_COMPLETED_COLUMN_NAME';

// Query to get the sum of completed values grouped by the project ID group
$sql = "SELECT $groupColumnName, SUM($completedColumnName) as completed_value FROM new GROUP BY $groupColumnName";

$result = mysqli_query($conn, $sql);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Add each group and its completed value to the data array
        $data[] = array(
            'group' => $row[$groupColumnName],
            'completed_value' => (int) $row['completed_value']
        );
    }
}

// Close the database connection
mysqli_close($conn);

// Return the data as JSON
header('Content-Type: application/json');
echo json_encode($data);
?>
