<?php
session_start(); 
include 'includes/koneksi.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = md5($_POST['password']); 

    // Mencari user berdasarkan username dan password
    $query  = "SELECT * FROM user WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($koneksi, $query);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        
        // Mencocokkan password MD5
        if ($password === $row['password']) {
            
            // Set session data user (pastiin nama kolom id di DB lo sesuai, misal: id_user)
            $_SESSION['id']       = $row['id_user']; 
            $_SESSION['username'] = $row['username'];
            $_SESSION['role']     = $row['role'];

            // 🌟 GERBANG UTAMA: Menentukan arah halaman berdasarkan role
            if ($row['role'] == 'admin' || $row['role'] == 'petugas') {
                // Masuk ke folder views karena dashboard.php ada di sana
                header("Location: admin/dashboard.php"); 
            } else {
                // Pelanggan lari ke index.php di luar
                header("Location: index.php");
            }
            exit();
        }
    }
    
    // 🌟 KOREKSI UTAMA: Kalau gagal, balik ke login.php yang ada di LUAR folder views
    header("Location: login.php?pesan=gagal");
    exit();
}
?>