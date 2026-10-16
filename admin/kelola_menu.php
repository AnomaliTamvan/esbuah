<?php

include '../includes/koneksi.php';
// Proteksi halaman admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    echo "<script>window.location.href='dashboard.php';</script>";
    exit();
}

// 🌟 FUNGSI GENERATE KODE OTOMATIS (Mencari nomor urut terbesar)
$query_kode = mysqli_query($koneksi, "SELECT MAX(id_menu) as max_id FROM menu");
$data_kode = mysqli_fetch_assoc($query_kode);
$no_urut = $data_kode['max_id'] ? $data_kode['max_id'] + 1 : 1;
// Format kode otomatis: #MB- urutan angka (Contoh: #MB-1, #MB-2, dst)
$kode_otomatis = "MB-" . $no_urut;


// 🌟 1. PROSES PHP LOGIC
// A. Aksi Tambah Menu Baru
if (isset($_POST['btn_tambah'])) {
    // Kodenya langsung ambil dari variabel kode otomatis di atas, anti-manipulasi
    $kode = mysqli_real_escape_string($koneksi, $kode_otomatis);
    $nama_menu = mysqli_real_escape_string($koneksi, $_POST['nama_menu']);
    $harga_menu = intval($_POST['harga_menu']);
   
    
    // Proses Upload Gambar
    $gambar = $_FILES['gambar']['name'];
    $tmp = $_FILES['gambar']['tmp_name'];
    $path = "../uploads/" . $gambar; 

    if (move_uploaded_file($tmp, $path)) {
        $query = "INSERT INTO menu (kode, nama_menu, harga_menu, gambar) 
                  VALUES ('$kode', '$nama_menu', '$harga_menu', '$gambar')";
        if (mysqli_query($koneksi, $query)) {
            echo "<script>alert('Menu baru berhasil ditambahkan dengan kode $kode!'); window.location.href='dashboard.php?page=kelola_menu';</script>";
        }
    } else {
        echo "<script>alert('Gambar gagal diupload!');</script>";
    }
}

// B. Aksi Hapus Menu
if (isset($_GET['action']) && $_GET['action'] == 'hapus') {
    $id_menu = intval($_GET['id']);
    
    $ambil_gambar = mysqli_query($koneksi, "SELECT gambar FROM menu WHERE id_menu = $id_menu");
    $data_gambar = mysqli_fetch_assoc($ambil_gambar);
    if ($data_gambar['gambar'] != '') {
        unlink("../uploads/" . $data_gambar['gambar']);
    }

    $query_hapus = "DELETE FROM menu WHERE id_menu = $id_menu";
    if (mysqli_query($koneksi, $query_hapus)) {
        echo "<script>alert('Menu berhasil didelete!'); window.location.href='dashboard.php?page=kelola_menu';</script>";
    }
}
?>

<div class="space-y-8 animate-fadeIn">
    
    <div class="border-b border-gray-200/60 pb-5">
        <h1 class="text-2xl font-black text-slate-800 tracking-tight">Manajemen <span class="text-emerald-500">Katalog Menu</span></h1>
        <p class="text-sm text-gray-500 font-medium mt-0.5">Tambah dan kelola menu Es Buah.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm sticky top-6">
            <div class="flex items-center gap-2 mb-5 pb-3 border-b border-gray-50">
                <i data-lucide="plus-circle" class="w-5 h-5 text-emerald-500"></i>
                <h2 class="text-base font-black text-slate-800">Tambah Menu Baru</h2>
            </div>

            <form action="" method="POST" enctype="multipart/form-data" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wide mb-1.5">Kode Menu (Otomatis)</label>
                    <input type="text" value="<?php echo $kode_otomatis; ?>" readonly
                        class="w-full bg-slate-100 border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-black text-gray-500 cursor-not-allowed select-none focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wide mb-1.5">Nama Menu Kesegaran</label>
                    <input type="text" name="nama_menu" placeholder="Nama Es Buah..." required autocomplate="off"
                        class="w-full bg-slate-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-semibold focus:outline-none focus:border-emerald-500 transition">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wide mb-1.5">Harga (Rp)</label>
                        <input type="number" name="harga_menu" placeholder="Rp" required
                            class="w-full bg-slate-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-semibold focus:outline-none focus:border-emerald-500 transition">
                    </div>
            
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wide mb-1.5">Foto Menu (Gambar)</label>
                    <input type="file" name="gambar" required
                        class="w-full text-xs font-semibold text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-black file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                </div>

                <button type="submit" name="btn_tambah" 
                    class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-extrabold text-sm py-3 rounded-xl shadow-md shadow-emerald-500/10 transition flex items-center justify-center gap-2 mt-2">
                    <i data-lucide="save" class="w-4 h-4"></i> Simpan Data Menu
                </button>
            </form>
        </div>

        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex items-center gap-2">
                <i data-lucide="layers" class="w-5 h-5 text-orange-500"></i>
                <h2 class="text-base font-black text-slate-800">Daftar Menu Aktif</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/70 border-b border-gray-100 text-[11px] font-black tracking-wider text-slate-400 uppercase">
                            <th class="py-4 px-6 text-center w-20">Foto</th>
                            <th class="py-4 px-4">Info Menu</th>
                            <th class="py-4 px-4">Harga</th>
                            <th class="py-4 px-6 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 text-sm font-semibold text-slate-700">
                        <?php
                        $query_tampil = mysqli_query($koneksi, "SELECT * FROM menu ORDER BY id_menu DESC");
                        if (mysqli_num_rows($query_tampil) > 0) {
                            while ($row = mysqli_fetch_assoc($query_tampil)) {
                        ?>
                        <tr class="hover:bg-slate-50/40 transition">
                            <td class="py-4 px-6 text-center">
                                <img src="../uploads/<?php echo htmlspecialchars($row['gambar']); ?>" 
                                     alt="Menu" class="w-12 h-12 object-cover rounded-xl border border-gray-100 mx-auto shadow-sm">
                            </td>
                            <td class="py-4 px-4">
                                <span class="text-xs font-bold text-slate-400 block mb-0.5"><?php echo htmlspecialchars($row['kode']); ?></span>
                                <span class="font-bold text-slate-800 block"><?php echo htmlspecialchars($row['nama_menu']); ?></span>
                            </td>
                            <td class="py-4 px-4 font-bold text-slate-800">
                                Rp <?php echo number_format($row['harga_menu'], 0, ',', '.'); ?>
                            </td>
                            
                            <td class="py-4 px-6 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="dashboard.php?page=kelola_menu&action=hapus&id=<?php echo $row['id_menu']; ?>" 
                                       onclick="return confirm('Yakin ingin menghapus menu ini dari gerobak?')"
                                       class="p-2 bg-red-50 text-red-500 hover:bg-red-500 hover:text-white rounded-xl transition shadow-sm"
                                       title="Hapus Menu">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php 
                            }
                        } else {
                            echo "<tr><td colspan='5' class='py-12 text-center text-gray-400 font-bold'>Gerobak kosong, belum ada menu terdaftar!</td></tr>";
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