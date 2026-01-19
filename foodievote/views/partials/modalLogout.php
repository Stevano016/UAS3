<div id="customAlert" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; backdrop-filter: blur(5px);">
    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 40px rgba(0,0,0,0.3); min-width: 350px; animation: slideIn 0.3s ease;">
        <div style="text-align: center; margin-bottom: 1.5rem;">
            <div style="width: 60px; height: 60px; border-radius: 50%; background: #fee2e2; margin: 0 auto 1rem; display: flex; align-items: center; justify-content: center; font-size: 2rem;">
                ⚠️
            </div>
            <h5 style="margin: 0 0 0.5rem 0; color: #333; font-weight: 600;">Konfirmasi Logout</h5>
            <p style="margin: 0; color: #666; font-size: 0.95rem;">Apakah Anda yakin ingin keluar dari sistem?</p>
        </div>
        <div style="display: flex; gap: 0.75rem; justify-content: center;">
            <button onclick="closeAlert()" style="padding: 0.6rem 1.5rem; border: 2px solid #6c757d; background: white; color: #6c757d; border-radius: 8px; cursor: pointer; font-weight: 500; transition: all 0.3s;">
                Batal
            </button>
            <button onclick="confirmLogout()" style="padding: 0.6rem 1.5rem; border: none; background: #dc3545; color: white; border-radius: 8px; cursor: pointer; font-weight: 500; transition: all 0.3s; box-shadow: 0 2px 8px rgba(220,53,69,0.3);">
                Ya, Logout
            </button>
        </div>
    </div>
</div>