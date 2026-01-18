 // Toggle Password Visibility
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const togglePasswordIcon = document.getElementById('togglePasswordIcon');

    togglePassword.addEventListener('click', function() {
        // Toggle tipe input
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        
        // Toggle icon
        if (type === 'text') {
            togglePasswordIcon.classList.remove('bi-eye');
            togglePasswordIcon.classList.add('bi-eye-slash');
            togglePassword.setAttribute('aria-label', 'Hide password');
        } else {
            togglePasswordIcon.classList.remove('bi-eye-slash');
            togglePasswordIcon.classList.add('bi-eye');
            togglePassword.setAttribute('aria-label', 'Show password');
        }
    });

    // Optional: Basic form validation
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        const username = document.getElementById('username').value.trim();
        const password = document.getElementById('password').value;
        
        if (username.length === 0) {
            e.preventDefault();
            alert('Username tidak boleh kosong!');
            document.getElementById('username').focus();
            return false;
        }
        
        if (password.length === 0) {
            e.preventDefault();
            alert('Password tidak boleh kosong!');
            document.getElementById('password').focus();
            return false;
        }
    });

    // Auto-dismiss alert after 5 seconds
    const alertElement = document.querySelector('.alert');
    if (alertElement) {
        setTimeout(function() {
            const bsAlert = new bootstrap.Alert(alertElement);
            bsAlert.close();
        }, 5000);
    }