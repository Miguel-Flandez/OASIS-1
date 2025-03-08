const employeeDashboardButton = document.getElementById('employee-dashboard-button');
const employeeOasisAccountsButton = document.getElementById('employee-oasis-accounts');
const employeePaymentCenterButton = document.getElementById('employee-payment-center');
const employeePayorHistoryButton = document.getElementById('employee-payor-history');

employeeDashboardButton.onclick = function() {
    window.location.href = 'employee-home.php';
};

employeeOasisAccountsButton.onclick = function() {
    window.location.href = 'employee-oasis-accounts.php';
};
employeePaymentCenterButton.onclick = function() {
    window.location.href = 'employee-payment-center.php';
};
employeePayorHistoryButton.onclick = function() {
    window.location.href = 'employee-payor-history.php';   
};

const toStudent = document.getElementById('to-student');
toStudent.onclick = function() {
    window.location.href = 'student-home.php'; // Note: Employee accessing student page
};

const toAdmin = document.getElementById('to-admin');
toAdmin.onclick = function() {
    window.location.href = 'admin-home.html'; // Still HTML, adjust if converted later
};