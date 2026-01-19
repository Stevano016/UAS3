 function updateClock() {
        const now = new Date();
        
        // Ambil Jam dan Menit
        let hours = now.getHours().toString().padStart(2, '0');
        let minutes = now.getMinutes().toString().padStart(2, '0');
        
        // Tampilkan ke elemen dengan ID 'realtime-clock'
        const timeString = `${hours}:${minutes}`;
        document.getElementById('realtime-clock').textContent = timeString;
    }

    // Jalankan fungsi setiap detik agar selalu update
    setInterval(updateClock, 1000);

    // Panggil fungsi sekali di awal agar tidak menunggu 1 detik saat refresh
    updateClock();