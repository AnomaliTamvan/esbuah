<?php
// 🌟 PERBAIKAN UTAMA 1: Wajib aktifkan session di paling atas file!
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../includes/koneksi.php';

// Proteksi halaman admin: Cuma role 'admin' yang bisa kelola petugas
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    echo "<script>window.location.href='dashboard.php';</script>";
    exit();
}

// 🌟 1. PROSES PHP LOGIC (CRUD USER DENGAN REFRESH OTOMATIS)
// A. Aksi Tambah Pengguna Baru
if (isset($_POST['btn_tambah_user'])) {
    $nama     = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $email    = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = md5($_POST['password']); 
    $role     = mysqli_real_escape_string($koneksi, $_POST['role']);
    $status   = 'active'; 

    // Validasi double input: Cek apakah username atau email udah ada yang pakai
    $cek_user = mysqli_query($koneksi, "SELECT * FROM user WHERE username = '$username' OR email = '$email'");
    if (mysqli_num_rows($cek_user) > 0) {
        echo "<script>alert('Username atau Email itu udah terdaftar di sistem!'); window.location.href='dashboard.php?page=kelola_user';</script>";
    } else {
        // PERBAIKAN UTAMA 2: ID user dibiarkan auto_increment (tidak perlu di-insert NULL manual jika bermasalah)
        $query = "INSERT INTO user (nama, username, email, password, role, status) 
                  VALUES ('$nama', '$username', '$email', '$password', '$role', '$status')";
        
        if (mysqli_query($koneksi, $query)) {
            // Sukses langsung lempar balik ke page kelola_user agar data langsung ke-render baru
            echo "<script>alert('Pengguna baru berhasil ditambahkan!'); window.location.href='dashboard.php?page=kelola_user';</script>";
            exit();
        } else {
            echo "<script>alert('Gagal nambah data: " . mysqli_error($koneksi) . "');</script>";
        }
    }
}

// B. Aksi Hapus Pengguna
if (isset($_GET['action']) && $_GET['action'] == 'hapus_user') {
    $id_user = intval($_GET['id']);
    
    // Ambil ID session login lo dengan aman
    $session_id = isset($_SESSION['id']) ? intval($_SESSION['id']) : 0;
    
    // Proteksi: Jangan biarkan admin menghapus dirinya sendiri yang sedang login
    if ($id_user === $session_id && $session_id !== 0) {
        echo "<script>alert('Akun tidak dapat dihapus!'); window.location.href='dashboard.php?page=kelola_user';</script>";
    } else {
        $query_hapus = "DELETE FROM user WHERE id_user = $id_user";
        if (mysqli_query($koneksi, $query_hapus)) {
            echo "<script>alert('Akun pengguna berhasil dihapus!'); window.location.href='dashboard.php?page=kelola_user';</script>";
            exit();
        }
    }
}
?>

