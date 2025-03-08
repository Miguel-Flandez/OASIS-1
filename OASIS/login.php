<?php
session_start(); // Start a session to track logged-in users

// Database connection parameters
$host = "localhost";
$username = "root";
$password = "password"; // Update if you’ve set a password
$database = "oasis";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to UTF-8
$conn->set_charset("utf8mb4");

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input_username = $_POST['username'];
    $input_password = $_POST['password'];

    // Check accounts_user (student) table first
    $stmt = $conn->prepare("SELECT username, password FROM accounts_user WHERE username = ?");
    $stmt->bind_param("s", $input_username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        if ($row['password'] === $input_password) {
            // Student login successful
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $input_username;
            $_SESSION['account_type'] = 'student';
            header("Location: html/student-home.php");
            exit;
        }
    }
    $stmt->close();

    // Check accounts_employee table
    $stmt = $conn->prepare("SELECT username, password FROM accounts_employee WHERE username = ?");
    $stmt->bind_param("s", $input_username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        if ($row['password'] === $input_password) {
            // Employee login successful
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $input_username;
            $_SESSION['account_type'] = 'employee';
            header("Location: html/employee-home.php");
            exit;
        }
    }
    $stmt->close();

    // Check accounts_admin table
    $stmt = $conn->prepare("SELECT username, password FROM accounts_admin WHERE username = ?");
    $stmt->bind_param("s", $input_username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        if ($row['password'] === $input_password) {
            // Admin login successful
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $input_username;
            $_SESSION['account_type'] = 'admin';
            header("Location: html/admin-home.php"); // Redirect to admin home
            exit;
        }
    }
    $stmt->close();

    // If no match in any table, credentials are invalid
    header("Location: index.html?error=invalid");
    exit;
} else {
    // If accessed directly, redirect to login page
    header("Location: index.html");
    exit;
}

$conn->close();
?>