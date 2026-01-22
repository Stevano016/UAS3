<style>
body {
    font-family: system-ui, sans-serif;
    background:#f3f4f6;
    padding:40px;
}

.btn {
    padding:.4rem .8rem;
    border-radius:6px;
    cursor:pointer;
    font-size:.85rem;
}

.btn-danger {
    border:1px solid #dc3545;
    color:#dc3545;
    background:white;
}

.btn-danger:hover {
    background:#dc3545;
    color:white;
}

/* MODAL */
#deleteModal {
    display:none;
    position:fixed;
    inset:0;
    background:rgba(0,0,0,.5);
    z-index:9999;
    backdrop-filter:blur(4px);
}

.modal-box {
    position:absolute;
    top:50%;
    left:50%;
    transform:translate(-50%, -50%);
    background:white;
    padding:2rem;
    border-radius:15px;
    box-shadow:0 10px 40px rgba(0,0,0,.3);
    min-width:360px;
    animation:pop .25s ease;
}

@keyframes pop {
    from {transform:translate(-50%,-45%) scale(.95); opacity:0}
    to   {transform:translate(-50%,-50%) scale(1); opacity:1}
}
</style>

<h3>Daftar Restoran</h3>

<form method="POST" class="delete-form">
    <input type="hidden" name="restaurant_id" value="<?php echo $restaurant['id']; ?>">
    <input type="hidden" name="delete_restaurant" value="1">

    <button type="button"
            class="btn btn-danger"
            onclick="openDeleteModal(this)">
        Hapus <?php echo htmlspecialchars($restaurant['name']); ?>
    </button>
</form>

<!-- MODAL -->
<div id="deleteModal">
    <div class="modal-box">
        <div style="text-align:center;margin-bottom:1.5rem">
            <div style="
                width:64px;height:64px;
                border-radius:50%;
                background:#fee2e2;
                margin:0 auto 1rem;
                display:flex;
                align-items:center;
                justify-content:center;
                font-size:2rem">
                🗑️
            </div>
            <h4 style="margin:0 0 .5rem">Hapus Data?</h4>
            <p style="margin:0;color:#555;font-size:.95rem">
                Data ini <b>tidak bisa dikembalikan</b>.<br>
                Yakin lanjut?
            </p>
        </div>

        <div style="display:flex;gap:.75rem;justify-content:center">
            <button type="button"
                    onclick="closeDeleteModal()"
                    style="
                        padding:.6rem 1.5rem;
                        border:2px solid #6c757d;
                        background:white;
                        color:#6c757d;
                        border-radius:8px;
                        cursor:pointer">
                Batal
            </button>

            <button type="button"
                    onclick="confirmDelete()"
                    style="
                        padding:.6rem 1.5rem;
                        border:none;
                        background:#dc3545;
                        color:white;
                        border-radius:8px;
                        cursor:pointer;
                        box-shadow:0 2px 8px rgba(220,53,69,.35)">
                Ya, Hapus
            </button>
        </div>
    </div>
</div>

<script>
let deleteForm = null;

function openDeleteModal(btn) {
    deleteForm = btn.closest('form');
    document.getElementById('deleteModal').style.display = 'block';
}

function closeDeleteModal() {
    deleteForm = null;
    document.getElementById('deleteModal').style.display = 'none';
}

function confirmDelete() {
    if (!deleteForm) return;
    deleteForm.submit();
}

// klik area gelap = tutup
document.getElementById('deleteModal').addEventListener('click', function(e){
    if (e.target === this) closeDeleteModal();
});
</script>


