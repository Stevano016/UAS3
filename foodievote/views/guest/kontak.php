<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/kontak.css">

<div class="contact-page">
    <div class="contact-card">
        <div class="contact-header">
            <span>Get in Touch</span>
            <h1>Hubungi Kami</h1>
            <p class="mx-auto" style="max-width: 600px; opacity: 0.9;">Punya pertanyaan tentang FoodieVote? Tim kami siap membantu Anda menemukan pengalaman kuliner terbaik.</p>
        </div>

        <div class="row g-0">
            <div class="col-lg-5">
                <div class="info-section">
                    <h3>Informasi Kontak</h3>
                    
                    <div class="info-item d-flex align-items-center mb-4">
                        <div class="icon-box me-3">
                            <i class="bi bi-envelope-heart-fill"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">Email Resmi</small>
                            <span class="fw-bold">stevanowahyu2@gmail.com</span>
                        </div>
                    </div>

                    <div class="info-item d-flex align-items-center mb-4">
                        <div class="icon-box me-3">
                            <i class="bi bi-instagram"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">Instagram</small>
                            <span class="fw-bold">@foodievote</span>
                        </div>
                    </div>

                    <div class="info-item d-flex align-items-center mb-4">
                        <div class="icon-box me-3">
                            <i class="bi bi-geo-alt-fill"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">Lokasi</small>
                            <span class="fw-bold">Jakarta, Indonesia</span>
                        </div>
                    </div>

                    <div class="mt-5 p-4 rounded-4" style="background: rgba(251, 191, 36, 0.1); border: 1px dashed var(--accent-amber);">
                        <p class="mb-0" style="font-style: italic; font-size: 0.9rem; color: #6d4c41;">
                            "FoodieVote membantu pecinta kuliner menemukan rasa yang jujur."
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="form-sections">
                    <form action="mailto:stevanowahyu2@gmail.com" method="GET" enctype="text/plain">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Anda</label>
                                <input type="text" name="name" class="form-control" placeholder="John Doe" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Subjek</label>
                                <input type="text" name="subject" class="form-control" placeholder="Tanya Restoran" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email Aktif</label>
                            <input type="email" class="form-control" placeholder="nama@email.com" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Pesan Anda</label>
                            <textarea name="body" class="form-control" rows="4" placeholder="Apa yang ingin Anda sampaikan?" required></textarea>
                        </div>

                        <button type="submit" class="btn btn-submit">
                            <i class="bi bi-send-fill me-2"></i>Kirim Sekarang
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>