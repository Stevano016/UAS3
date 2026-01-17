function showLogoutConfirm(event) {
    event.preventDefault();
    document.getElementById('customAlert').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closeAlert() {
    document.getElementById('customAlert').style.display = 'none';
    document.body.style.overflow = 'auto';
}

function confirmLogout() {
    window.location.href = 'logout.php';
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeAlert();
    }
});

document.addEventListener('DOMContentLoaded', function() {
    var alertEl = document.getElementById('customAlert');
    if (alertEl) {
        alertEl.addEventListener('click', function(e) {
            if (e.target === this) {
                closeAlert();
            }
        });
    }
});