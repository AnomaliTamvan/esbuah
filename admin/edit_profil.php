<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../includes/koneksi.php';

// Pastikan yang akses sudah login dan punya ID di session
$id_user = isset($_SESSION['id']) ? intval($_SESSION['id']) : 0;
if ($id_user === 0) {
    echo "<script>window.location.href='../login.php';</script>";
    exit();
}

// 🌟 1. PROSES KETIKA TOMBOL SIMPAN DIKLIK
if (isset($_POST['btn_update_profil'])) {
    $nama     = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $email    = mysqli_real_escape_string($koneksi, $_POST['email']);
    
    // Ambil data lama di database untuk pengecekan
    $get_old  = mysqli_query($koneksi, "SELECT foto, password FROM user WHERE id_user = $id_user");
    $old_data = mysqli_fetch_assoc($get_old);
    $foto_db  = $old_data['foto'];

    // Logika ganti password: Kalau diisi berarti ganti, kalau kosong pakai password lama
    if (!empty($_POST['password'])) {
        $password = md5($_POST['password']);
    } else {
        $password = $old_data['password'];
    }

    // Logika Upload Foto Profil
    if (!empty($_FILES['foto']['name'])) {
        $nama_file = $_FILES['foto']['name'];
        $tmp_file  = $_FILES['foto']['tmp_name'];
        $ekstensi  = pathinfo($nama_file, PATHINFO_EXTENSION);
        
        // Validasi format gambar yang diperbolehkan
        $format_diizinkan = ['jpg', 'jpeg', 'png', 'webp'];
        if (in_array(strtolower($ekstensi), $format_diizinkan)) {
            
            // Nama foto dibikin unik biar gak tabrakan antar user
            $foto_baru = "avatar_" . $id_user . "_" . time() . "." . $ekstensi;
            $target_dir = "../uploads/";

            // Bikin folder uploads secara otomatis kalau belum ada
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0755, true);
            }

            // Hapus file foto profil lama dari server jika ada, biar hemat storage
            if (!empty($foto_db) && file_exists($target_dir . $foto_db)) {
                unlink($target_dir . $foto_db);
            }

            // Pindahkan file baru ke folder uploads
            if (move_uploaded_file($tmp_file, $target_dir . $foto_baru)) {
                $foto_db = $foto_baru;
            }
        } else {
            echo "<script>alert('Format foto harus gambar (JPG, JPEG, PNG, atau WEBP)!');</script>";
        }
    }

    // Eksekusi Update ke Database
    $update_query = "UPDATE user SET nama='$nama', username='$username', email='$email', password='$password', foto='$foto_db' WHERE id_user = $id_user";
    
    if (mysqli_query($koneksi, $update_query)) {
        // Update session username & role secara real-time untuk keperluan header
        $_SESSION['username'] = $username;
        
        echo "<script>alert('Profil berhasil diperbarui!'); window.location.href='dashboard.php?page=edit_profil';</script>";
        exit();
    } else {
        echo "<script>alert('Gagal memperbarui profil: " . mysqli_error($koneksi) . "');</script>";
    }
}

// 🌟 2. AMBIL DATA USER TERBARU UNTUK DITAMPILKAN DI FORM
$query_user = mysqli_query($koneksi, "SELECT * FROM user WHERE id_user = $id_user");
$user       = mysqli_fetch_assoc($query_user);
?>

<div class="max-w-2xl mx-auto space-y-6 animate-fadeIn">
    
    <div class="border-b border-gray-200/60 pb-4">
        <h1 class="text-xl font-black text-slate-800 tracking-tight">Pengaturan <span class="text-emerald-500">Akun Pribadi</span></h1>
        <p class="text-xs text-gray-400 font-medium mt-0.5">Ubah biodata, ganti password, dan kelola foto avatar anda di sini.</p>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 p-8 shadow-sm">
        <form action="" method="POST" enctype="multipart/form-data" class="space-y-6">
            
            <div class="flex items-center gap-5 pb-6 border-b border-gray-50">
                <div class="relative group">
                    <?php if (!empty($user['foto']) && file_exists('../uploads/' . $user['foto'])) : ?>
                        <img src="../uploads/<?php echo $user['foto']; ?>" class="w-20 h-20 rounded-2xl object-cover border-2 border-emerald-500/20 shadow-md transition group-hover:scale-105 duration-200">
                    <?php else : ?>
                        <div class="w-20 h-20 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 font-black text-xl uppercase shadow-sm">
                            <?php echo substr($user['nama'] ?? 'AD', 0, 2); ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="space-y-1">
                    <h3 class="text-sm font-black text-slate-800"><?php echo htmlspecialchars($user['nama'] ?? 'Pengguna'); ?></h3>
                    <p class="text-xs text-gray-400 font-semibold inline-flex items-center bg-purple-50 text-purple-600 px-2 py-0.5 rounded-md uppercase tracking-wider border border-purple-100">
                        Role: <?php echo htmlspecialchars($user['role'] ?? 'Petugas'); ?>
                    </p>
                    <div class="pt-1">
                        <label class="inline-block text-[11px] font-black text-emerald-600 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 px-3 py-1.5 rounded-xl cursor-pointer transition shadow-sm">
                            <i data-lucide="camera" class="w-3.5 h-3.5 inline mr-1"></i> Pilih Foto Baru
                            <input type="file" name="foto" class="hidden" accept="image/*">
                        </label>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wide mb-1.5">Nama Lengkap</label>
                    <input type="text" name="nama" value="<?php echo htmlspecialchars($user['nama'] ?? ''); ?>" required autocomplete="off"
                        class="w-full bg-slate-50 border border-gray-200 rounded-xl px-4 py-2.5 text-xs font-semibold focus:outline-none focus:border-emerald-500 transition">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wide mb-1.5">Username</label>
                    <input type="text" name="username" value="<?php echo htmlspecialchars($user['username'] ?? ''); ?>" required autocomplete="off"
                        class="w-full bg-slate-50 border border-gray-200 rounded-xl px-4 py-2.5 text-xs font-semibold focus:outline-none focus:border-emerald-500 transition">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wide mb-1.5">Alamat Email</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required autocomplete="off"
                    class="w-full bg-slate-50 border border-gray-200 rounded-xl px-4 py-2.5 text-xs font-semibold focus:outline-none focus:border-emerald-500 transition">
            </div>

            <div class="pt-4 border-t border-gray-50">
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wide mb-1.5">Ganti Password (Kosongkan jika tidak ingin diganti)</label>
                <input type="password" name="password" placeholder="Biar aman ganti berkala ya ••••••••"
                    class="w-full bg-slate-50 border border-gray-200 rounded-xl px-4 py-2.5 text-xs font-semibold focus:outline-none focus:border-emerald-500 transition">
            </div>

            <button type="submit" name="btn_update_profil"
                class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-extrabold text-xs py-3.5 rounded-xl shadow-md shadow-emerald-500/10 transition flex items-center justify-center gap-2 mt-2">
                <i data-lucide="shield-check" class="w-4 h-4"></i> Simpan Semua Perubahan Profil
            </button>

        </form>
    </div>
</div>