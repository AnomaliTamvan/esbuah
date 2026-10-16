<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'includes/koneksi.php';

// 🌟 1. LOGIKA UTAMA KERANJANG (UPDATE QUANTITY, HAPUS, CHECKOUT)

// A. Logika Tambah Ke Keranjang (Dari Halaman Menu)
if (isset($_POST['action']) && $_POST['action'] == 'add') {
    $id_menu = intval($_POST['id_menu']);
    $nama_menu = $_POST['nama_menu'];
    $harga = intval($_POST['harga_menu']);
    $qty = intval($_POST['qty_beli'] ?? 1);

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$id_menu])) {
        $_SESSION['cart'][$id_menu]['qty'] += $qty;
    } else {
        $_SESSION['cart'][$id_menu] = [
            'nama' => $nama_menu,
            'harga' => $harga,
            'qty' => $qty
        ];
    }

    // Jika dipicu dari tombol "Beli Langsung" (Membawa flag btn_checkout)
    if (isset($_POST['btn_checkout'])) {
        $_POST['btn_checkout'] = true; // Teruskan ke logika checkout di bawah
    } else {
        echo "<script>window.location.href='index.php?page=keranjang';</script>";
        exit();
    }
}

// 🔄 B. LOGIKA BARU: UPDATE QUANTITY LANGSUNG DARI DALAM KERANJANG
if (isset($_GET['update_qty']) && isset($_GET['id'])) {
    $id_ubah = intval($_GET['id']);
    $qty_baru = intval($_GET['update_qty']);

    if (isset($_SESSION['cart'][$id_ubah])) {
        if ($qty_baru < 1) {
            unset($_SESSION['cart'][$id_ubah]); // Kalau di-minus sampai 0, otomatis kehapus
        } else {
            $_SESSION['cart'][$id_ubah]['qty'] = $qty_baru; // Set qty baru
        }
    }
    echo "<script>window.location.href='index.php?page=keranjang';</script>";
    exit();
}

// C. Logika Hapus Item Tertentu
if (isset($_GET['remove'])) {
    $id_remove = intval($_GET['remove']);
    if (isset($_SESSION['cart'][$id_remove])) {
        unset($_SESSION['cart'][$id_remove]);
    }
    echo "<script>window.location.href='index.php?page=keranjang';</script>";
    exit();
}

// D. Logika Kosongkan Semua Isi Keranjang
if (isset($_GET['clear'])) {
    unset($_SESSION['cart']);
    echo "<script>window.location.href='index.php?page=keranjang';</script>";
    exit();
}

// E. Logika Checkout (Kirim Ke Dashboard Admin)
if (isset($_POST['btn_checkout']) || isset($_POST['btn_checkout_instan'])) {
    if (!isset($_SESSION['id'])) {
        echo "<script>alert('Login dulu sebelum checkout!'); window.location.href='login.php';</script>";
        exit();
    }

    if (empty($_SESSION['cart'])) {
        echo "<script>alert('Keranjang masih kosong!'); window.location.href='index.php?page=menu';</script>";
        exit();
    }

    $nama_pelanggan = mysqli_real_escape_string($koneksi, $_SESSION['username']);
    $total_bayar = 0;
    $arr_detail = [];

    foreach ($_SESSION['cart'] as $item) {
        $total_bayar += ($item['harga'] * $item['qty']);
        $arr_detail[] = $item['nama'] . " x" . $item['qty'];
    }

    $detail_pesanan = implode(", ", $arr_detail);
    $nomor_antrean = "A-" . str_pad(rand(1, 99), 2, "0", STR_PAD_LEFT);

    $query_checkout = "INSERT INTO pesanan (nomor_antrean, nama_pelanggan, detail_pesanan, total_bayar, status, created_at) 
                       VALUES ('$nomor_antrean', '$nama_pelanggan', '$detail_pesanan', '$total_bayar', 'menunggu', NOW())";

    if (mysqli_query($koneksi, $query_checkout)) {
        unset($_SESSION['cart']);
        echo "<script>alert('Pesanan berhasil dikirim ke dapur. Nomor Antrean : $nomor_antrean'); window.location.href='index.php?page=beranda';</script>";
        exit();
    } else {
        echo "<script>alert('Gagal memproses checkout: " . mysqli_error($koneksi) . "');</script>";
    }
}
?>

