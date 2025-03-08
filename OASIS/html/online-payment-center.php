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
$stmt = $conn->prepare("SELECT studentnumber, firstname, lastname FROM accounts_user WHERE username = ?");
$stmt->bind_param("s", $logged_in_username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Prepare variables for display
$student_number = $user['studentnumber'] ?? 'N/A';
$full_name = ($user['firstname'] ?? '') . ' ' . ($user['lastname'] ?? '');

// Use the student number as-is with dash format
$tuition_table = $student_number . '_tuition';
$misc_table = $student_number . '_misc';

// Fetch tuition fees
$tuition_query = "SELECT fee, duedate, amount FROM `$tuition_table`";
$tuition_result = $conn->query($tuition_query);

// Fetch miscellaneous fees
$misc_query = "SELECT fee, duedate, amount FROM `$misc_table`";
$misc_result = $conn->query($misc_query);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/OPC.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dosis:wght@200..800&family=Fjalla+One&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Oswald:wght@200..700&family=Playwrite+IN:wght@100..400&family=Poiret+One&family=Roboto+Slab:wght@100..900&family=Roboto:ital,wght@0,100..900;1,100..900&family=Smooch+Sans:wght@100..900&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/de323b5912.js" crossorigin="anonymous"></script>
    <title>Online Payment Center</title>
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

    <div id="payor-infoAndfees">
        <div id="payor-info">
            <h3>Payor Information</h3>
            <div id="forms">
                <div id="form-pair">
                    <label for="student-number">Student Number: </label>
                    <input type="text" name="student-number" id="student-number" value="<?php echo htmlspecialchars($student_number); ?>" readonly>
                </div>
                <div id="form-pair">
                    <label for="name">Name: </label>
                    <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($full_name); ?>" readonly>
                </div>
            </div>
        </div>

        <div id="tuition-fees">
            <h3>Tuition</h3>
            <table id="tuition-table">
                <thead>
                    <tr>
                        <th></th>
                        <th>Fee</th>
                        <th>Due Date</th>
                        <th>Amount (PHP)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($tuition_result && $tuition_result->num_rows > 0) {
                        while ($row = $tuition_result->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td><input type="checkbox" class="fee-checkbox" data-amount="' . htmlspecialchars($row['amount']) . '"></td>';
                            echo '<td>' . htmlspecialchars($row['fee']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['duedate']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['amount']) . '</td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="4">No tuition fees found.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>

            <h3>Miscellaneous Fees</h3>
            <table id="misc-table">
                <thead>
                    <tr>
                        <th></th>
                        <th>Fee</th>
                        <th>Due Date</th>
                        <th>Amount (PHP)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($misc_result && $misc_result->num_rows > 0) {
                        while ($row = $misc_result->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td><input type="checkbox" class="fee-checkbox" data-amount="' . htmlspecialchars($row['amount']) . '"></td>';
                            echo '<td>' . htmlspecialchars($row['fee']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['duedate']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['amount']) . '</td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="4">No miscellaneous fees found.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>

            <div id="total-amount">
                <h3>Total Amount (PHP): <span id="total">0</span></h3>
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
    <script src="../js/routing-student.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const checkboxes = document.querySelectorAll('.fee-checkbox');
            const totalSpan = document.getElementById('total');

            function updateTotal() {
                let total = 0;
                checkboxes.forEach(checkbox => {
                    if (checkbox.checked) {
                        total += parseFloat(checkbox.getAttribute('data-amount'));
                    }
                });
                totalSpan.textContent = total.toFixed(2); // Display with 2 decimal places
            }

            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateTotal);
            });
        });
    </script>
</body>
</html>