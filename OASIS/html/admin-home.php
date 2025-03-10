<?php
session_start();

// Check if user is logged in and is an admin
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['account_type'] !== 'admin') {
    header("Location: ../index.html");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/admin-home.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dosis:wght@200..800&family=Fjalla+One&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Oswald:wght@200..700&family=Playwrite+IN:wght@100..400&family=Poiret+One&family=Roboto+Slab:wght@100..900&family=Roboto:ital,wght@0,100..900;1,100..900&family=Smooch+Sans:wght@100..900&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/de323b5912.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/luxon@3/build/global/luxon.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-luxon"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

    <title>Admin Dashboard</title>
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

    <div id="dashboard-top">
        <!-- <label for="data-analytics"></label>
        <select name="data-analytics" id="data-analytics">
            <option value="statistics">Statistics</option>
            <option value="payment-trends">Payment Trends</option>
        </select> -->

        <div id="info-box" class="one">
            <p>Outstanding Balance: </p>
            <h2 id="outstanding-balances"></h2>
        </div>
        <div id="info-box" class="two">
            <p>Overdue Accounts: </p>
            <h2 id="overdue-accounts"></h2>
        </div>

        <div id="info-box" class="three">
            <p>Total Enrolled Students: </p>
            <h2 id="payments-on-process"></h2>
        </div>

        <div id="info-box" class="four">
            <p>Revenue: </p>
            <h2 id="payments-on-process"></h2>
        </div>
        <!-- <h2 id="date-and-time"></h2> -->
    </div>

    <!-- <div id="dashboard-info">
        <div id="info-boxes">
            <div id="info-box">
                <p>Currently Enrolled Students: </p>
                <h2 id="enrolled-students"></h2>
            </div>
            <div id="info-box">
                <p>Outstanding Balance: </p>
                <h2 id="outstanding-balances"></h2>
            </div>
            <div id="info-box">
                <p>Overdue Accounts: </p>
                <h2 id="overdue-accounts"></h2>
            </div>
            <div id="info-box">
                <p>Current School Year Quarter: </p>
                <h2 id="current-school-year"></h2>
            </div>
            <div id="info-box">
                <p>Payments on Process: </p>
                <h2 id="payments-on-process"></h2>
            </div>
        </div>
    </div> -->

    <div id="graph-container">

    <div>
        <canvas id="paymentChart"></canvas>
    </div>
    <div>
        <canvas id="paymentDateChart"></canvas>
    </div>
    
    </div>

    <i class="fa-solid fa-comment-dots"></i>

    <div id="chatbox-modal" class="chatbox hide">       
        <div class="chat-content" id="chat-content"></div>
        <div class="chat-input">
            <input type="text" id="chat-message" placeholder="Type a message..." />
            <button id="send-message"><i class="fa-solid fa-paper-plane"></i></button>
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
    <script src="../js/admin-dashboard.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js"></script>
</body>
</html>