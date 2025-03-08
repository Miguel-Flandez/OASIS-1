<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
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

// Fetch user data
$logged_in_username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT studentnumber, firstname, lastname, level FROM accounts_user WHERE username = ?");
$stmt->bind_param("s", $logged_in_username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Prepare variables for display
$student_number = $user['studentnumber'] ?? 'N/A';
$full_name = ($user['firstname'] ?? '') . ' ' . ($user['lastname'] ?? '');
$level = $user['level'] ?? 'N/A';

// Use the student number as-is with dash format
$tuition_table = $student_number . '_tuition';
$misc_table = $student_number . '_misc';

// Fetch total from tuition table
$tuition_query = "SELECT SUM(amount) as tuition_total FROM `$tuition_table`";
$tuition_result = $conn->query($tuition_query);
$tuition_total = $tuition_result ? ($tuition_result->fetch_assoc()['tuition_total'] ?? 0) : 0;

// Fetch total from miscellaneous table
$misc_query = "SELECT SUM(amount) as misc_total FROM `$misc_table`";
$misc_result = $conn->query($misc_query);
$misc_total = $misc_result ? ($misc_result->fetch_assoc()['misc_total'] ?? 0) : 0;

// Calculate total balance
$balance = $tuition_total + $misc_total;

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/student-home.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dosis:wght@200..800&family=Fjalla+One&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Oswald:wght@200..700&family=Playwrite+IN:wght@100..400&family=Poiret+One&family=Roboto+Slab:wght@100..900&family=Roboto:ital,wght@0,100..900;1,100..900&family=Smooch+Sans:wght@100..900&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/de323b5912.js" crossorigin="anonymous"></script>
    <title>Home</title>
</head>
<body>
    <header>
        <div id="left">
            <img src="../assets/images/oasis-logo-Photoroom.jpg" alt="Oakwood" id="logo">
        </div>
        <h3 id="home-button">Home</h3>
        <h3 id="opc-button">Online Payment Center</h3>
        <h3 id="profile-button">User Profile</h3>
        <div id="right">
            <i class="fa-solid fa-bell"></i>
            <button id="logout" onclick="if(confirm('Are you sure you want to log out?')) window.location.href='../logout.php';">Logout</button>
        </div>
    </header>

    <div id="homescreen">   
        <div id="student-info">
            <p>Basic Student Information</p>
            <div id="dpandinfo">
                <div id="student-information">
                    <h3>Student Information</h3>
                    <div id="photo-and-info">
                        <img src="https://st3.depositphotos.com/6672868/13701/v/450/depositphotos_137014128-stock-illustration-user-profile-icon.jpg" alt="dp" id="student-dp">
                        <div id="info-list">
                            <div id="info">
                                <p>Student Number: </p>
                                <p id="student-number"><?php echo htmlspecialchars($student_number); ?></p>
                            </div>
                            <div id="info">
                                <p>Name: </p>
                                <p id="student-name"><?php echo htmlspecialchars($full_name); ?></p>
                            </div>
                            <div id="info">
                                <p>Level: </p>
                                <p id="level"><?php echo htmlspecialchars($level); ?></p>
                            </div>
                            <div id="info">
                                <p>Balance: </p>
                                <p id="balance"><?php echo number_format($balance, 2) . ' PHP'; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="notif-modal" class="hide">
                    <div id="notifs">
                        <div id="notif"><h3>Reminder</h3><p>Magbayad ka na please</p></div>
                        <div id="notif"><h3>Reminder</h3><p>Magbayad ka na sabi e</p></div>
                        <div id="notif"><h3>Reminder</h3><p>Alam ko kung saan ka nakatira</p></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../js/common-functions.js"></script>
    <script src="../js/routing-student.js"></script>
</body>
</html>