// File JavaScript dasar untuk FoodieVote

// Fungsi untuk menangani konfirmasi sebelum menghapus
document.addEventListener("DOMContentLoaded", function () {
  // Tambahkan event listener untuk form yang membutuhkan konfirmasi
  const deleteForms = document.querySelectorAll('form[onsubmit*="confirm"]');

  deleteForms.forEach((form) => {
    form.addEventListener("submit", function (e) {
      const confirmation = confirm(
        "Apakah Anda yakin ingin melakukan tindakan ini?"
      );
      if (!confirmation) {
        e.preventDefault();
      }
    });
  });

  // Fungsi untuk menangani modal edit rating
  const editModals = document.querySelectorAll(".modal");
  editModals.forEach((modal) => {
    modal.addEventListener("show.bs.modal", function () {
      // Tambahkan logika tambahan jika diperlukan saat modal ditampilkan
    });
  });
});

// Fungsi utilitas tambahan
function showAlert(message, type = "info") {
  // Membuat elemen alert dinamis
  const alertDiv = document.createElement("div");
  alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
  alertDiv.setAttribute("role", "alert");
  alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

  // Tambahkan ke bagian atas container utama
  const container = document.querySelector(".container");
  if (container) {
    container.insertBefore(alertDiv, container.firstChild);
  }
}

// Fungsi untuk validasi formulir sederhana
function validateForm(formId) {
  const form = document.getElementById(formId);
  if (!form) return false;

  const inputs = form.querySelectorAll(
    "input[required], textarea[required], select[required]"
  );
  let isValid = true;

  inputs.forEach((input) => {
    if (!input.value.trim()) {
      isValid = false;
      input.classList.add("is-invalid");
    } else {
      input.classList.remove("is-invalid");
    }
  });

  return isValid;
}