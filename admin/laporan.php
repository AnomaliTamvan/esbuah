<?php
// Pastiin session udah jalan
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../includes/koneksi.php';

// Proteksi halaman admin: Cuma role 'admin' atau 'petugas' yang bisa akses laporan
if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'petugas')) {
    echo "<script>window.location.href='../index.php';</script>";
    exit();
}

// 🌟 1. LOGIKA FILTER TANGGAL PERIODE LAPORAN
// Default-nya diset tanggal hari ini jika admin belum memfilter
$tgl_mulai  = isset($_GET['tgl_mulai']) ? mysqli_real_escape_string($koneksi, $_GET['tgl_mulai']) : date('Y-m-d');
$tgl_sampai = isset($_GET['tgl_sampai']) ? mysqli_real_escape_string($koneksi, $_GET['tgl_sampai']) : date('Y-m-d');

// 🌟 2. LOGIKA MATANG: HITUNG RINGKASAN OMSET DARI TABEL PERMANEN LO
$query_ringkasan = mysqli_query($koneksi, "
    SELECT 
        SUM(total_bayar) AS total_pendapatan,
        COUNT(id_laporan) AS total_transaksi
    FROM laporan_penjualan 
    WHERE DATE(tanggal_selesai) BETWEEN '$tgl_mulai' AND '$tgl_sampai'
");
$data_ringkasan = mysqli_fetch_assoc($query_ringkasan);
$omset = $data_ringkasan['total_pendapatan'] ?? 0;
$transaksi = $data_ringkasan['total_transaksi'] ?? 0;

// 🌟 3. QUERY UTAMA AMBIL DATA DAFTAR RIWAYAT DARI TABEL KELOLA LAPORAN LO
$query_laporan = mysqli_query($koneksi, "
    SELECT * FROM laporan_penjualan 
    WHERE DATE(tanggal_selesai) BETWEEN '$tgl_mulai' AND '$tgl_sampai'
    ORDER BY id_laporan DESC
");
?>

<div class="p-6 space-y-6">
    
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-100 pb-5">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Laporan Keuangan <span class="text-orange-500">Es Buah</span></h1>
            <p class="text-xs text-gray-400 font-medium mt-0.5">Data laporan di bawah diambil dari arsip permanen database.</p>
        </div>
        
        <form method="GET" action="" class="flex items-end gap-3 flex-wrap bg-white p-3 border border-slate-100 rounded-2xl shadow-sm hide-on-print">
            <input type="hidden" name="page" value="laporan">
            
            <div class="space-y-1">
                <label class="text-[10px] font-black uppercase text-gray-400 block">Dari Periode</label>
                <input type="date" name="tgl_mulai" value="<?php echo $tgl_mulai; ?>" class="bg-slate-50 border border-slate-200 text-xs font-bold p-2 rounded-xl focus:outline-none focus:border-orange-500">
            </div>
            
            <div class="space-y-1">
                <label class="text-[10px] font-black uppercase text-gray-400 block">Sampai Periode</label>
                <input type="date" name="tgl_sampai" value="<?php echo $tgl_sampai; ?>" class="bg-slate-50 border border-slate-200 text-xs font-bold p-2 rounded-xl focus:outline-none focus:border-orange-500">
            </div>
            
            <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white font-extrabold text-xs px-4 py-2.5 rounded-xl transition shadow-sm flex items-center gap-1.5 h-[34px]">
                <i data-lucide="filter" class="w-3.5 h-3.5"></i> Filter Data
            </button>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white border border-slate-100 p-6 rounded-3xl shadow-sm flex items-center justify-between group hover:border-emerald-200 transition">
            <div class="space-y-2">
                <span class="text-xs font-black text-gray-400 uppercase tracking-wider block">Total Pendapatan (Omset Bersih)</span>
                <h3 class="text-2xl font-black text-slate-800 tracking-tight group-hover:text-emerald-600 transition">
                    Rp <?php echo number_format($omset, 0, ',', '.'); ?>
                </h3>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-500 border border-emerald-100 flex items-center justify-center shadow-inner">
                <i data-lucide="banknote" class="w-6 h-6"></i>
            </div>
        </div>

        <div class="bg-white border border-slate-100 p-6 rounded-3xl shadow-sm flex items-center justify-between group hover:border-orange-200 transition">
            <div class="space-y-2">
                <span class="text-xs font-black text-gray-400 uppercase tracking-wider block">Total Nota Lunas</span>
                <h3 class="text-2xl font-black text-slate-800 tracking-tight group-hover:text-orange-500 transition">
                    <?php echo $transaksi; ?> <span class="text-xs text-gray-400 font-bold">Transaksi Sukses</span>
                </h3>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-orange-50 text-orange-500 border border-orange-100 flex items-center justify-center shadow-inner">
                <i data-lucide="clipboard-check" class="w-6 h-6"></i>
            </div>
        </div>
    </div>

    <div class="bg-white border border-slate-100 rounded-3xl shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-50 flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center gap-2">
                <i data-lucide="database" class="w-4 h-4 text-orange-500"></i>
                <h2 class="text-sm font-black text-slate-800">Riwayat Transaksi Permanen</h2>
            </div>
            
            <button onclick="window.print()" class="text-xs font-bold text-slate-600 hover:text-orange-500 bg-slate-50 border border-slate-200 px-3 py-1.5 rounded-xl transition flex items-center gap-1.5 shadow-sm hide-on-print">
                <i data-lucide="printer" class="w-3.5 h-3.5"></i> Cetak Laporan Kertas / PDF
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/70 border-b border-slate-100 text-[11px] font-black uppercase text-gray-400 tracking-wider">
                        <th class="py-4 px-6 w-32">No. Antrean</th>
                        <th class="py-4 px-6">Nama Pelanggan</th>
                        <th class="py-4 px-6">Detail Kombinasi Es Buah</th>
                        <th class="py-4 px-6 w-44">Waktu Selesai</th>
                        <th class="py-4 px-6 w-40 text-right">Nominal Lunas</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm font-semibold text-slate-700">
                    <?php 
                    if (mysqli_num_rows($query_laporan) > 0) {
                        while ($row = mysqli_fetch_assoc($query_laporan)) :
                    ?>
                    <tr class="hover:bg-slate-50/40 transition">
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center text-slate-800 bg-slate-100 border border-slate-200 text-xs font-black px-2.5 py-1 rounded-lg uppercase">
                                <?php echo htmlspecialchars($row['nomor_antrean']); ?>
                            </span>
                        </td>
                        <td class="py-4 px-6 text-slate-900 font-bold">
                            <?php echo htmlspecialchars($row['nama_pelanggan']); ?>
                        </td>
                        <td class="py-4 px-6 text-xs text-slate-600 font-medium">
                            <?php echo htmlspecialchars($row['detail_pesanan']); ?>
                        </td>
                        <td class="py-4 px-6 text-xs text-gray-500 font-medium">
                            <?php echo date('d M Y - H:i', strtotime($row['tanggal_selesai'])); ?> WIB
                        </td>
                        <td class="py-4 px-6 text-right font-black text-emerald-600">
                            Rp <?php echo number_format($row['total_bayar'], 0, ',', '.'); ?>
                        </td>
                    </tr>
                    <?php 
                        endwhile;
                    } else {
                        echo "<tr><td colspan='5' class='py-16 text-center text-gray-400 font-bold'>Belum ada rekaman omset masuk di rentang periode ini!</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
@media print {
    body { background: white !important; color: black !important; }
    nav, sidebar, .hide-on-print, form, button, footer { display: none !important; }
    .bg-white { border: none !important; box-shadow: none !important; }
    .shadow-sm { box-shadow: none !important; }
}
</style>

<script>
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
</script>