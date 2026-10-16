<?php
include '../includes/koneksi.php';

// Set Zona Waktu agar sinkron dengan jam lo (Sesuaikan: Asia/Jakarta, Asia/Makassar, atau Asia/Jayapura)
date_default_timezone_set('Asia/Jayapura'); 

// Proteksi halaman dashboard admin
if (!isset($_SESSION['role'])) {
    echo "<script>window.location.href='dashboard.php';</script>";
    exit();
}

// Ambil format tanggal hari ini (YYYY-MM-DD)
$tgl_hari_ini = date('Y-m-d');

// 🌟 1. AMBIL DATA DINAMIS DARI DATABASE (TABEL MENU & PESANAN)
$query_varian = mysqli_query($koneksi, "SELECT COUNT(*) as total_menu FROM menu");
$data_varian = mysqli_fetch_assoc($query_varian);
$total_varian = $data_varian['total_menu'] ?? 0;

// Card Tengah: Hitung pesanan masuk HARI INI yang berstatus 'menunggu' atau 'diproses'
$query_pesanan_aktif = mysqli_query($koneksi, "SELECT COUNT(*) as total_aktif FROM pesanan WHERE DATE(created_at) = '$tgl_hari_ini' AND status IN ('menunggu', 'diproses')");
$data_pesanan_aktif = mysqli_fetch_assoc($query_pesanan_aktif);
$pesanan_aktif = $data_pesanan_aktif['total_aktif'] ?? 0;

// Card Kanan: Hitung pesanan yang 'selesai' khusus HARI INI
$query_pesanan_selesai = mysqli_query($koneksi, "SELECT COUNT(*) as total_selesai FROM pesanan WHERE DATE(created_at) = '$tgl_hari_ini' AND status = 'selesai'");
$data_pesanan_selesai = mysqli_fetch_assoc($query_pesanan_selesai);
$pesanan_selesai = $data_pesanan_selesai['total_selesai'] ?? 0;


// 🌟 2. LOGIC AKSI UPDATE STATUS ANTREAN
if (isset($_POST['btn_selesai'])) {
    $id_antrean = intval($_POST['id_antrean']);
    $nama_cust = htmlspecialchars($_POST['nama_customer']);
    
    // Update status ke database
    mysqli_query($koneksi, "UPDATE pesanan SET status='selesai' WHERE id_pesanan=$id_antrean");
    echo "<script>alert('Pesanan atas nama $nama_cust berhasil diselesaikan!'); window.location.href='dashboard.php?page=beranda';</script>";
}
?>

