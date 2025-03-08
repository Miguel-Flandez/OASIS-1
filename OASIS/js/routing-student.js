const homeButton = document.getElementById('home-button');
const opcButton = document.getElementById('opc-button');
const profileButton = document.getElementById('profile-button');

homeButton.onclick = function() {
    window.location.href = 'student-home.php'; // Same directory, no prefix needed
};
opcButton.onclick = function() {
    window.location.href = 'online-payment-center.php'; // Same directory
};
profileButton.onclick = function() {
    window.location.href = 'user-profile.php'; // Same directory
};

const toAdmin = document.getElementById('to-admin');
toAdmin.onclick = function() {
    window.location.href = 'admin-home.html'; // Same directory
};

const toEmployee = document.getElementById('to-employee');
toEmployee.onclick = function() {
    window.location.href = 'employee-home.html'; // Same directory
};