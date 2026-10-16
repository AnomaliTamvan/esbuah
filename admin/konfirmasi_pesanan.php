<?php
include '../includes/koneksi.php';

if (isset($_GET['action']) && $_GET['action'] == 'selesaikan_pesanan') {
    $id_pesanan = intval($_GET['id']);

    // 1. Tarik dulu data pesanan yang mau diselesaikan
    $ambil_data = mysqli_query($koneksi, "SELECT * FROM pesanan WHERE id_pesanan = $id_pesanan");
    
    if (mysqli_num_rows($ambil_data) > 0) {
        $pesanan = mysqli_fetch_assoc($ambil_data);
        
        $nomor_antrean  = mysqli_real_escape_string($koneksi, $pesanan['nomor_antrean']);
        $nama_pelanggan = mysqli_real_escape_string($koneksi, $pesanan['nama_pelanggan']);
        $detail_pesanan = mysqli_real_escape_string($koneksi, $pesanan['detail_pesanan']);
        $total_bayar    = intval($pesanan['total_bayar']);

        // 2. JALANKAN LOGIKA SAKTI: Salin dan kunci data ke tabel laporan_penjualan
        $query_backup = "INSERT INTO laporan_penjualan (id_pesanan, nomor_antrean, nama_pelanggan, detail_pesanan, total_bayar, tanggal_selesai) 
                         VALUES ($id_pesanan, '$nomor_antrean', '$nama_pelanggan', '$detail_pesanan', $total_bayar, NOW())";
        
        mysqli_query($koneksi, $query_backup);

        // 3. Update status di tabel pesanan utama menjadi 'selesai'
        $query_update = "UPDATE pesanan SET status = 'selesai' WHERE id_pesanan = $id_pesanan";
        
        if (mysqli_query($koneksi, $query_update)) {
            echo "<script>alert('Pesanan selesai!'); window.location.href='dashboard.php?page=pesanan_masuk';</script>";
            exit();
        }
    }
}
?>