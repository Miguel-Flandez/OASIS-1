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

$result = $conn->query("SELECT firstname, lastname, middlename, username, accountname, password FROM accounts_employee");
$accounts = [];

while ($row = $result->fetch_assoc()) {
    $accounts[] = $row;
}

echo json_encode($accounts);

$result->close();
$conn->close();
?>