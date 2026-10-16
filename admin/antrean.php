<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../includes/koneksi.php';

// 🌟 1. LOGIKA UPDATE STATUS ANTREAN CEPAT
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id_pesanan = intval($_GET['id']);
    $action = $_GET['action'];
    
    if ($action == 'proses') {
        $update_status = "UPDATE pesanan SET status = 'diproses' WHERE id_pesanan = $id_pesanan";
    } elseif ($action == 'selesai') {
        $update_status = "UPDATE pesanan SET status = 'selesai' WHERE id_pesanan = $id_pesanan";
    }

    if (mysqli_query($koneksi, $update_status)) {
        echo "<script>window.location.href='dashboard.php?page=antrean';</script>";
        exit();
    }
}

// 🌟 2. HITUNG RINGKASAN DATA (UNTUK CARDS INFO)
$tgl_hari_ini = date('Y-m-d');
$hitung_menunggu = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM pesanan WHERE status = 'menunggu' AND DATE(created_at) = '$tgl_hari_ini'");
$data_menunggu   = mysqli_fetch_assoc($hitung_menunggu)['total'];

$hitung_proses   = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM pesanan WHERE status = 'diproses' AND DATE(created_at) = '$tgl_hari_ini'");
$data_proses     = mysqli_fetch_assoc($hitung_proses)['total'];

$hitung_selesai  = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM pesanan WHERE status = 'selesai' AND DATE(created_at) = '$tgl_hari_ini'");
$data_selesai    = mysqli_fetch_assoc($hitung_selesai)['total'];
?>

