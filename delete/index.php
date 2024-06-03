<?php
include '../config.php';

$conn = new mysqli($dbhost, $dbuser, $dbpass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "DELETE FROM schedule";
if ($conn->query($sql) === TRUE) {
    echo json_encode(['status' => 'success', 'message' => 'All data deleted successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Error deleting data: ' . $conn->error]);
}

$conn->close();
?>
