<?php
header('Content-Type: application/json');
$host = "localhost";
$username = "root";
$password = "password";
$database = "oasis";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    echo json_encode(['error' => 'Connection failed: ' . $conn->connect_error]);
    exit;
}

$studentNumber = $_GET['studentnumber'];
$stmt = $conn->prepare("SELECT studentnumber, firstname, lastname, middlename, level, username, accountname, paymentplan FROM accounts_user WHERE studentnumber = ?");
$stmt->bind_param("s", $studentNumber);
$stmt->execute();
$result = $stmt->get_result();
$account = $result->fetch_assoc();

echo json_encode($account);
$stmt->close();
$conn->close();
?>