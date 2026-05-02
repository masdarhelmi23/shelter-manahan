<!-- =========================================
    COMPONENT: KARTU SALDO & POPUP WITHDRAW
========================================= -->

<style>
    /* CSS POPUP WITHDRAW (VANILLA) */
    .withdraw-overlay {
        position: fixed; 
        top: 0; 
        left: 0; 
        width: 100vw; 
        height: 100vh;
        background: rgba(0, 0, 0, 0.95); 
        backdrop-filter: blur(12px);
        display: none; /* Awalnya sembunyi */
        justify-content: center; 
        align-items: center;
        z-index: 9999999; 
        opacity: 0;
        transition: opacity 0.4s ease;
    }

    .withdraw-box {
        background: #111827; 
        width: 95%; 
        max-width: 480px; 
        padding: 40px;
        border-radius: 40px; 
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 25px 50px rgba(0, 0, 0, 1); 
        transform: translateY(30px);
        transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        position: relative;
    }

    .withdraw-overlay.active { display: flex !important; opacity: 1; }
    .withdraw-overlay.active .withdraw-box { transform: translateY(0); }

    .btn-withdraw-close {
        position: absolute; top: 25px; right: 25px; 
        color: #64748b; font-size: 24px; cursor: pointer;
    }

    .input-withdraw {
        width: 100%; background: #000; border: 1px solid rgba(255,255,255,0.2);
        color: #fff; border-radius: 15px; padding: 18px; font-weight: 700;
        margin-top: 10px; outline: none;
    }

    .info-rekening {
        width: 100%; background: #000; border: 1px solid rgba(255,255,255,0.2);
        color: #fff; border-radius: 15px; padding: 18px; font-size: 13px;
        margin-top: 10px; height: 100px; resize: none; outline: none;
    }
</style>

<!-- POPUP MODAL -->
<div class="withdraw-overlay" id="withdrawOverlay" onclick="toggleWithdrawPopup(false)">
    <div class="withdraw-box" onclick="event.stopPropagation()">
        <span class="btn-withdraw-close" onclick="toggleWithdrawPopup(false)">&times;</span>
        
        <div style="text-align: center; margin-bottom: 25px;">
            <div style="background: rgba(34, 197, 94, 0.1); width: 65px; height: 65px; border-radius: 20px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px;">
                <i class="fas fa-university" style="color: #22c55e; font-size: 26px;"></i>
            </div>
            <h3 style="color: #fff; font-weight: 800; font-size: 22px;">Penarikan Dana</h3>
            <p style="color: #94a3b8; font-size: 13px;">Saldo tersedia: <span style="color: #4ade80; font-weight: 700;">Rp {{ number_format(auth()->user()->shop->balance ?? 0, 0, ',', '.') }}</span></p>
        </div>

        <form action="{{ route('owner.withdraw') }}" method="POST">
            @csrf
            <div style="margin-bottom: 20px;">
                <label style="color: #fcd34d; font-size: 11px; font-weight: 800; text-transform: uppercase;">Nominal Penarikan:</label>
                <input type="number" name="amount" class="input-withdraw" placeholder="Min. 10.000" min="10000" max="{{ auth()->user()->shop->balance ?? 0 }}" required>
            </div>

            <div style="margin-bottom: 25px;">
                <label style="color: #fcd34d; font-size: 11px; font-weight: 800; text-transform: uppercase;">Detail Rekening:</label>
                <textarea name="bank_info" class="info-rekening" placeholder="Nama Bank - Nomor Rekening - Atas Nama" required></textarea>
            </div>

            <button type="submit" class="btn-logout" style="width: 100%; padding: 18px; font-size: 14px; background: #22c55e; justify-content: center;">
                KONFIRMASI PENARIKAN
            </button>
        </form>
    </div>
</div>

<script>
    // Pastikan fungsi ini tersedia secara global
    window.toggleWithdrawPopup = function(show) {
        const overlay = document.getElementById('withdrawOverlay');
        if (!overlay) return;

        if (show) {
            overlay.style.display = 'flex';
            // Trigger animation
            setTimeout(() => {
                overlay.classList.add('active');
            }, 10);
        } else {
            overlay.classList.remove('active');
            // Tunggu animasi selesai baru hide
            setTimeout(() => {
                overlay.style.display = 'none';
            }, 400);
        }
    }
</script>