<div class="space-y-8 animate-fadeIn">
    
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-gray-200/60 pb-5">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Ringkasan <span class="text-orange-500">Aktivitas</span></h1>
            <p class="text-sm text-gray-500 font-medium mt-0.5">Halo <?php echo htmlspecialchars($_SESSION['username'] ?? 'Petugas'); ?>, berikut adalah performa pesanan gerobak lo hari ini.</p>
        </div>
        <div class="flex items-center gap-2 bg-white border border-gray-200 px-4 py-2 rounded-xl shadow-sm text-xs font-bold text-slate-600">
            <i data-lucide="calendar" class="w-4 h-4 text-orange-500"></i>
            <span><?php echo date('d M Y'); ?></span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <div class="bg-white rounded-2xl border-l-4 border-l-orange-500 border border-gray-100 p-6 shadow-sm hover:shadow-md transition duration-300 relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-black text-gray-400 uppercase tracking-wider">Varian Es Buah</p>
                    <h3 class="text-3xl font-black text-slate-800 mt-2"><?php echo $total_varian; ?> <span class="text-sm font-semibold text-gray-400">Rasa</span></h3>
                </div>
                <div class="bg-orange-50 text-orange-500 p-3 rounded-xl group-hover:scale-110 transition duration-300">
                    <i data-lucide="shopping-bag" class="w-6 h-6"></i>
                </div>
            </div>
            <div class="absolute -bottom-2 -right-2 text-slate-100 opacity-20 pointer-events-none">
                <i data-lucide="shopping-bag" class="w-24 h-24"></i>
            </div>
        </div>

        <div class="bg-white rounded-2xl border-l-4 border-l-amber-500 border border-gray-100 p-6 shadow-sm hover:shadow-md transition duration-300 relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-black text-gray-400 uppercase tracking-wider">Antrean Aktif Hari Ini</p>
                    <h3 class="text-3xl font-black text-slate-800 mt-2">
                        <?php echo $pesanan_aktif; ?> 
                        <span class="text-sm font-semibold <?php echo $pesanan_aktif > 0 ? 'text-amber-500 animate-pulse font-black' : 'text-gray-400'; ?>">
                            Belum Siap
                        </span>
                    </h3>
                </div>
                <div class="bg-amber-50 text-amber-500 p-3 rounded-xl group-hover:scale-110 transition duration-300">
                    <i data-lucide="clock" class="w-6 h-6"></i>
                </div>
            </div>
            <div class="absolute -bottom-2 -right-2 text-slate-100 opacity-20 pointer-events-none">
                <i data-lucide="clock" class="w-24 h-24"></i>
            </div>
        </div>

        <div class="bg-white rounded-2xl border-l-4 border-l-emerald-500 border border-gray-100 p-6 shadow-sm hover:shadow-md transition duration-300 relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-black text-gray-400 uppercase tracking-wider">Pesanan Selesai</p>
                    <h3 class="text-3xl font-black text-slate-800 mt-2">
                        <?php echo $pesanan_selesai; ?> 
                        <span class="text-sm font-semibold text-emerald-500 font-bold">Cup Lunas</span>
                    </h3>
                </div>
                <div class="bg-emerald-50 text-emerald-500 p-3 rounded-xl group-hover:scale-110 transition duration-300">
                    <i data-lucide="badge-check" class="w-6 h-6"></i>
                </div>
            </div>
            <div class="absolute -bottom-2 -right-2 text-slate-100 opacity-20 pointer-events-none">
                <i data-lucide="badge-check" class="w-24 h-24"></i>
            </div>
        </div>

    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="bg-orange-500 text-white p-1.5 rounded-lg">
                    <i data-lucide="list-ordered" class="w-4 h-4"></i>
                </div>
                <h2 class="text-lg font-black text-slate-800">Antrean Terbaru</h2>
            </div>
            <a href="dashboard.php?page=antrean" class="text-xs font-bold text-orange-500 hover:text-orange-600 flex items-center gap-1 transition">
                Lihat Semua <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/70 border-b border-gray-100 text-[11px] font-black tracking-wider text-slate-400 uppercase">
                        <th class="py-4 px-6">Customer</th>
                        <th class="py-4 px-6">Menu Pesanan</th>
                        <th class="py-4 px-6 text-center">Status</th>
                        <th class="py-4 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm font-semibold text-slate-700">
                    <?php
                    // Ambil 5 data teratas hari ini
                    $q_tabel = mysqli_query($koneksi, "SELECT * FROM pesanan WHERE DATE(created_at) = '$tgl_hari_ini' ORDER BY id_pesanan DESC LIMIT 5");
                    
                    if (mysqli_num_rows($q_tabel) > 0) {
                        while ($row = mysqli_fetch_assoc($q_tabel)) {
                    ?>
                    <tr class="hover:bg-slate-50/40 transition">
                        <td class="py-4 px-6">
                            <span class="font-black text-slate-800 block"><?php echo htmlspecialchars($row['nama_pelanggan']); ?></span>
                            <span class="text-[10px] text-gray-400 font-semibold block mt-0.5">
                                Jam: <?php echo date('H:i', strtotime($row['created_at'])); ?> WIB
                            </span>
                        </td>
                        <td class="py-4 px-6 text-xs font-medium text-slate-600 max-w-xs truncate">
                            <?php echo htmlspecialchars($row['detail_pesanan']); ?>
                        </td>
                        <td class="py-4 px-6 text-center">
                            <?php if (strtolower($row['status']) == 'menunggu') : ?>
                                <span class="inline-flex items-center gap-1 text-amber-600 bg-amber-50 text-[10px] font-black px-2.5 py-1 rounded-md uppercase tracking-wide border border-amber-100">
                                    Menunggu
                                </span>
                            <?php elseif (strtolower($row['status']) == 'diproses') : ?>
                                <span class="inline-flex items-center gap-1 text-orange-600 bg-orange-50 text-[10px] font-black px-2.5 py-1 rounded-md uppercase tracking-wide border border-orange-100">
                                    Proses
                                </span>
                            <?php else : ?>
                                <span class="inline-flex items-center gap-1 text-emerald-600 bg-emerald-50 text-[10px] font-black px-2.5 py-1 rounded-md uppercase tracking-wide border border-emerald-100">
                                    Selesai
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="py-4 px-6 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <?php if (strtolower($row['status']) != 'selesai') : ?>
                                    <a href="dashboard.php?page=antrean" class="text-xs font-black bg-emerald-500 hover:bg-emerald-600 text-white px-3 py-1.5 rounded-xl transition shadow-sm inline-flex items-center gap-1">
                                        Kelola
                                    </a>
                                <?php else : ?>
                                    <span class="text-xs text-gray-400 italic font-bold">Selesai</span>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php 
                        }
                    } else {
                        echo "<tr><td colspan='4' class='py-12 text-center text-gray-400 font-bold'>Belum ada antrean pesanan es buah yang masuk!</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<style>
    .animate-fadeIn {
        animation: fadeIn 0.4s ease-out forwards;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(8px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>