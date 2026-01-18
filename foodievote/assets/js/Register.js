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
        } else {
            togglePasswordIcon.classList.remove('bi-eye-slash');
            togglePasswordIcon.classList.add('bi-eye');
        }
    });

    // Toggle Confirm Password Visibility
    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
    const confirmPasswordInput = document.getElementById('confirm_password');
    const toggleConfirmPasswordIcon = document.getElementById('toggleConfirmPasswordIcon');

    toggleConfirmPassword.addEventListener('click', function() {
        // Toggle tipe input
        const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        confirmPasswordInput.setAttribute('type', type);
        
        // Toggle icon
        if (type === 'text') {
            toggleConfirmPasswordIcon.classList.remove('bi-eye');
            toggleConfirmPasswordIcon.classList.add('bi-eye-slash');
        } else {
            toggleConfirmPasswordIcon.classList.remove('bi-eye-slash');
            toggleConfirmPasswordIcon.classList.add('bi-eye');
        }
    });

    // Real-time Username Validation
    const usernameInput = document.getElementById('username');
    usernameInput.addEventListener('input', function() {
        const value = this.value;
        
        // Remove old feedback
        const oldFeedback = document.getElementById('username-feedback');
        if (oldFeedback) oldFeedback.remove();
        
        const feedback = document.createElement('small');
        feedback.id = 'username-feedback';
        feedback.className = 'd-block mt-1';
        
        if (value.length === 0) {
            this.classList.remove('is-valid', 'is-invalid');
        } else if (value.length < 3) {
            feedback.className += ' text-danger';
            feedback.textContent = '❌ Username minimal 3 karakter (saat ini: ' + value.length + ')';
            this.classList.add('is-invalid');
            this.classList.remove('is-valid');
            this.parentElement.appendChild(feedback);
        } else if (value.length > 50) {
            feedback.className += ' text-danger';
            feedback.textContent = '❌ Username maksimal 50 karakter';
            this.classList.add('is-invalid');
            this.classList.remove('is-valid');
            this.parentElement.appendChild(feedback);
        } else if (!/^[a-zA-Z0-9_]+$/.test(value)) {
            feedback.className += ' text-danger';
            feedback.textContent = '❌ Username hanya boleh huruf, angka, dan underscore';
            this.classList.add('is-invalid');
            this.classList.remove('is-valid');
            this.parentElement.appendChild(feedback);
        } else {
            feedback.className += ' text-success';
            feedback.textContent = '✅ Username valid';
            this.classList.remove('is-invalid');
            this.classList.add('is-valid');
            this.parentElement.appendChild(feedback);
        }
    });

    // Real-time Password Validation
    const password = document.getElementById('password');
    password.addEventListener('input', function() {
        const value = this.value;
        
        // Remove old feedback
        const oldFeedback = document.getElementById('password-feedback');
        if (oldFeedback) oldFeedback.remove();
        
        const feedback = document.createElement('small');
        feedback.id = 'password-feedback';
        feedback.className = 'd-block mt-1';
        
        if (value.length === 0) {
            this.classList.remove('is-valid', 'is-invalid');
        } else if (value.length < 8) {
            feedback.className += ' text-danger';
            feedback.textContent = '❌ Password minimal 8 karakter (saat ini: ' + value.length + ')';
            this.classList.add('is-invalid');
            this.classList.remove('is-valid');
            this.parentElement.parentElement.appendChild(feedback);
        } else {
            feedback.className += ' text-success';
            feedback.textContent = '✅ Password valid';
            this.classList.remove('is-invalid');
            this.classList.add('is-valid');
            this.parentElement.parentElement.appendChild(feedback);
        }
        
        // Re-validate confirm password if it has value
        if (confirmPasswordInput.value.length > 0) {
            confirmPasswordInput.dispatchEvent(new Event('input'));
        }
    });

    // Real-time Confirm Password Validation
    const confirmPassword = document.getElementById('confirm_password');
    confirmPassword.addEventListener('input', function() {
        const value = this.value;
        
        // Remove old feedback
        const oldFeedback = document.getElementById('confirm-feedback');
        if (oldFeedback) oldFeedback.remove();
        
        const feedback = document.createElement('small');
        feedback.id = 'confirm-feedback';
        feedback.className = 'd-block mt-1';
        
        if (value.length === 0) {
            this.classList.remove('is-valid', 'is-invalid');
        } else if (value !== password.value) {
            feedback.className += ' text-danger';
            feedback.textContent = '❌ Password tidak cocok';
            this.classList.add('is-invalid');
            this.classList.remove('is-valid');
            this.parentElement.parentElement.appendChild(feedback);
        } else if (value.length >= 8) {
            feedback.className += ' text-success';
            feedback.textContent = '✅ Password cocok';
            this.classList.remove('is-invalid');
            this.classList.add('is-valid');
            this.parentElement.parentElement.appendChild(feedback);
        }
    });

    // Form validation before submit
    document.getElementById('registerForm').addEventListener('submit', function(e) {
        const username = usernameInput.value;
        const passwordVal = password.value;
        const confirmPasswordVal = confirmPassword.value;
        
        // Validate username
        if (username.length < 3) {
            e.preventDefault();
            alert('Username minimal 3 karakter!');
            usernameInput.focus();
            return false;
        }
        
        if (username.length > 50) {
            e.preventDefault();
            alert('Username maksimal 50 karakter!');
            usernameInput.focus();
            return false;
        }
        
        if (!/^[a-zA-Z0-9_]+$/.test(username)) {
            e.preventDefault();
            alert('Username hanya boleh huruf, angka, dan underscore!');
            usernameInput.focus();
            return false;
        }
        
        // Validate password
        if (passwordVal.length < 8) {
            e.preventDefault();
            alert('Password minimal 8 karakter!');
            password.focus();
            return false;
        }
        
        // Validate password match
        if (passwordVal !== confirmPasswordVal) {
            e.preventDefault();
            alert('Password dan konfirmasi password tidak cocok!');
            confirmPassword.focus();
            return false;
        }
    });