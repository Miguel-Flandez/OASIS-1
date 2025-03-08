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
$history_table = $studentNumber . '_history';
$stmt = $conn->prepare("SELECT receiptnumber, dateoftransaction, amountpaid, fee, paymentstatus FROM `$history_table`");
$stmt->execute();
$result = $stmt->get_result();
$history = $result->fetch_all(MYSQLI_ASSOC);

echo json_encode($history);
$stmt->close();
$conn->close();
?>