<div class="space-y-8 animate-fadeIn">
    
    <div class="border-b border-gray-200/60 pb-5">
        <h1 class="text-2xl font-black text-slate-800 tracking-tight">Monitoring <span class="text-emerald-500">Antrean Pesanan</span></h1>
        <p class="text-sm text-gray-500 font-medium mt-0.5">Pantau, buat, dan selesaikan racikan es buah pesanan pelanggan hari ini.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Belum Dibuat</span>
                <span class="text-3xl font-black text-slate-800 block"><?php echo $data_menunggu; ?></span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-500">
                <i data-lucide="clock" class="w-6 h-6"></i>
            </div>
        </div>

        <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Sedang Diracik</span>
                <span class="text-3xl font-black text-orange-500 block"><?php echo $data_proses; ?></span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-orange-50 border border-orange-100 flex items-center justify-center text-orange-500">
                <i data-lucide="cooking-pot" class="w-6 h-6"></i>
            </div>
        </div>

        <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Sudah Diambil</span>
                <span class="text-3xl font-black text-emerald-500 block"><?php echo $data_selesai; ?></span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-500">
                <i data-lucide="check-circle-2" class="w-6 h-6"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between flex-wrap gap-3">
            <div class="flex items-center gap-2">
                <i data-lucide="list-ordered" class="w-5 h-5 text-emerald-500"></i>
                <h2 class="text-base font-black text-slate-800">Alur Urutan Antrean Aktif</h2>
            </div>
            <span class="text-xs font-bold text-gray-400 bg-slate-50 border border-gray-200/60 px-3 py-1.5 rounded-lg">
                Hari ini: <?php echo date('d M Y'); ?>
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/70 border-b border-gray-100 text-[11px] font-black tracking-wider text-slate-400 uppercase">
                        <th class="py-4 px-6 text-center w-24">No. Antrean</th>
                        <th class="py-4 px-6">Nama Pelanggan</th>
                        <th class="py-4 px-6">Detail Cup Es Buah</th>
                        <th class="py-4 px-6">Total Bayar</th>
                        <th class="py-4 px-6 text-center">Status</th>
                        <th class="py-4 px-6 text-center">Tindakan Alur</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm font-semibold text-slate-700">
                    <?php
                    // Ambil pesanan khusus hari ini, urutkan dari status menunggu -> diproses -> selesai
                    $query_antrean = mysqli_query($koneksi, "SELECT * FROM pesanan WHERE DATE(created_at) = '$tgl_hari_ini' ORDER BY FIELD(status, 'diproses', 'menunggu', 'selesai'), id_pesanan ASC");
                    
                    if (mysqli_num_rows($query_antrean) > 0) {
                        while ($row = mysqli_fetch_assoc($query_antrean)) {
                    ?>
                    <tr class="hover:bg-slate-50/40 transition">
                        <td class="py-4 px-6 text-center">
                            <span class="inline-block bg-slate-900 text-white font-black text-sm px-3 py-1 rounded-xl shadow-sm">
                                <?php echo htmlspecialchars($row['nomor_antrean']); ?>
                            </span>
                        </td>
                        
                        <td class="py-4 px-6">
                            <span class="font-black text-slate-800 block"><?php echo htmlspecialchars($row['nama_pelanggan']); ?></span>
                            <span class="text-[11px] text-gray-400 font-bold block mt-0.5">ID Order: #<?php echo $row['id_pesanan']; ?></span>
                        </td>
                        
                        <td class="py-4 px-6 text-xs text-slate-600 font-medium max-w-xs">
                            <p class="leading-relaxed whitespace-pre-line"><?php echo htmlspecialchars($row['detail_pesanan']); ?></p>
                        </td>
                        
                        <td class="py-4 px-6 text-slate-800 font-bold text-xs">
                            Rp <?php echo number_format($row['total_bayar'], 0, ',', '.'); ?>
                        </td>
                        
                        <td class="py-4 px-6 text-center">
                            <?php if ($row['status'] == 'menunggu') : ?>
                                <span class="inline-flex items-center gap-1 text-amber-600 bg-amber-50 text-[10px] font-black px-2.5 py-1 rounded-lg uppercase tracking-wide border border-amber-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Menunggu
                                </span>
                            <?php elseif ($row['status'] == 'diproses') : ?>
                                <span class="inline-flex items-center gap-1 text-orange-600 bg-orange-50 text-[10px] font-black px-2.5 py-1 rounded-lg uppercase tracking-wide border border-orange-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-orange-500 animate-bounce"></span> Diracik
                                </span>
                            <?php else : ?>
                                <span class="inline-flex items-center gap-1 text-emerald-600 bg-emerald-50 text-[10px] font-black px-2.5 py-1 rounded-lg uppercase tracking-wide border border-emerald-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Selesai
                                </span>
                            <?php endif; ?>
                        </td>
                        
                        <td class="py-4 px-6 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <?php if ($row['status'] == 'menunggu') : ?>
                                    <a href="dashboard.php?page=antrean&action=proses&id=<?php echo $row['id_pesanan']; ?>" 
                                       class="text-xs font-black bg-orange-500 hover:bg-orange-600 text-white px-3 py-1.5 rounded-xl transition shadow-sm flex items-center gap-1">
                                        <i data-lucide="play" class="w-3.5 h-3.5"></i> Racik Es
                                    </a>
                                <?php elseif ($row['status'] == 'diproses') : ?>
                                    <a href="dashboard.php?action=selesaikan_pesanan&id=<?php echo $row['id_pesanan']; ?>&from=beranda" 
                                       class="text-xs font-black bg-emerald-500 hover:bg-emerald-600 text-white px-3 py-1.5 rounded-xl transition shadow-sm flex items-center gap-1">
                                        <i data-lucide="check" class="w-3.5 h-3.5"></i> Selesai Siap Ambil
                                    </a>
                                <?php else : ?>
                                    <span class="text-xs text-slate-400 italic font-bold px-3 py-1.5 bg-slate-50 border border-gray-100 rounded-xl inline-flex items-center gap-1">
                                        <i data-lucide="smile" class="w-3.5 h-3.5 text-emerald-500"></i> Done
                                    </span>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php 
                        }
                    } else {
                        echo "<tr><td colspan='6' class='py-16 text-center text-gray-400 font-bold'>Belum ada antrean masuk untuk hari ini!</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>