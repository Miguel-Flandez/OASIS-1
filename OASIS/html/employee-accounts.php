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
    <link rel="stylesheet" href="../css/oasis-accounts.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dosis:wght@200..800&family=Fjalla+One&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Oswald:wght@200..700&family=Playwrite+IN:wght@100..400&family=Poiret+One&family=Roboto+Slab:wght@100..900&family=Roboto:ital,wght@0,100..900;1,100..900&family=Smooch+Sans:wght@100..900&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/de323b5912.js" crossorigin="anonymous"></script>
    <title>Employee Accounts</title>
    <style>
        /* Modal styles */
        #editModal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }

        #editModalContent {
            background-color: white;
            margin: 5% auto;
            padding: 20px;
            width: 700px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            position: relative;
        }

        #closeModal {
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
        .edit-form-row input[type="password"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 20px;
        }

        .edit-form-row input[readonly] {
            background-color: #f0f0f0;
            cursor: not-allowed;
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

        .delete-btn {
            background-color: #ff4444;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        .delete-btn:hover {
            background-color: #cc0000;
        }

        /* Search bar styling to match oasis-accounts.php */
        #search {
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        #search label {
            font-weight: bold;
        }

        #search input {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 250px;
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
            <label for="search-employee">Search Employee: </label>
            <input type="text" id="search-employee" name="search-employee" placeholder=" ">
        </div>
        
        <table id="accounts-table">
            <thead>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Middle Name</th>
                    <th>Username</th>
                    <th>Account Name</th>
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
            <h3>Edit Employee Account</h3>
            <form id="editAccountForm">
                <div class="edit-form-row">
                    <label for="editFirstName">First Name:</label>
                    <input type="text" id="editFirstName" name="editFirstName" required>
                </div>
                <div class="edit-form-row">
                    <label for="editLastName">Last Name:</label>
                    <input type="text" id="editLastName" name="editLastName" required>
                </div>
                <div class="edit-form-row">
                    <label for="editMiddleName">Middle Name:</label>
                    <input type="text" id="editMiddleName" name="editMiddleName">
                </div>
                <div class="edit-form-row">
                    <label for="editUsername">Username:</label>
                    <input type="text" id="editUsername" name="editUsername" readonly>
                </div>
                <div class="edit-form-row">
                    <label for="editAccountName">Account Name:</label>
                    <input type="text" id="editAccountName" name="editAccountName" required>
                </div>
                <div class="edit-form-row">
                    <label for="editPassword">Password:</label>
                    <input type="password" id="editPassword" name="editPassword">
                </div>
                <button type="submit" id="saveChanges">Save Changes</button>
            </form>
        </div>
    </div>

    <script src="../js/common-functions.js"></script>
    <script src="../js/routing-admin.js"></script>
    <script>
    let allAccounts = []; // Store all accounts for filtering

    document.addEventListener("DOMContentLoaded", function () {
        // Fetch and populate accounts table
        fetch("fetch_employee_accounts.php")
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
                const searchInput = document.getElementById('search-employee');
                searchInput.addEventListener('input', function () {
                    const searchTerm = this.value.toLowerCase();
                    const filteredAccounts = allAccounts.filter(account => {
                        return (
                            (account.firstname && account.firstname.toLowerCase().includes(searchTerm)) ||
                            (account.lastname && account.lastname.toLowerCase().includes(searchTerm)) ||
                            (account.middlename && account.middlename.toLowerCase().includes(searchTerm)) ||
                            (account.username && account.username.toLowerCase().includes(searchTerm)) ||
                            (account.accountname && account.accountname.toLowerCase().includes(searchTerm))
                        );
                    });
                    renderAccountsTable(filteredAccounts);
                });

                // Add event listeners to edit and delete buttons
                addButtonListeners();
            })
            .catch(error => {
                console.error("Error fetching employee accounts:", error);
                alert("Failed to load employee accounts data. Please check the console for details.");
            });

        // Close modal
        document.getElementById('closeModal').addEventListener('click', function () {
            document.getElementById('editModal').style.display = 'none';
        });

        // Save changes
        document.getElementById('editAccountForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const firstName = document.getElementById('editFirstName').value;
            const lastName = document.getElementById('editLastName').value;
            const middleName = document.getElementById('editMiddleName').value;
            const username = document.getElementById('editUsername').value;
            const accountName = document.getElementById('editAccountName').value;
            const password = document.getElementById('editPassword').value;

            fetch('update_employee_account.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `firstname=${encodeURIComponent(firstName)}&lastname=${encodeURIComponent(lastName)}&middlename=${encodeURIComponent(middleName)}&username=${encodeURIComponent(username)}&accountname=${encodeURIComponent(accountName)}&password=${encodeURIComponent(password)}`
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    alert('Employee account updated successfully!');
                    document.getElementById('editModal').style.display = 'none';
                    // Refresh the table by refetching data
                    fetch("fetch_employee_accounts.php")
                        .then(response => response.json())
                        .then(data => {
                            allAccounts = data;
                            renderAccountsTable(allAccounts);
                            addButtonListeners();
                        });
                } else {
                    alert('Error updating employee account: ' + result.error);
                }
            })
            .catch(error => console.error('Error updating employee account:', error));
        });
    });

    // Function to render the table
    function renderAccountsTable(accounts) {
        const tableBody = document.getElementById("accountsTableBody");
        tableBody.innerHTML = ""; // Clear existing data

        accounts.forEach(account => {
            const row = `
                <tr>
                    <td>${account.firstname || ''}</td>
                    <td>${account.lastname || ''}</td>
                    <td>${account.middlename || ''}</td>
                    <td>${account.username || ''}</td>
                    <td>${account.accountname || ''}</td>
                    <td>
                        <button class="edit-btn" data-username="${account.username}">Edit</button>
                        <button class="delete-btn" data-username="${account.username}">Delete</button>
                    </td>
                </tr>
            `;
            tableBody.innerHTML += row;
        });

        // Re-attach button listeners after rendering
        addButtonListeners();
    }

    // Function to add edit and delete button listeners
    function addButtonListeners() {
        // Edit button listeners
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function () {
                const username = this.getAttribute('data-username');
                fetch(`fetch_employee_account_details.php?username=${username}`)
                    .then(response => response.json())
                    .then(account => {
                        document.getElementById('editFirstName').value = account.firstname || '';
                        document.getElementById('editLastName').value = account.lastname || '';
                        document.getElementById('editMiddleName').value = account.middlename || '';
                        document.getElementById('editUsername').value = account.username || '';
                        document.getElementById('editAccountName').value = account.accountname || '';
                        document.getElementById('editPassword').value = ''; // Clear password field for security
                        document.getElementById('editModal').style.display = 'block';
                    })
                    .catch(error => console.error('Error fetching employee account details:', error));
            });
        });

        // Delete button listeners
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function () {
                const username = this.getAttribute('data-username');
                if (confirm(`Are you sure you want to delete the employee account for ${username}?`)) {
                    fetch('delete_employee_account.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: `username=${encodeURIComponent(username)}`
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.success) {
                            alert('Employee account deleted successfully!');
                            // Refresh the table by refetching data
                            fetch("fetch_employee_accounts.php")
                                .then(response => response.json())
                                .then(data => {
                                    allAccounts = data;
                                    renderAccountsTable(allAccounts);
                                    addButtonListeners();
                                });
                        } else {
                            alert('Error deleting employee account: ' + result.error);
                        }
                    })
                    .catch(error => console.error('Error deleting employee account:', error));
                }
            });
        });
    }

    // Close modal if clicking outside
    window.addEventListener('click', function (e) {
        const editModal = document.getElementById('editModal');
        if (e.target === editModal) editModal.style.display = 'none';
    });
    </script>
</body>
</html>