<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Hitung jumlah total item di keranjang belanja secara real-time
$jumlah_keranjang = 0;
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $jumlah_keranjang += $item['qty'];
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Es Buah! - Segar & Nikmat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-orange-50/50 text-gray-800 antialiased font-sans flex flex-col min-h-screen">

    <nav class="bg-white/80 backdrop-blur-md sticky top-0 z-50 shadow-sm border-b border-orange-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                
                <div class="flex items-center gap-2">
                    <div class="bg-orange-500 text-white p-2 rounded-2xl shadow-md">
                        <i data-lucide="citrus" class="w-6 h-6"></i>
                    </div>
                    <a href="index.php?page=beranda" class="text-2xl font-black tracking-wider text-orange-600">ES BUAH <span class="text-green-500">SEGER!</span></a>
                </div>
                
                <div class="hidden md:flex items-center gap-6 font-bold text-sm">
                    <a href="index.php?page=beranda" class="text-gray-600 hover:text-orange-500 transition">Beranda</a>
                    <a href="index.php?page=menu" class="text-gray-600 hover:text-orange-500 transition">Menu</a>
                    <a href="index.php?page=tentang" class="text-gray-600 hover:text-orange-500 transition">Tentang</a>
                    <a href="index.php?page=kontak" class="text-gray-600 hover:text-orange-500 transition">Kontak Toko</a>
                </div>
                
                <div class="flex items-center gap-4">
                    
                    <a href="index.php?page=keranjang" class="relative p-2.5 bg-slate-50 hover:bg-orange-50 text-slate-600 hover:text-orange-500 rounded-xl transition group mr-2" title="Lihat Keranjang Belanja">
                        <i data-lucide="shopping-basket" class="w-5 h-5 transition group-hover:scale-110"></i>
                        
                        <?php if ($jumlah_keranjang > 0) : ?>
                            <span class="absolute -top-1.5 -right-1.5 bg-red-500 text-white text-[10px] font-black w-5 h-5 rounded-full flex items-center justify-center border-2 border-white shadow-sm animate-bounce">
                                <?php echo $jumlah_keranjang; ?>
                            </span>
                        <?php endif; ?>
                    </a>

                    <?php if (isset($_SESSION['username'])) : ?>
                        <span class="text-sm font-medium text-gray-600 bg-orange-100 px-4 py-2 rounded-full border border-orange-200">
                            Halo, <b><?php echo htmlspecialchars($_SESSION['username']); ?></b>
                        </span>
                        
                        <?php if (isset($_SESSION['role']) && ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'petugas')) : ?>
                            <a href="admin/dashboard.php" class="bg-green-500 hover:bg-green-600 text-white text-sm font-bold px-4 py-2 rounded-xl transition shadow-md">Dashboard</a>
                        <?php endif; ?>
                        
                        <a href="logout.php" onclick="return confirm('Yakin logout?')" class="text-gray-400 hover:text-red-500 transition p-1" title="Keluar">
                            <i data-lucide="log-out" class="w-5 h-5"></i>
                        </a>
                    <?php else : ?>
                        <a href="login.php" class="text-orange-600 hover:text-orange-700 font-bold text-sm transition">Masuk</a>
                        <a href="register.php" class="bg-orange-500 hover:bg-orange-600 text-white text-sm font-extrabold px-5 py-2.5 rounded-2xl shadow-md transition">Daftar</a>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </nav>

    <main class="flex-grow">
        <?php
        $page = isset($_GET['page']) ? $_GET['page'] : 'beranda';

        if (file_exists($page . '.php')) {
            include $page . '.php';
        } else {
            echo '
            <div class="text-center py-24 space-y-4">
                <h1 class="text-6xl font-black text-red-500">404</h1>
                <p class="text-gray-500 font-bold">Ups, Halaman yang dicari tidak tersedia!</p>
                <a href="index.php?page=beranda" class="inline-block bg-orange-500 text-white font-bold px-6 py-2 rounded-xl">Balik ke Beranda</a>
            </div>';
        }
        ?>
    </main>

    <footer class="bg-slate-900 text-slate-400 pt-16 pb-8 border-t border-slate-800 mt-auto shadow-2xl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 pb-12 border-b border-slate-800">
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="bg-gradient-to-tr from-orange-500 to-amber-400 text-white p-2 rounded-xl shadow-lg shadow-orange-500/10">
                            <i data-lucide="citrus" class="w-5 h-5"></i>
                        </div>
                        <span class="text-xl font-black tracking-wider text-white">ES BUAH <span class="text-emerald-400">SEGER!</span></span>
                    </div>
                    <p class="text-sm text-slate-400 leading-relaxed max-w-sm">
                        Menyajikan kesegaran murni dari racikan aneka buah tropis pilihan setiap hari. Higienis, manis alami tanpa pemanis buatan.
                    </p>
                </div>

                <div class="space-y-4">
                    <h4 class="text-white font-bold text-sm uppercase tracking-widest border-l-2 border-orange-500 pl-3">Navigasi</h4>
                    <ul class="space-y-2.5 text-sm font-semibold">
                        <li>
                            <a href="index.php?page=beranda" class="hover:text-orange-400 transition flex items-center gap-2 group">
                                <i data-lucide="chevron-right" class="w-4 h-4 text-slate-600 group-hover:text-orange-400 transition"></i> Beranda
                            </a>
                        </li>
                        <li>
                            <a href="index.php?page=menu" class="hover:text-orange-400 transition flex items-center gap-2 group">
                                <i data-lucide="chevron-right" class="w-4 h-4 text-slate-600 group-hover:text-orange-400 transition"></i> Daftar Menu
                            </a>
                        </li>
                        <li>
                            <a href="index.php?page=kontak" class="hover:text-orange-400 transition flex items-center gap-2 group">
                                <i data-lucide="chevron-right" class="w-4 h-4 text-slate-600 group-hover:text-orange-400 transition"></i> Kontak Toko
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="space-y-4">
                    <h4 class="text-white font-bold text-sm uppercase tracking-widest border-l-2 border-emerald-500 pl-3">Jam Operasional</h4>
                    <div class="space-y-3 text-sm">
                        <div class="flex items-start gap-3">
                            <i data-lucide="clock" class="w-4 h-4 text-slate-500 mt-0.5"></i>
                            <div>
                                <p class="font-bold text-slate-300">Setiap Hari</p>
                                <p class="text-xs text-slate-500">09:00 WIB - 17:00 WIT</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <i data-lucide="map-pin" class="w-4 h-4 text-slate-500 mt-0.5"></i>
                            <p class="text-slate-400">Jl. Segar Selalu No. 45, Area Central Kuliner.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs font-semibold text-slate-500">
                <p>&copy; 2026 Es Buah Seger All Rights Reserved.</p>
                <div class="flex items-center gap-1.5 bg-slate-800/50 border border-slate-800 px-3 py-1.5 rounded-xl">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="text-slate-400">Sistem Kasir v2.0 (Aktif)</span>
                </div>
            </div>
        </div>
    </footer>

    <script>
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    </script>
</body>
</html>