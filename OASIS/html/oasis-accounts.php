<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/oasis-accounts.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dosis:wght@200..800&family=Fjalla+One&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Oswald:wght@200..700&family=Playwrite+IN:wght@100..400&family=Poiret+One&family=Roboto+Slab:wght@100..900&family=Roboto:ital,wght@0,100..900;1,100..900&family=Smooch+Sans:wght@100..900&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/de323b5912.js" crossorigin="anonymous"></script>
    <title>OASIS Accounts</title>
    <style>
        /* Modal styles */
        #editModal, #viewHistoryModal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }

        #editModalContent, #viewHistoryModalContent {
            background-color: white;
            margin: 5% auto;
            padding: 20px;
            width: 700px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            position: relative;
        }

        #closeModal, #closeHistoryModal {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 20px;
            cursor: pointer;
            border: none;
            background: none;
        }

        .edit-form-row {
            display: grid;
            grid-template-columns: 150px 1fr;
            grid-gap: 20px;
            margin-bottom: 10px;
        }

        .edit-form-row label {
            text-align: right;
            padding-right: 10px;
            font-weight: bold;
        }

        .edit-form-row input[type="text"],
        .edit-form-row input[type="password"],
        .edit-form-row select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 20px;
        }

        #saveChanges {
            grid-column: 1 / span 2;
            padding: 10px 20px;
            background-color: white;
            border: 1px solid #40513b;
            border-radius: 5px;
            cursor: pointer;
        }

        #saveChanges:hover {
            background-color: #40513b;
            color: white;
        }

        #historyTable {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        #historyTable th, #historyTable td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        #historyTable th {
            background-color: #f2f2f2;
        }

        .delete-btn {
            background-color: #ff4444;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 3px;
        }

        .delete-btn:hover {
            background-color: #cc0000;
        }
    </style>
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
        <div id="search">
            <label for="search-student">Search Account: </label>
            <input type="text" id="search-student" name="search-student" placeholder=" ">
        </div>
        
        <table id="accounts-table">
            <thead>
                <tr>
                    <th>Student No.</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Middle Name</th>
                    <th>Level</th>
                    <th>Username</th>
                    <th>Account Name</th>
                    <th>Payment Plan</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="accountsTableBody">
                <!-- Dynamic rows will be inserted here -->
            </tbody>
        </table>
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

    <!-- Edit Modal -->
    <div id="editModal">
        <div id="editModalContent">
            <button id="closeModal">×</button>
            <h3>Edit Account</h3>
            <form id="editAccountForm">
                <div class="edit-form-row">
                    <label for="editStudentNumber">Student No.:</label>
                    <input type="text" id="editStudentNumber" name="editStudentNumber">
                </div>
                <div class="edit-form-row">
                    <label for="editFirstName">First Name:</label>
                    <input type="text" id="editFirstName" name="editFirstName">
                </div>
                <div class="edit-form-row">
                    <label for="editLastName">Last Name:</label>
                    <input type="text" id="editLastName" name="editLastName">
                </div>
                <div class="edit-form-row">
                    <label for="editMiddleName">Middle Name:</label>
                    <input type="text" id="editMiddleName" name="editMiddleName">
                </div>
                <div class="edit-form-row">
                    <label for="editLevel">Level:</label>
                    <input type="text" id="editLevel" name="editLevel">
                </div>
                <div class="edit-form-row">
                    <label for="editUsername">Username:</label>
                    <input type="text" id="editUsername" name="editUsername">
                </div>
                <div class="edit-form-row">
                    <label for="editAccountName">Account Name:</label>
                    <input type="text" id="editAccountName" name="editAccountName">
                </div>
                <div class="edit-form-row">
                    <label for="editPaymentPlan">Payment Plan:</label>
                    <select id="editPaymentPlan" name="editPaymentPlan">
                        <option value="">Select Payment Plan</option>
                        <option value="Cash">Cash</option>
                        <option value="Quarterly">Quarterly</option>
                        <option value="Monthly">Monthly</option>
                    </select>
                </div>
                <div class="edit-form-row">
                    <label for="editPassword">Password:</label>
                    <input type="password" id="editPassword" name="editPassword">
                </div>
                <button type="submit" id="saveChanges">Save Changes</button>
            </form>
        </div>
    </div>

    <!-- View History Modal -->
    <div id="viewHistoryModal">
        <div id="viewHistoryModalContent">
            <button id="closeHistoryModal">×</button>
            <h3>Transaction History</h3>
            <table id="historyTable">
                <thead>
                    <tr>
                        <th>Receipt Number</th>
                        <th>Date of Transaction</th>
                        <th>Amount Paid</th>
                        <th>Fees</th>
                        <th>Payment Status</th>
                    </tr>
                </thead>
                <tbody id="historyTableBody">
                    <!-- Dynamic rows will be inserted here -->
                </tbody>
            </table>
        </div>
    </div>

    <script src="../js/common-functions.js"></script>
    <script src="../js/routing-admin.js"></script>
    <script>
    let allAccounts = []; // Store all accounts for filtering

    document.addEventListener("DOMContentLoaded", function () {
        // Fetch and populate accounts table
        fetch("fetch_accounts.php")
            .then(response => {
                if (!response.ok) {
                    throw new Error("Network response was not ok");
                }
                return response.json();
            })
            .then(data => {
                allAccounts = data; // Store the fetched data
                renderAccountsTable(allAccounts); // Initial render

                // Add search functionality
                const searchInput = document.getElementById('search-student');
                searchInput.addEventListener('input', function () {
                    const searchTerm = this.value.toLowerCase();
                    const filteredAccounts = allAccounts.filter(account => {
                        return (
                            (account.studentnumber && account.studentnumber.toLowerCase().includes(searchTerm)) ||
                            (account.firstname && account.firstname.toLowerCase().includes(searchTerm)) ||
                            (account.lastname && account.lastname.toLowerCase().includes(searchTerm)) ||
                            (account.middlename && account.middlename.toLowerCase().includes(searchTerm)) ||
                            (account.level && account.level.toLowerCase().includes(searchTerm)) ||
                            (account.username && account.username.toLowerCase().includes(searchTerm)) ||
                            (account.accountname && account.accountname.toLowerCase().includes(searchTerm)) ||
                            (account.paymentplan && account.paymentplan.toLowerCase().includes(searchTerm))
                        );
                    });
                    renderAccountsTable(filteredAccounts);
                });

                // Add event listeners to buttons
                addButtonListeners();
            })
            .catch(error => {
                console.error("Error fetching accounts:", error);
                alert("Failed to load accounts data. Please check the console for details.");
            });

        // Close modals
        document.getElementById('closeModal').addEventListener('click', function () {
            document.getElementById('editModal').style.display = 'none';
        });

        document.getElementById('closeHistoryModal').addEventListener('click', function () {
            document.getElementById('viewHistoryModal').style.display = 'none';
        });

        // Save changes
        document.getElementById('editAccountForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const studentNumber = document.getElementById('editStudentNumber').value;
            const firstName = document.getElementById('editFirstName').value;
            const lastName = document.getElementById('editLastName').value;
            const middleName = document.getElementById('editMiddleName').value;
            const level = document.getElementById('editLevel').value;
            const username = document.getElementById('editUsername').value;
            const accountName = document.getElementById('editAccountName').value;
            const paymentPlan = document.getElementById('editPaymentPlan').value;
            const password = document.getElementById('editPassword').value;

            fetch('update_account.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `studentnumber=${encodeURIComponent(studentNumber)}&firstname=${encodeURIComponent(firstName)}&lastname=${encodeURIComponent(lastName)}&middlename=${encodeURIComponent(middleName)}&level=${encodeURIComponent(level)}&username=${encodeURIComponent(username)}&accountname=${encodeURIComponent(accountName)}&paymentplan=${encodeURIComponent(paymentPlan)}&password=${encodeURIComponent(password)}`
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    alert('Account updated successfully!');
                    document.getElementById('editModal').style.display = 'none';
                    fetch("fetch_accounts.php")
                        .then(response => response.json())
                        .then(data => {
                            allAccounts = data;
                            renderAccountsTable(allAccounts);
                            addButtonListeners();
                        });
                } else {
                    alert('Error updating account: ' + result.error);
                }
            })
            .catch(error => console.error('Error updating account:', error));
        });
    });

    // Function to render the table
    function renderAccountsTable(accounts) {
        const tableBody = document.getElementById("accountsTableBody");
        tableBody.innerHTML = ""; // Clear existing data

        accounts.forEach(accounts_user => {
            const row = `
                <tr>
                    <td>${accounts_user.studentnumber || ''}</td>
                    <td>${accounts_user.firstname || ''}</td>
                    <td>${accounts_user.lastname || ''}</td>
                    <td>${accounts_user.middlename || ''}</td>
                    <td>${accounts_user.level || ''}</td>
                    <td>${accounts_user.username || ''}</td>
                    <td>${accounts_user.accountname || ''}</td>
                    <td>${accounts_user.paymentplan || ''}</td>
                    <td>
                        <button class="edit-btn" data-studentnumber="${accounts_user.studentnumber}">Edit</button>
                        <button class="view-btn" data-studentnumber="${accounts_user.studentnumber}">View</button>
                        <button class="delete-btn" data-studentnumber="${accounts_user.studentnumber}">Delete</button>
                    </td>
                </tr>
            `;
            tableBody.innerHTML += row;
        });

        // Re-attach button listeners after rendering
        addButtonListeners();
    }

    // Function to add button listeners
    function addButtonListeners() {
        // Edit buttons
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function () {
                const studentNumber = this.getAttribute('data-studentnumber');
                fetch(`fetch_account_details.php?studentnumber=${studentNumber}`)
                    .then(response => response.json())
                    .then(account => {
                        document.getElementById('editStudentNumber').value = account.studentnumber || '';
                        document.getElementById('editFirstName').value = account.firstname || '';
                        document.getElementById('editLastName').value = account.lastname || '';
                        document.getElementById('editMiddleName').value = account.middlename || '';
                        document.getElementById('editLevel').value = account.level || '';
                        document.getElementById('editUsername').value = account.username || '';
                        document.getElementById('editAccountName').value = account.accountname || '';
                        document.getElementById('editPaymentPlan').value = account.paymentplan || '';
                        document.getElementById('editPassword').value = ''; // Leave blank for security
                        document.getElementById('editModal').style.display = 'block';
                    })
                    .catch(error => console.error('Error fetching account details:', error));
            });
        });

        // View buttons
        document.querySelectorAll('.view-btn').forEach(button => {
            button.addEventListener('click', function () {
                const studentNumber = this.getAttribute('data-studentnumber');
                fetch(`fetch_transaction_history.php?studentnumber=${studentNumber}`)
                    .then(response => response.json())
                    .then(history => {
                        const tableBody = document.getElementById('historyTableBody');
                        tableBody.innerHTML = ''; // Clear existing data
                        history.forEach(transaction => {
                            const row = `
                                <tr>
                                    <td>${transaction.receiptnumber || ''}</td>
                                    <td>${transaction.dateoftransaction || ''}</td>
                                    <td>${transaction.amountpaid || ''}</td>
                                    <td>${transaction.fee || ''}</td>
                                    <td>${transaction.paymentstatus || ''}</td>
                                </tr>
                            `;
                            tableBody.innerHTML += row;
                        });
                        document.getElementById('viewHistoryModal').style.display = 'block';
                    })
                    .catch(error => console.error('Error fetching transaction history:', error));
            });
        });

        // Delete buttons
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function () {
                const studentNumber = this.getAttribute('data-studentnumber');
                if (confirm(`Are you sure you want to delete account ${studentNumber}?`)) {
                    fetch('delete_account.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: `studentnumber=${encodeURIComponent(studentNumber)}`
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.success) {
                            alert('Account deleted successfully!');
                            fetch("fetch_accounts.php")
                                .then(response => response.json())
                                .then(data => {
                                    allAccounts = data;
                                    renderAccountsTable(allAccounts);
                                    addButtonListeners();
                                });
                        } else {
                            alert('Error deleting account: ' + result.error);
                        }
                    })
                    .catch(error => console.error('Error deleting account:', error));
                }
            });
        });
    }

    // Close modals if clicking outside
    window.addEventListener('click', function (e) {
        const editModal = document.getElementById('editModal');
        const viewHistoryModal = document.getElementById('viewHistoryModal');
        if (e.target === editModal) editModal.style.display = 'none';
        if (e.target === viewHistoryModal) viewHistoryModal.style.display = 'none';
    });
    </script>
</body>
</html>