<?php
// Pastikan session sudah aktif biar nama adminnya muncul dinamis
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../includes/koneksi.php';

// Ambil data session login, kasih fallback biar gak error kalau session kosong
$id_admin_login = isset($_SESSION['id']) ? intval($_SESSION['id']) : 0;
$query_header   = mysqli_query($koneksi, "SELECT * FROM user WHERE id_user = $id_admin_login");
$data_admin     = mysqli_fetch_assoc($query_header);

$nama_admin = $data_admin['nama'] ?? 'Admin';
$role_admin = $data_admin['role'] ?? 'Admin';
$foto_admin = $data_admin['foto'] ?? '';
?>

<header class="bg-white/80 backdrop-blur-md border-b border-gray-100 sticky top-0 z-40 px-6 py-3 flex items-center justify-between transition-all relative">
    
    <div class="flex items-center gap-4">
        <button id="btn-toggle-sidebar" class="p-2 text-slate-500 hover:bg-slate-50 rounded-xl lg:hidden transition">
            <i data-lucide="menu" class="w-5 h-5"></i>
        </button>
        
        <div class="hidden sm:block">
            <h2 class="text-sm font-bold text-slate-700">Selamat Bekerja, <span class="text-emerald-500"><?php echo htmlspecialchars($nama_admin); ?></span>! 👋</h2>
            <p class="text-[11px] text-gray-400 font-semibold mt-0.5" id="live-clock">Memuat waktu...</p>
        </div>
    </div>

    <div class="flex items-center gap-4">
        
        <div class="h-6 w-px bg-gray-100 hidden sm:block"></div>

        <div class="relative">
            <button id="profileDropdownBtn" class="flex items-center gap-3 hover:bg-slate-50 p-1.5 rounded-xl transition focus:outline-none text-left">
                <?php if (!empty($foto_admin) && file_exists('../uploads/' . $foto_admin)) : ?>
                    <img src="../uploads/<?php echo $foto_admin; ?>" class="w-9 h-9 rounded-xl object-cover shadow-sm border border-gray-100">
                <?php else : ?>
                    <div class="w-9 h-9 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 font-black text-sm uppercase shadow-sm">
                        <?php echo substr($nama_admin, 0, 2); ?>
                    </div>
                <?php endif; ?>
                
                <div class="hidden md:block">
                    <span class="block text-xs font-black text-slate-800 tracking-tight leading-none"><?php echo htmlspecialchars($nama_admin); ?></span>
                    <span class="inline-block text-[9px] font-black text-purple-600 bg-purple-50 border border-purple-100 px-1.5 py-0.5 rounded uppercase tracking-wider mt-1">
                        <?php echo htmlspecialchars($role_admin); ?>
                    </span>
                </div>
                <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-slate-400 hidden md:block"></i>
            </button>

            <div id="profileDropdownMenu" class="hidden absolute right-0 mt-2 w-48 bg-white border border-gray-100 rounded-xl shadow-xl z-50 py-1.5 transform origin-top-right transition-all">
                <div class="px-4 py-2 border-b border-gray-50">
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Akses Masuk</p>
                    <p class="text-xs font-black text-slate-700 truncate mt-0.5"><?php echo htmlspecialchars($data_admin['email'] ?? 'email@domain.com'); ?></p>
                </div>
                
                <a href="dashboard.php?page=edit_profil" class="flex items-center gap-2 px-4 py-2.5 text-xs font-bold text-slate-600 hover:bg-slate-50 transition">
                    <i data-lucide="user-cog" class="w-4 h-4 text-slate-400"></i> Edit Profil Anda
                </a>
                
                <hr class="border-gray-50 my-1">
            </div>
        </div>

        <div class="h-6 w-px bg-gray-100"></div>

        <a href="../logout.php" onclick="return confirm('Yakin ingin keluar dari sistem dashboard?')" 
           class="p-2 bg-red-50 hover:bg-red-500 text-red-500 hover:text-white rounded-xl transition-all duration-200 shadow-sm flex items-center gap-2 group"
           title="Logout Aplikasi">
            <i data-lucide="log-out" class="w-4 h-4 group-hover:scale-110 transition"></i>
            <span class="text-xs font-bold hidden sm:inline">Keluar</span>
        </a>

    </div>
</header>

<script>
    // 1. Logika Jam Live Real-time Indonesia
    function updateClock() {
        const now = new Date();
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        const dateString = now.toLocaleDateString('id-ID', options);
        const timeString = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        
        const clockElement = document.getElementById('live-clock');
        if(clockElement) {
            clockElement.textContent = `${dateString} — ${timeString} WIT`;
        }
    }
    setInterval(updateClock, 1000);
    updateClock(); // Jalankan instan pertama kali

    // 🌟 2. Tambahan Script Toggle Dropdown Menu Profil
    document.addEventListener('DOMContentLoaded', function() {
        const dropBtn = document.getElementById('profileDropdownBtn');
        const dropMenu = document.getElementById('profileDropdownMenu');

        if (dropBtn && dropMenu) {
            dropBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                dropMenu.classList.toggle('hidden');
            });

            // Otomatis tutup menu dropdown jika admin klik di luar area profil
            document.addEventListener('click', function() {
                dropMenu.classList.add('hidden');
            });
        }
    });
</script>