<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Sesuaikan path include koneksi lo jika posisinya sejajar di luar
include 'includes/koneksi.php';

header('Content-Type: application/json');

// Validasi request wajib berupa POST dan action yang sesuai
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['action']) || $_POST['action'] !== 'beli_instan_ajax') {
    echo json_encode(['status' => 'error', 'pesan' => 'Akses ilegal, Brek!']);
    exit();
}

// Cek status login
if (!isset($_SESSION['username'])) {
    echo json_encode(['status' => 'error', 'pesan' => 'Lo belum login, silakan login dulu Brek!']);
    exit();
}

// Ambil data kiriman dari form menu.php
$nama_menu  = $_POST['nama_menu'] ?? '';
$harga_menu = intval($_POST['harga_menu'] ?? 0);
$qty_beli   = intval($_POST['qty_beli'] ?? 1);

if (empty($nama_menu) || $harga_menu <= 0) {
    echo json_encode(['status' => 'error', 'pesan' => 'Data menu tidak valid!']);
    exit();
}

$nama_pelanggan = mysqli_real_escape_string($koneksi, $_SESSION['username']);
$total_bayar    = $harga_menu * $qty_beli;
$detail_pesanan = $nama_menu . " x" . $qty_beli;

// Ambil nomor antrean urut otomatis berdasarkan hari ini
date_default_timezone_set('Asia/Jakarta');
$tgl_hari_ini = date('Y-m-d');
$query_cek_antrean = mysqli_query($koneksi, "SELECT MAX(nomor_antrean) as antrean_terakhir FROM pesanan WHERE DATE(created_at) = '$tgl_hari_ini'");
$data_antrean = mysqli_fetch_assoc($query_cek_antrean);

if ($data_antrean['antrean_terakhir'] !== null) {
    // Bersihkan karakter non-angka, lalu tambah 1
    $angka_terakhir = intval(preg_replace('/[^0-9]/', '', $data_antrean['antrean_terakhir']));
    $nomor_urut = $angka_terakhir + 1;
} else {
    $nomor_urut = 1;
}
$nomor_antrean = "A-" . str_pad($nomor_urut, 2, "0", STR_PAD_LEFT);

// Query insert langsung ke dashboard dapur
$query_checkout = "INSERT INTO pesanan (nomor_antrean, nama_pelanggan, detail_pesanan, total_bayar, status, created_at) 
                   VALUES ('$nomor_antrean', '$nama_pelanggan', '$detail_pesanan', '$total_bayar', 'menunggu', NOW())";

if (mysqli_query($koneksi, $query_checkout)) {
    // Response sukses murni JSON tanpa embel-embel HTML
    echo json_encode(['status' => 'sukses', 'nomor_antrean' => $nomor_antrean]);
    exit();
} else {
    echo json_encode(['status' => 'error', 'pesan' => 'Gagal query: ' . mysqli_error($koneksi)]);
    exit();
}