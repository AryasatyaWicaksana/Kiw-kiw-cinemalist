// Toggle password visibility
document.getElementById('togglePassword1').addEventListener('click', function () {
    const passwordField = document.getElementById('password');
    const icon1 = document.getElementById('toggleIcon1');
    passwordField.type = passwordField.type === 'password' ? 'text' : 'password';
    icon1.classList.toggle('bi-eye-fill');
    icon1.classList.toggle('bi-eye-slash-fill');
});

document.getElementById('togglePassword2').addEventListener('click', function () {
    const confirmPasswordField = document.getElementById('confirm-password');
    const icon2 = document.getElementById('toggleIcon2');
    confirmPasswordField.type = confirmPasswordField.type === 'password' ? 'text' : 'password';
    icon2.classList.toggle('bi-eye-fill');
    icon2.classList.toggle('bi-eye-slash-fill');
});