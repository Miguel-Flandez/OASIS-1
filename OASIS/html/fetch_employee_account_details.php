<?php
header('Content-Type: application/json');

$host = "localhost";
$username = "root";
$password = "password"; // Update with your actual DB password
$database = "oasis";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    echo json_encode(['error' => 'Connection failed: ' . $conn->connect_error]);
    exit;
}

$username = $_GET['username'] ?? '';
$stmt = $conn->prepare("SELECT firstname, lastname, middlename, username, accountname, password FROM accounts_employee WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$account = $result->fetch_assoc();

if ($account) {
    echo json_encode($account);
} else {
    echo json_encode(['error' => 'Employee account not found']);
}

$stmt->close();
$conn->close();
?>