<div class="space-y-8 animate-fadeIn">
    
    <div class="border-b border-gray-200/60 pb-5">
        <h1 class="text-2xl font-black text-slate-800 tracking-tight">Manajemen <span class="text-emerald-500">Akses Pengguna</span></h1>
        <p class="text-sm text-gray-500 font-medium mt-0.5">Kelola hak akses dan akun tim Es Buah anda langsung dari database.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm sticky top-6">
            <div class="flex items-center gap-2 mb-5 pb-3 border-b border-gray-50">
                <i data-lucide="user-plus" class="w-5 h-5 text-emerald-500"></i>
                <h2 class="text-base font-black text-slate-800">Tambah Akun Baru</h2>
            </div>

            <form action="" method="POST" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wide mb-1.5">Nama Lengkap</label>
                    <input type="text" name="nama" placeholder="Nama Lengkap" required autocomplete="off"
                        class="w-full bg-slate-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-semibold focus:outline-none focus:border-emerald-500 transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wide mb-1.5">Username</label>
                    <input type="text" name="username" placeholder="Username" required autocomplete="off"
                        class="w-full bg-slate-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-semibold focus:outline-none focus:border-emerald-500 transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wide mb-1.5">Alamat Email</label>
                    <input type="email" name="email" placeholder="Email@domain.com" required autocomplete="off"
                        class="w-full bg-slate-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-semibold focus:outline-none focus:border-emerald-500 transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wide mb-1.5">Password</label>
                    <input type="password" name="password" placeholder="••••••••" required
                        class="w-full bg-slate-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-semibold focus:outline-none focus:border-emerald-500 transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wide mb-1.5">Hak Akses (Role)</label>
                    <select name="role" required 
                        class="w-full bg-slate-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-semibold focus:outline-none focus:border-emerald-500 transition cursor-pointer">
                        <option value="petugas">Petugas / Kasir</option>
                        <option value="admin">Administrator</option>
                        <option value="pelanggan">Pelanggan</option>
                    </select>
                </div>

                <button type="submit" name="btn_tambah_user" 
                    class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-extrabold text-sm py-3 rounded-xl shadow-md shadow-emerald-500/10 transition flex items-center justify-center gap-2 mt-2">
                    <i data-lucide="shield-check" class="w-4 h-4"></i> Simpan Data Pengguna
                </button>
            </form>
        </div>

        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex items-center gap-2">
                <i data-lucide="users" class="w-5 h-5 text-orange-500"></i>
                <h2 class="text-base font-black text-slate-800">Daftar Akun Pengguna Terdaftar</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/70 border-b border-gray-100 text-[11px] font-black tracking-wider text-slate-400 uppercase">
                            <th class="py-4 px-4">Informasi Profil</th>
                            <th class="py-4 px-4">Kontak / Email</th>
                            <th class="py-4 px-4 text-center">Role</th>
                            <th class="py-4 px-4 text-center">Status</th>
                            <th class="py-4 px-6 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 text-sm font-semibold text-slate-700">
                        <?php
                        // Ambil data real-time dengan select bintang (*) agar field terbaca sempurna
                        $query_tampil = mysqli_query($koneksi, "SELECT * FROM user ORDER BY id_user DESC");
                        if (mysqli_num_rows($query_tampil) > 0) {
                            while ($row = mysqli_fetch_assoc($query_tampil)) {
                                $current_row_id = isset($row['id_user']) ? intval($row['id_user']) : 0;
                                $logged_in_id   = isset($_SESSION['id']) ? intval($_SESSION['id']) : 0;
                        ?>
                        <tr class="hover:bg-slate-50/40 transition">
                            <td class="py-4 px-4">
                                <span class="font-black text-slate-800 block"><?php echo htmlspecialchars($row['nama']); ?></span>
                                <span class="text-xs text-gray-400 font-bold block mt-0.5">@<?php echo htmlspecialchars($row['username']); ?> (ID: #<?php echo $current_row_id; ?>)</span>
                            </td>
                            <td class="py-4 px-4 text-slate-600 text-xs font-medium">
                                <?php echo htmlspecialchars($row['email']); ?>
                            </td>
                            <td class="py-4 px-4 text-center">
                                <?php if ($row['role'] == 'admin') : ?>
                                    <span class="inline-flex items-center bg-purple-50 text-purple-600 text-[10px] font-black px-2.5 py-1 rounded-lg uppercase tracking-wide border border-purple-100">
                                        Admin
                                    </span>
                                <?php elseif ($row['role'] == 'petugas') : ?>
                                    <span class="inline-flex items-center bg-blue-50 text-blue-600 text-[10px] font-black px-2.5 py-1 rounded-lg uppercase tracking-wide border border-blue-100">
                                        Kasir
                                    </span>
                                <?php else : ?>
                                    <span class="inline-flex items-center bg-gray-100 text-gray-600 text-[10px] font-black px-2.5 py-1 rounded-lg uppercase tracking-wide border border-gray-200">
                                        Pelanggan
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="py-4 px-4 text-center">
                                <span class="inline-flex items-center gap-1 text-emerald-600 bg-emerald-50 text-[10px] font-black px-2 py-0.5 rounded-md uppercase tracking-wide border border-emerald-100">
                                    <span class="w-1 h-1 rounded-full bg-emerald-500"></span> <?php echo htmlspecialchars($row['status']); ?>
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <div class="flex items-center justify-center">
                                    <?php if ($current_row_id !== $logged_in_id) : ?>
                                        <a href="dashboard.php?page=kelola_user&action=hapus_user&id=<?php echo $current_row_id; ?>" 
                                           onclick="return confirm('Yakin ingin menghapus pengguna ini dari database?')"
                                           class="p-2 bg-red-50 text-red-500 hover:bg-red-500 hover:text-white rounded-xl transition shadow-sm"
                                           title="Hapus Pengguna">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </a>
                                    <?php else : ?>
                                        <span class="text-xs text-slate-400 italic font-medium px-2.5 py-1 bg-slate-50 border border-gray-100 rounded-lg">You</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php 
                            }
                        } else {
                            echo "<tr><td colspan='5' class='py-12 text-center text-gray-400 font-bold'>Belum ada akun pengguna terdaftar!</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<style>
    .animate-fadeIn { animation: fadeIn 0.4s ease-out forwards; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: translateY(0); } }
</style>