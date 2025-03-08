<?php
session_start();

// Check if user is logged in and is an admin
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['account_type'] !== 'admin') {
    header("Location: ../index.html");
    exit;
}

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

// Handle form submission
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $_POST['first-name'];
    $lastName = $_POST['last-name'];
    $middleName = $_POST['middle-name'] ?? '';
    $username = $_POST['username'];
    $accountName = $_POST['account-name'] ?? '';
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];

    if ($password === $confirmPassword) {
        // Prepare and execute the insert query
        $stmt = $conn->prepare("INSERT INTO accounts_employee (firstname, lastname, middlename, username, accountname, password) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $firstName, $lastName, $middleName, $username, $accountName, $password);

        if ($stmt->execute()) {
            $message = "Employee account created successfully!";
        } else {
            $message = "Error: " . $conn->error;
        }
        $stmt->close();
    } else {
        $message = "Passwords do not match!";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/create-account.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dosis:wght@200..800&family=Fjalla+One&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Oswald:wght@200..700&family=Playwrite+IN:wght@100..400&family=Poiret+One&family=Roboto+Slab:wght@100..900&family=Roboto:ital,wght@0,100..900;1,100..900&family=Smooch+Sans:wght@100..900&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/de323b5912.js" crossorigin="anonymous"></script>
    <title>Create Employee Account</title>
</head>
<body>
    <header>
        <div id="left">
            <img src="../assets/images/oasis-logo-Photoroom.jpg" alt="Oakwood" id="logo">
        </div>
        <h3 id="dashboard-button">Dashboard</h3>
        <h3 id="create-account">Create Account</h3>
        <h3 id="create-employee">Create Employee</h3>
        <h3 id="oasis-accounts">OASIS Accounts</h3>
        <h3 id="employee-accounts">Employee Accounts</h3>
        <div id="right">
            <i class="fa-solid fa-bell"></i>
            <button id="logout" onclick="if(confirm('Are you sure you want to log out?')) window.location.href='../logout.php';">Logout</button>
        </div>
    </header>

    <div id="accounts">
        <div id="student-information">
            <h3>Employee Account Information</h3>
            <div id="create-account-form">
                <form action="#" method="post">
                    <div class="form-row">
                        <label for="first-name">First Name:</label>
                        <input type="text" id="first-name" name="first-name" placeholder="Enter First Name" required>
                    </div>
                    <div class="form-row">
                        <label for="last-name">Last Name:</label>
                        <input type="text" id="last-name" name="last-name" placeholder="Enter Last Name" required>
                    </div>
                    <div class="form-row">
                        <label for="middle-name">Middle Name:</label>
                        <input type="text" id="middle-name" name="middle-name" placeholder="Enter Middle Name">
                    </div>
                    <div class="form-row">
                        <label for="username">Username:</label>
                        <input type="text" id="username" name="username" placeholder="Choose a Username" required>
                    </div>
                    <div class="form-row">
                        <label for="account-name">Account Name:</label>
                        <input type="text" id="account-name" name="account-name" placeholder="Enter Account Name" required>
                    </div>
                    <div class="form-row">
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" placeholder="Enter Password" required>
                    </div>
                    <div class="form-row">
                        <label for="confirm-password">Confirm Password:</label>
                        <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirm Password" required>
                    </div>
                    <button type="submit">Create Employee Account</button>
                </form>
                <?php if (!empty($message)) echo "<p>$message</p>"; ?>
            </div>
        </div>
    </div>

    <div id="notif-modal" class="hide">
        <div id="notifs">
            <div id="notif"><h3>Reminder</h3><p>Magbayad ka na please</p></div>
            <div id="notif"><h3>Reminder</h3><p>Magbayad ka na sabi e</p></div>
            <div id="notif"><h3>Reminder</h3><p>Alam namin bahay niyo</p></div>
        </div>
    </div>

    <script src="../js/common-functions.js"></script>
    <script src="../js/routing-admin.js"></script>
</body>
</html>