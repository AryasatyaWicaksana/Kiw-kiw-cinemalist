document.getElementById('togglePassword').addEventListener('click', function () {
    const passwordInput = document.getElementById('password');
    const passwordIcon = document.getElementById('toggleIcon');
    
    // Toggle the type attribute
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        passwordIcon.classList.remove('bi-eye-fill');
        passwordIcon.classList.add('bi-eye-slash-fill');
    } else {
        passwordInput.type = 'password';
        passwordIcon.classList.remove('bi-eye-slash-fill');
        passwordIcon.classList.add('bi-eye-fill');
    }
});