function updateClock() {
    const clockEl = document.getElementById('realtime-clock');
    if (!clockEl) return; // pengaman wajib

    const now = new Date();

    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');

    clockEl.textContent = `${hours}:${minutes}`;
}

// Panggil sekali saat load
updateClock();

// Update tiap detik
setInterval(updateClock, 1000);

document.addEventListener('DOMContentLoaded', () => {
    updateClock();
    setInterval(updateClock, 1000);
});
