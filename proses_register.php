<?php
session_start();
include 'includes/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 🌟 Ditambahkan trim() biar inputan spasi kosong di awal/akhir dibuang otomatis, Brek!
    $nama     = mysqli_real_escape_string($koneksi, trim($_POST['nama']));
    $username = mysqli_real_escape_string($koneksi, trim($_POST['username']));
    $email    = mysqli_real_escape_string($koneksi, trim($_POST['email']));
    
    $password_input = $_POST['password'];
    $confirm_password_input = $_POST['confirm_password'];
    $role     = 'pelanggan'; 
    $status   = 'active';    

    // 🌟 ANTISIPASI: Pastikan form tidak diisi spasi kosong doang
    if (empty($username) || empty($nama) || empty($email)) {
        header("Location: register.php?pesan=data_kosong");
        exit;
    }

    // 1. Cek apakah password & konfirmasi cocok
    if ($password_input !== $confirm_password_input) {
        header("Location: register.php?pesan=password");
        exit;
    }

    // 2. Cek apakah username sudah terpakai
    $cek_user = mysqli_query($koneksi, "SELECT * FROM user WHERE username = '$username'");
    
    if (!$cek_user) {
        die("Query Error!: " . mysqli_error($koneksi));
    }

    if (mysqli_num_rows($cek_user) > 0) {
        header("Location: register.php?pesan=username_kembar");
        exit;
    }

    // 3. Enkripsi password ke MD5
    $password_aman = md5($password_input);

    // 4. Query Insert data
    $query = "INSERT INTO user (nama, username, email, password, role, status, created_at) 
              VALUES ('$nama', '$username', '$email', '$password_aman', '$role', '$status', NOW())";
    
    if (mysqli_query($koneksi, $query)) {
        header("Location: register.php?status=sukses");
        exit;
    } else {
        echo "Waduh, error database: " . mysqli_error($koneksi);
    }
}
?>