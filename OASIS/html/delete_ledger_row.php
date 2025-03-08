<?php
header('Content-Type: application/json');
$host = "localhost";
$username = "root";
$password = "password";
$database = "oasis";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Connection failed: ' . $conn->connect_error]);
    exit;
}

$account = $_POST['account'];
$table = $_POST['table'] === 'tuition' ? $account . '_tuition' : $account . '_misc';
$id = $_POST['id'];

$stmt = $conn->prepare("DELETE FROM `$table` WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $conn->error]);
}

$stmt->close();
$conn->close();
?>