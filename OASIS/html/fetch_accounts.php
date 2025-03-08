<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection parameters
$host = "localhost";
$username = "root";
$password = "password"; // Default XAMPP password is empty, update if you've set a password
$database = "oasis";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed: ' . $conn->connect_error]);
    exit;
}

// Set charset to UTF-8
$conn->set_charset("utf8mb4");

// Fetch accounts from the database
$sql = "SELECT studentnumber, firstname, lastname, middlename, level, username, accountname, password, paymentplan FROM accounts_user";
$result = $conn->query($sql);

if (!$result) {
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode(['error' => 'Query failed: ' . $conn->error]);
    exit;
}

// Convert result to array
$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// Set header and output JSON
header('Content-Type: application/json');
echo json_encode($data);

$conn->close();
?>