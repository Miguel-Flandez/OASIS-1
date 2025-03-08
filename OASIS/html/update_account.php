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

// Get POST data
$studentnumber = $_POST['studentnumber'];
$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$middlename = $_POST['middlename'];
$level = $_POST['level'];
$username = $_POST['username'];
$accountname = $_POST['accountname'];
$paymentplan = $_POST['paymentplan'];
$password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

$stmt = $conn->prepare("UPDATE accounts_user SET 
    studentnumber = ?, 
    firstname = ?, 
    lastname = ?, 
    middlename = ?, 
    level = ?, 
    username = ?, 
    accountname = ?, 
    paymentplan = ?" . 
    ($password !== null ? ", password = ?" : "") . 
    " WHERE studentnumber = ?");

if ($password !== null) {
    $stmt->bind_param("ssssssssss", 
        $studentnumber,
        $firstname,
        $lastname,
        $middlename,
        $level,
        $username,
        $accountname,
        $paymentplan,
        $password,
        $studentnumber
    );
} else {
    $stmt->bind_param("sssssssss", 
        $studentnumber,
        $firstname,
        $lastname,
        $middlename,
        $level,
        $username,
        $accountname,
        $paymentplan,
        $studentnumber
    );
}

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $stmt->error]);
}

$stmt->close();
$conn->close();
?>