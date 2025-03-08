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

$firstName = $_POST['firstname'] ?? '';
$lastName = $_POST['lastname'] ?? '';
$middleName = $_POST['middlename'] ?? '';
$username = $_POST['username'] ?? '';
$accountName = $_POST['accountname'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($firstName) || empty($lastName) || empty($username) || empty($accountName)) {
    echo json_encode(['success' => false, 'error' => 'Required fields are missing']);
    $conn->close();
    exit;
}

$stmt = $conn->prepare("UPDATE accounts_employee SET firstname = ?, lastname = ?, middlename = ?, accountname = ?, password = ? WHERE username = ?");
$stmt->bind_param("ssssss", $firstName, $lastName, $middleName, $accountName, $password, $username);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to update employee account: ' . $conn->error]);
}

$stmt->close();
$conn->close();
?>