const dashboardButton = document.getElementById('dashboard-button');
const createAccountsButton = document.getElementById('create-account');
const createEmployeeButton = document.getElementById('create-employee');
const oasisAccountsButton = document.getElementById('oasis-accounts');
const employeeAccountsButton = document.getElementById('employee-accounts');

dashboardButton.onclick = function() {
    window.location.href = 'admin-home.php';
}
createEmployeeButton.onclick = function() {
    window.location.href = 'create-employee.php';
};
createAccountsButton.onclick = function() {
    window.location.href = 'create-account.php';
}

oasisAccountsButton.onclick = function() {
    window.location.href = 'oasis-accounts.php';
}
employeeAccountsButton.onclick = function() {
    window.location.href = 'employee-accounts.php';
};



const toStudent = document.getElementById('to-student');

toStudent.onclick = function(){
    window.location.href = 'student-home.html'
}

const toEmployee = document.getElementById('to-employee');

toEmployee.onclick = function(){
    window.location.href = 'employee-home.html'
}

