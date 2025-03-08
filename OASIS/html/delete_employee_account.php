<?php
header('Content-Type: application/json');

$host = "localhost";
$username = "root";
$password = "password"; // Update with your actual DB password
$database = "oasis";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Connection failed: ' . $conn->connect_error]);
    exit;
}

$username = $_POST['username'] ?? '';

if (empty($username)) {
    echo json_encode(['success' => false, 'error' => 'Username is required']);
    $conn->close();
    exit;
}

$stmt = $conn->prepare("DELETE FROM accounts_employee WHERE username = ?");
$stmt->bind_param("s", $username);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to delete employee account: ' . $conn->error]);
}

$stmt->close();
$conn->close();
?>