<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../includes/koneksi.php';
// --- PROSES TOMBOL SELESAI OTOMATIS MASUK LAPORAN ---
if (isset($_GET['action']) && $_GET['action'] == 'selesaikan_pesanan') {
    $id_pesanan = intval($_GET['id']);

    // 1. Ambil data pesanan yang mau diselesaikan
    $ambil_order = mysqli_query($koneksi, "SELECT * FROM pesanan WHERE id_pesanan = $id_pesanan");
    
    if (mysqli_num_rows($ambil_order) > 0) {
        $data_order = mysqli_fetch_assoc($ambil_order);
        
        $nomor_antrean  = mysqli_real_escape_string($koneksi, $data_order['nomor_antrean']);
        $nama_pelanggan = mysqli_real_escape_string($koneksi, $data_order['nama_pelanggan']);
        $detail_pesanan = mysqli_real_escape_string($koneksi, $data_order['detail_pesanan']);
        $total_bayar    = intval($data_order['total_bayar']);

        // 2. Cek apakah sudah pernah masuk laporan biar gak duplikat
        $cek_laporan = mysqli_query($koneksi, "SELECT id_laporan FROM laporan_penjualan WHERE id_pesanan = $id_pesanan");
        
        if (mysqli_num_rows($cek_laporan) == 0) {
            // 3. Masukkan data secara otomatis ke tabel laporan_penjualan
            $query_laporan_auto = "INSERT INTO laporan_penjualan (id_pesanan, nomor_antrean, nama_pelanggan, detail_pesanan, total_bayar, tanggal_selesai) 
                                   VALUES ($id_pesanan, '$nomor_antrean', '$nama_pelanggan', '$detail_pesanan', $total_bayar, NOW())";
            mysqli_query($koneksi, $query_laporan_auto);
        }

        // 4. Update status pesanan utama menjadi 'selesai'
        $query_update_status = "UPDATE pesanan SET status = 'selesai' WHERE id_pesanan = $id_pesanan";
        
        if (mysqli_query($koneksi, $query_update_status)) {
            // Ambil info dari mana tombol diklik agar kembali ke halaman yang sama
            $redirect_page = isset($_GET['from']) ? $_GET['from'] : 'beranda';
            echo "<script>alert('Transaksi Sukses!'); window.location.href='dashboard.php?page=" . $redirect_page . "';</script>";
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - Es Buah Seger</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-orange-50/30 text-gray-800 antialiased font-sans min-h-screen flex m-0 p-0">

    <?php include '../includes/sidebar.php'; ?>

    <div class="flex-grow flex flex-col min-h-screen overflow-x-hidden">
        
        <?php include '../includes/header.php'; ?>
        
        <main class="p-8 flex-grow">
            <?php
            // Ambil parameter ?page= dari URL, kalau gak ada otomatis set ke 'beranda'
            $page = isset($_GET['page']) ? $_GET['page'] : 'beranda';

            if (file_exists($page . '.php')) {
                include $page . '.php';
            } else {
                // Mengarahkan ke file 404 lo kalau halamannya gak ketemu
                if (file_exists('404.php')) {
                    include '404.php';
                } else {
                    echo '<div class="text-center py-12"><h1 class="text-2xl font-black text-red-500">404 - Halaman Tidak Ditemukan</h1></div>';
                }
            }
            ?>
        </main>

        <?php include '../includes/footer.php'; ?>

    </div>

</body>
</html>