<div class="max-w-4xl mx-auto px-4 py-12 animate-fadeIn">
    
    <div class="border-b border-orange-100 pb-5 mb-8 flex items-center justify-between flex-wrap gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Keranjang <span class="text-orange-500">Belanja Lo</span></h1>
            <p class="text-sm text-gray-400 font-medium mt-0.5">Tinjau ulang cup es buah pilihan lo sebelum kita racik di dapur.</p>
        </div>
        <?php if (!empty($_SESSION['cart'])) : ?>
            <a href="index.php?page=keranjang&clear=1" onclick="return confirm('Kosongkan keranjang?')" class="text-xs font-bold text-red-500 hover:text-red-600 bg-red-50 px-3 py-2 rounded-xl border border-red-100 transition flex items-center gap-1">
                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Kosongkan Keranjang
            </a>
        <?php endif; ?>
    </div>

    <?php if (empty($_SESSION['cart'])) : ?>
        <div class="bg-white border border-orange-100 rounded-3xl p-12 text-center shadow-sm space-y-4">
            <div class="w-16 h-16 bg-orange-50 text-orange-500 rounded-2xl flex items-center justify-center mx-auto border border-orange-100 shadow-inner">
                <i data-lucide="shopping-cart" class="w-8 h-8"></i>
            </div>
            <div class="space-y-1">
                <h3 class="text-base font-black text-slate-800">Keranjang Masih Kosong Mlompong</h3>
                <p class="text-xs text-gray-400 max-w-sm mx-auto font-medium">Belum ada kesegaran yang lo pilih. Yuk, liat-liat menunya dulu dan pesan es buah favorit lo!</p>
            </div>
            <a href="index.php?page=menu" class="inline-flex items-center gap-2 bg-orange-500 hover:bg-orange-600 text-white font-extrabold text-xs px-6 py-3 rounded-xl shadow-md shadow-orange-500/10 transition mt-2">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Liat Daftar Menu Es Buah
            </a>
        </div>
    <?php else : ?>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            
            <div class="lg:col-span-2 bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-50 flex items-center gap-2">
                    <i data-lucide="shopping-bag" class="w-4 h-4 text-orange-500"></i>
                    <h2 class="text-sm font-black text-slate-800">Daftar Item yang Lo Pilih</h2>
                </div>
                <div class="divide-y divide-gray-50">
                    <?php 
                    $grand_total = 0;
                    foreach ($_SESSION['cart'] as $id_key => $item) : 
                        $subtotal = $item['harga'] * $item['qty'];
                        $grand_total += $subtotal;
                    ?>
                    <div class="p-6 flex items-center justify-between gap-4 flex-wrap sm:flex-nowrap hover:bg-slate-50/40 transition">
                        <div class="space-y-1">
                            <h4 class="text-sm font-black text-slate-800"><?php echo htmlspecialchars($item['nama']); ?></h4>
                            <p class="text-xs text-gray-400 font-bold">
                                Rp <?php echo number_format($item['harga'], 0, ',', '.'); ?> / Cup
                            </p>
                        </div>
                        
                        <div class="flex items-center gap-6">
                            <div class="flex items-center bg-slate-50 border border-slate-200 rounded-xl p-1 max-w-[120px]">
                                <a href="index.php?page=keranjang&id=<?php echo $id_key; ?>&update_qty=<?php echo $item['qty'] - 1; ?>" class="w-7 h-7 rounded-lg bg-white shadow-sm flex items-center justify-center text-slate-600 font-bold hover:bg-orange-50 hover:text-orange-500 transition text-sm">-</a>
                                
                                <span class="w-10 text-center font-black text-sm text-slate-800"><?php echo $item['qty']; ?></span>
                                
                                <a href="index.php?page=keranjang&id=<?php echo $id_key; ?>&update_qty=<?php echo $item['qty'] + 1; ?>" class="w-7 h-7 rounded-lg bg-white shadow-sm flex items-center justify-center text-slate-600 font-bold hover:bg-orange-50 hover:text-orange-500 transition text-sm">+</a>
                            </div>

                            <div class="flex items-center gap-4 min-w-[100px] justify-end">
                                <span class="text-sm font-black text-slate-800">
                                    Rp <?php echo number_format($subtotal, 0, ',', '.'); ?>
                                </span>
                                <a href="index.php?page=keranjang&remove=<?php echo $id_key; ?>" onclick="return confirm('Hapus item ini dari keranjang?')" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition" title="Hapus Item">
                                    <i data-lucide="x" class="w-4 h-4"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm space-y-6">
                <h3 class="text-sm font-black text-slate-800 border-b border-gray-50 pb-3">Ringkasan Pembayaran</h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between text-xs font-semibold text-gray-400">
                        <span>Total Harga Produk</span>
                        <span>Rp <?php echo number_format($grand_total, 0, ',', '.'); ?></span>
                    </div>
                    <div class="flex justify-between text-xs font-semibold text-gray-400">
                        <span>Biaya Pelayanan / Pajak</span>
                        <span class="text-emerald-500 font-bold">FREE / Rp 0</span>
                    </div>
                    <hr class="border-gray-100">
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-black text-slate-800">Total Pembayaran</span>
                        <span class="text-base font-black text-orange-500">Rp <?php echo number_format($grand_total, 0, ',', '.'); ?></span>
                    </div>
                </div>

                <form action="" method="POST">
                    <button type="submit" name="btn_checkout" onclick="return confirm('Sudah fix kesegarannya? Klik OK untuk kirim antrean ke dapur!')" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-extrabold text-xs py-3.5 rounded-xl shadow-md shadow-orange-500/10 transition flex items-center justify-center gap-2">
                        <i data-lucide="badge-check" class="w-4 h-4"></i> Checkout & Kirim Antrean
                    </button>
                </form>

                <p class="text-[10px] text-gray-400 text-center font-medium leading-relaxed">
                    *Pesanan lo langsung masuk antrean sistem kasir utama dapur secara otomatis. Pantau status pembuatan lo di halaman depan.
                </p>
            </div>

        </div>
    <?php endif; ?>
</div>

<script>
    // Supaya icon Lucide tetap ke-render sempurna
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
</script>