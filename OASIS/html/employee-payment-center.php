<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['account_type'] !== 'employee') {
    header("Location: ../index.html");
    exit;
}

// Database connection
$host = "localhost";
$username = "root";
$password = "password"; // Update if you've set a password
$database = "oasis";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch accounts for dropdown
$accounts = [];
$stmt = $conn->prepare("SELECT studentnumber FROM accounts_user");
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $accounts[] = $row['studentnumber'];
}
$stmt->close();

// Handle selected account and fetch tuition/misc data
$selectedAccount = isset($_GET['account']) ? $_GET['account'] : (count($accounts) > 0 ? $accounts[0] : '');
$tuitionData = [];
$miscData = [];

if ($selectedAccount) {
    $tuitionTable = $selectedAccount . '_tuition';
    $miscTable = $selectedAccount . '_misc';
    $tuitionStmt = $conn->prepare("SELECT id, fee, duedate, amount FROM `$tuitionTable`");
    $tuitionStmt->execute();
    $tuitionResult = $tuitionStmt->get_result();
    while ($row = $tuitionResult->fetch_assoc()) {
        $tuitionData[] = $row;
    }
    $tuitionStmt->close();

    $miscStmt = $conn->prepare("SELECT id, fee, duedate, amount FROM `$miscTable`");
    $miscStmt->execute();
    $miscResult = $miscStmt->get_result();
    while ($row = $miscResult->fetch_assoc()) {
        $miscData[] = $row;
    }
    $miscStmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/employee-payment-center.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dosis:wght@200..800&family=Fjalla+One&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Oswald:wght@200..700&family=Playwrite+IN:wght@100..400&family=Poiret+One&family=Roboto+Slab:wght@100..900&family=Roboto:ital,wght@0,100..900;1,100..900&family=Smooch+Sans:wght@100..900&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/de323b5912.js" crossorigin="anonymous"></script>
    <title>Employee Payment Center</title>
    <style>
        body {
            background-color: #e6f3d6; /* Light green background from screenshot */
            margin: 0;
            padding: 0;
        }

        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            color: white;
            z-index: 1000;
            height: 60px; /* Adjust based on your header height in dashboard.css */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3); /* Shadow at the bottom */
        }

        #payment-container {
            width: 80%;
            margin: 80px auto 20px; /* Adjusted top margin to clear fixed header (match header height + padding) */
            padding: 20px;
            background-color: white;
            box-shadow: 0 0 5px -1px;
            border-radius: 5px;
            min-height: calc(60vh - 100px); /* Keep as per your original code */
            position: relative; /* For positioning save button */
        }

        #account-select {
            margin-bottom: 20px;
        }

        #account-select label {
            font-weight: bold;
            margin-right: 10px;
        }

        #account-select select {
            padding: 5px;
            border-radius: 5px;
        }

        .ledger-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .ledger-table th, .ledger-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .ledger-table td input {
            width: 100%;
            padding: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .delete-btn {
            padding: 5px 10px;
            background-color: #ff4444;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .delete-btn:hover {
            background-color: #cc0000;
        }

        .add-row-btn, #saveChangesBtn {
            padding: 10px 20px;
            background-color: white;
            border: 1px solid #3674b5;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 10px;
        }

        .add-row-btn {
            margin-top: 10px;
        }

        .add-row-btn:hover, #saveChangesBtn:hover {
            background-color: #3674b5;
            color: white;
        }

        .button-container {
            display: flex;
            justify-content: flex-start; /* Changed to flex-start to align Save Changes to the left */
            align-items: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <header>
        <div id="left">
            <img src="../assets/images/oasis-logo-Photoroom.jpg" alt="Oakwood" id="logo">
        </div>
        <h3 id="employee-dashboard-button">Dashboard</h3>
        <h3 id="employee-oasis-accounts">Oasis Accounts</h3>
        <h3 id="employee-payment-center">Payment Center</h3>
        <div id="right">
            <i class="fa-solid fa-bell"></i>
            <button id="logout" onclick="if(confirm('Are you sure you want to log out?')) window.location.href='../logout.php';">Logout</button>
        </div>
    </header>

    <div id="payment-container">
        <div id="account-select">
            <label for="account-dropdown">Select Account: </label>
            <select id="account-dropdown" name="account-dropdown" onchange="window.location.href='?account=' + this.value">
                <option value="">Select an Account</option>
                <?php foreach ($accounts as $account): ?>
                    <option value="<?php echo htmlspecialchars($account); ?>" <?php echo $selectedAccount === $account ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($account); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <?php if ($selectedAccount): ?>
            <h3>Tuition Ledger for <?php echo htmlspecialchars($selectedAccount); ?></h3>
            <table class="ledger-table" id="tuition-table">
                <thead>
                    <tr>
                        <th>Fee</th>
                        <th>Due Date</th>
                        <th>Amount</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="tuition-table-body">
                    <?php foreach ($tuitionData as $row): ?>
                        <tr>
                            <td><input type="text" value="<?php echo htmlspecialchars($row['fee']); ?>" data-id="<?php echo $row['id']; ?>" data-table="tuition" data-field="fee"></td>
                            <td><input type="date" value="<?php echo htmlspecialchars($row['duedate']); ?>" data-id="<?php echo $row['id']; ?>" data-table="tuition" data-field="duedate"></td>
                            <td><input type="number" step="0.01" value="<?php echo htmlspecialchars($row['amount']); ?>" data-id="<?php echo $row['id']; ?>" data-table="tuition" data-field="amount"></td>
                            <td><button class="delete-btn" data-id="<?php echo $row['id']; ?>" data-table="tuition">Delete</button></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <button class="add-row-btn" data-table="tuition">Add Row to Tuition</button>

            <h3>Miscellaneous Ledger for <?php echo htmlspecialchars($selectedAccount); ?></h3>
            <table class="ledger-table" id="misc-table">
                <thead>
                    <tr>
                        <th>Fee</th>
                        <th>Due Date</th>
                        <th>Amount</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="misc-table-body">
                    <?php foreach ($miscData as $row): ?>
                        <tr>
                            <td><input type="text" value="<?php echo htmlspecialchars($row['fee']); ?>" data-id="<?php echo $row['id']; ?>" data-table="misc" data-field="fee"></td>
                            <td><input type="date" value="<?php echo htmlspecialchars($row['duedate']); ?>" data-id="<?php echo $row['id']; ?>" data-table="misc" data-field="duedate"></td>
                            <td><input type="number" step="0.01" value="<?php echo htmlspecialchars($row['amount']); ?>" data-id="<?php echo $row['id']; ?>" data-table="misc" data-field="amount"></td>
                            <td><button class="delete-btn" data-id="<?php echo $row['id']; ?>" data-table="misc">Delete</button></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <button class="add-row-btn" data-table="misc">Add Row to Miscellaneous</button>

            <div class="button-container">
                <button id="saveChangesBtn">Save Changes</button>   
                <!-- Removed the two add-row-btn elements here -->
            </div>
        <?php endif; ?>
    </div>

    <div id="notif-modal" class="hide">
        <div id="notifs">
            <div id="notif"><h3>Reminder</h3><p>Magbayad ka na please</p></div>
            <div id="notif"><h3>Reminder</h3><p>Magbayad ka na sabi e</p></div>
            <div id="notif"><h3>Reminder</h3><p>Alam namin bahay niyo</p></div>
        </div>
    </div>

    <script src="../js/common-functions.js"></script>
    <script src="../js/routing-employee.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const saveChangesBtn = document.getElementById('saveChangesBtn');
            let selectedAccount = '<?php echo $selectedAccount; ?>';

            // Add row functionality for each table
            document.querySelectorAll('.add-row-btn').forEach(button => {
                button.addEventListener('click', function () {
                    const tableType = this.getAttribute('data-table');
                    const tableBody = document.getElementById(`${tableType}-table-body`);
                    const newRow = document.createElement('tr');
                    newRow.innerHTML = `
                        <td><input type="text" data-table="${tableType}" data-id="new" data-field="fee"></td>
                        <td><input type="date" data-table="${tableType}" data-id="new" data-field="duedate"></td>
                        <td><input type="number" step="0.01" data-table="${tableType}" data-id="new" data-field="amount"></td>
                        <td><button class="delete-btn" data-id="new" data-table="${tableType}" disabled>Delete</button></td>
                    `;
                    tableBody.appendChild(newRow);
                });
            });

            // Delete row functionality
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function () {
                    if (confirm('Are you sure you want to delete this row?')) {
                        const id = this.getAttribute('data-id');
                        const table = this.getAttribute('data-table');
                        const account = selectedAccount;

                        fetch('delete_ledger_row.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                            body: `account=${encodeURIComponent(account)}&table=${encodeURIComponent(table)}&id=${encodeURIComponent(id)}`
                        })
                        .then(response => response.json())
                        .then(result => {
                            if (result.success) {
                                alert('Row deleted successfully!');
                                window.location.reload(); // Reload to reflect the deletion
                            } else {
                                alert('Error deleting row: ' + result.error);
                            }
                        })
                        .catch(error => console.error('Error deleting row:', error));
                    }
                });
            });

            // Save changes functionality
            saveChangesBtn.addEventListener('click', function () {
                const changes = [];
                document.querySelectorAll('input[data-table]').forEach(element => {
                    const table = element.getAttribute('data-table');
                    const id = element.getAttribute('data-id');
                    const field = element.getAttribute('data-field');
                    const value = element.value;
                    changes.push({ table, id, field, value });
                });

                if (changes.length === 0) {
                    alert('No changes to save.');
                    return;
                }

                fetch('update_ledger.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `account=${encodeURIComponent(selectedAccount)}&changes=${encodeURIComponent(JSON.stringify(changes))}`
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        alert('Changes saved successfully!');
                        window.location.reload(); // Reload to reflect updates
                    } else {
                        alert('Error saving changes: ' + result.error);
                    }
                })
                .catch(error => {
                    console.error('Error saving changes:', error);
                    alert('An error occurred while saving changes. Check the console for details.');
                });
            });
        });
    </script>
</body>
</html>