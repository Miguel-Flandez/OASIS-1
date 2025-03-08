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

// Handle password change
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $old_password = $_POST['old-password'];
    $new_password = $_POST['new-password'];
    $re_new_password = $_POST['re-new-password'];

    $logged_in_username = $_SESSION['username'];

    // Verify old password
    $stmt = $conn->prepare("SELECT password FROM accounts_user WHERE username = ?");
    $stmt->bind_param("s", $logged_in_username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user['password'] === $old_password) {
        if ($new_password === $re_new_password) {
            // Update password
            $update_stmt = $conn->prepare("UPDATE accounts_user SET password = ? WHERE username = ?");
            $update_stmt->bind_param("ss", $new_password, $logged_in_username);
            $update_stmt->execute();
            $update_stmt->close();
            $message = "Password updated successfully!";
        } else {
            $message = "New passwords do not match.";
        }
    } else {
        $message = "Old password is incorrect.";
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/userProfile.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dosis:wght@200..800&family=Fjalla+One&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Oswald:wght@200..700&family=Playwrite+IN:wght@100..400&family=Poiret+One&family=Roboto+Slab:wght@100..900&family=Roboto:ital,wght@0,100..900;1,100..900&family=Smooch+Sans:wght@100..900&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/de323b5912.js" crossorigin="anonymous"></script>
    <title>User Profile</title>
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

    <div id="student-information">
        <h3>Student Information</h3>
        <form method="POST" action="">
            <div id="info-list">
                <div id="info">
                    <p>Old Password: </p>
                    <input type="password" id="old-password" name="old-password" required>
                    <i class="fa-solid fa-key old"></i>
                </div>
                <div id="info">
                    <p>New Password: </p>
                    <input type="password" id="new-password" name="new-password" required>
                    <i class="fa-solid fa-key new"></i>
                </div>
                <div id="info">
                    <p>Re-type new password: </p>
                    <input type="password" id="re-new-password" name="re-new-password" required>
                    <i class="fa-solid fa-key retype"></i>
                </div>
            </div>
            <button id="save" type="submit">Save</button>
        </form>
        <?php if (isset($message)) { echo "<p>$message</p>"; } ?>
    </div>

    <div id="notif-modal" class="hide">
        <div id="notifs">
            <div id="notif">
                <h3>Reminder</h3>
                <p>Magbayad ka na please</p>
            </div>
            <div id="notif">
                <h3>Reminder</h3>
                <p>Magbayad ka na sabi e</p>
            </div>
            <div id="notif">
                <h3>Reminder</h3>
                <p>Alam namin bahay niyo</p>
            </div>
        </div>
    </div>

    <script src="../js/common-functions.js"></script>
    <script src="../js/routing-student.js"></script>
    <script src="../js/password.js"></script>
</body>
</html>