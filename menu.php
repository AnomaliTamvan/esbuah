<?php
include 'includes/koneksi.php';

// CONFIGURATION PAGINATION 10 DATA
$batas_data = 10; 
$halaman    = isset($_GET['halaman_menu']) ? (int)$_GET['halaman_menu'] : 1;
if ($halaman < 1) { $halaman = 1; }
$mulai_dari = ($halaman > 1) ? ($halaman * $batas_data) - $batas_data : 0;

// Query hitung total semua menu
$query_total = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM menu");
$data_total  = mysqli_fetch_assoc($query_total);
$total_menu  = $data_total['total'];
$total_halaman = ceil($total_menu / $batas_data);

// Query tarik data dari tabel menu lo
$query_menu = mysqli_query($koneksi, "SELECT id_menu, nama_menu, harga_menu, gambar FROM menu LIMIT $mulai_dari, $batas_data");
?>

<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 pb-24">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-12 border-b border-gray-100 pb-6">
        <div>
            <h3 class="text-3xl font-black text-gray-900">Daftar Menu <span class="text-orange-500">Es Buah</span></h3>
            <p class="text-gray-500 text-sm mt-1">Silakan pilih varian menu kesegaran yang anda inginkan!</p>
        </div>
        <span class="self-start md:self-center text-sm font-bold text-orange-600 bg-orange-100 px-4 py-2 rounded-2xl">
            Tersedia: <b><?php echo $total_menu; ?></b> Varian Menu
        </span>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php 
        if (mysqli_num_rows($query_menu) > 0) {
            while ($menu = mysqli_fetch_assoc($query_menu)) : 
        ?>
            <div class="bg-white rounded-3xl border border-gray-100 p-4 shadow-sm flex flex-col justify-between group">
                
                <div class="rounded-2xl h-48 mb-4 overflow-hidden relative">
                    <?php if (!empty($menu['gambar'])) : ?>
                        <img src="uploads/<?php echo $menu['gambar']; ?>" class="w-full h-full object-cover transition duration-300 group-hover:scale-110">
                    <?php else : ?>
                        <div class="w-full h-full bg-gray-100 flex flex-col items-center justify-center text-gray-400 gap-2 border border-dashed border-gray-200 rounded-2xl">
                            <i data-lucide="image-off" class="w-8 h-8 text-gray-300"></i>
                            <span class="text-xs font-bold tracking-wide uppercase">Tidak Ada Gambar</span>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="space-y-3">
                    <div>
                        <h4 class="text-xl font-extrabold text-gray-900 truncate"><?php echo htmlspecialchars($menu['nama_menu']); ?></h4>
                        <p class="text-gray-400 text-xs mt-0.5">Kode Menu: MB-<?php echo $menu['id_menu']; ?></p>
                    </div>
                    
                    <div class="flex items-center justify-between pt-1 border-t border-gray-50">
                        <span class="text-orange-600 font-black text-xl">Rp <?php echo number_format($menu['harga_menu'], 0, ',', '.'); ?></span>
                    </div>

                    <div class="grid grid-cols-2 gap-2 pt-1">
    
                        <form class="form-keranjang-ajax">
                            <input type="hidden" name="action" value="add">
                            <input type="hidden" name="id_menu" value="<?php echo $menu['id_menu']; ?>">
                            <input type="hidden" name="nama_menu" value="<?php echo htmlspecialchars($menu['nama_menu']); ?>">
                            <input type="hidden" name="harga_menu" value="<?php echo $menu['harga_menu']; ?>">
                            <input type="hidden" name="qty_beli" value="1">
                            
                            <button type="button" onclick="tambahKeKeranjangAjax(this)" class="w-full bg-amber-50 hover:bg-amber-100 border border-amber-200 text-amber-700 font-bold py-2.5 px-3 rounded-xl transition flex items-center justify-center gap-1.5 text-xs">
                                <i data-lucide="shopping-basket" class="w-4 h-4"></i> + Keranjang
                            </button>
                        </form>

                        <form class="form-beli-langsung-ajax">
    <input type="hidden" name="action" value="beli_instan_ajax">
    <input type="hidden" name="id_menu" value="<?php echo $menu['id_menu']; ?>">
    <input type="hidden" name="nama_menu" value="<?php echo htmlspecialchars($menu['nama_menu']); ?>">
    <input type="hidden" name="harga_menu" value="<?php echo $menu['harga_menu']; ?>">
    <input type="hidden" name="qty_beli" value="1">
    
    <button type="button" onclick="beliLangsungTanpaPindah(this)" class="w-full bg-orange-500 hover:bg-emerald-500 text-white font-extrabold py-2.5 px-3 rounded-xl transition flex items-center justify-center gap-1.5 text-xs shadow-sm">
        <i data-lucide="zap" class="w-4 h-4"></i> Beli Sekarang
    </button>
</form>

                    </div>
                </div>
            </div>
        <?php 
            endwhile; 
        } else { 
        ?>
            <div class="col-span-full text-center py-16 text-gray-400 font-bold">
                <i data-lucide="frown" class="w-12 h-12 mx-auto mb-3 text-gray-300"></i>
                Belum ada menu di database!
            </div>
        <?php } ?>
    </div>

    <?php if ($total_halaman > 1) : ?>
        <div class="flex items-center justify-center gap-4 mt-16">
            <?php if ($halaman > 1) : ?>
                <a href="index.php?page=menu&halaman_menu=<?php echo $halaman - 1; ?>" 
                    class="bg-white border border-gray-200 text-gray-600 hover:text-orange-500 w-12 h-12 rounded-2xl flex items-center justify-center shadow-sm transition font-black text-lg">
                    &lt;
                </a>
            <?php else : ?>
                <span class="bg-gray-100 text-gray-300 w-12 h-12 rounded-2xl flex items-center justify-center cursor-not-allowed font-black text-lg">
                    &lt;
                </span>
            <?php endif; ?>

            <span class="text-sm font-bold text-gray-500">
                Halaman <?php echo $halaman; ?> dari <?php echo $total_halaman; ?>
            </span>

            <?php if ($halaman < $total_halaman) : ?>
                <a href="index.php?page=menu&halaman_menu=<?php echo $halaman + 1; ?>" 
                    class="bg-white border border-gray-200 text-gray-600 hover:text-orange-500 w-12 h-12 rounded-2xl flex items-center justify-center shadow-sm transition font-black text-lg">
                    &gt;
                </a>
            <?php else : ?>
                <span class="bg-gray-100 text-gray-300 w-12 h-12 rounded-2xl flex items-center justify-center cursor-not-allowed font-black text-lg">
                    &gt;
                </span>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</section>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Inisialisasi ikon Lucide
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }

    // Fungsi Validasi Status Login (Cukup Satu Saja)
    function cekLoginSebelumSubmit(event, namaMenu) {
        const isLogin = <?php echo isset($_SESSION['username']) ? 'true' : 'false'; ?>;
        
        if (!isLogin) {
            event.preventDefault();
            
            Swal.fire({
                icon: 'warning',
                title: 'Login Dulu Terlebih Dahulu!',
                // text: 'Lo harus masuk ke akun lo dulu biar bisa memesan ' + namaMenu,
                showCancelButton: true,
                confirmButtonColor: '#f97316', 
                cancelButtonColor: '#6b7280',  
                confirmButtonText: 'Login!',
                cancelButtonText: 'Nanti',
                reverseButtons: true 
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'login.php';
                }
            });
            return false;
        }
        return true;
    }

    // 🌟 FUNGSI: Tambah Keranjang via AJAX
    function tambahKeKeranjangAjax(button) {
        const isLogin = <?php echo isset($_SESSION['username']) ? 'true' : 'false'; ?>;
        const form = button.closest('form');
        const namaMenu = form.querySelector('input[name="nama_menu"]').value;

        if (!isLogin) {
            Swal.fire({
                icon: 'warning',
                title: 'Login Dulu Terlebih Dahulu!',
                showCancelButton: true,
                confirmButtonColor: '#f97316', 
                cancelButtonColor: '#6b7280',  
                confirmButtonText: 'Login!',
                cancelButtonText: 'Nanti',
                reverseButtons: true 
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'login.php'; 
                }
            });
            return;
        }

        const formData = new FormData(form);

        fetch('index.php?page=keranjang', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            Toast.fire({
                icon: 'success',
                title: namaMenu + ' berhasil ditambah ke keranjang!'
            });
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Gagal masukin data ke keranjang, error');
        });
    }

    function beliLangsungTanpaPindah(button) {
    const isLogin = <?php echo isset($_SESSION['username']) ? 'true' : 'false'; ?>;
    const form = button.closest('form');
    const namaMenu = form.querySelector('input[name="nama_menu"]').value;

    if (!isLogin) {
        if (typeof cekLoginSebelumSubmit === 'function') {
            cekLoginSebelumSubmit(new Event('click'), namaMenu);
        } else {
            Swal.fire('Login Dulu', 'Lo harus login dulu sebelum memesan es buah, Brek!', 'warning');
        }
        return;
    }

    const formData = new FormData(form);

    // 🌟 SEKARANG TEMBAK LANGSUNG KE FILE BERSIH TANPA INTERUPSI HTML TEMPLATE
    fetch('proses_cepat.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('HTTP error, status = ' + response.status);
        }
        return response.json(); // Karena jalurnya murni, ini pasti aman terbaca JSON
    })
    .then(data => {
        if (data.status === 'sukses') {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil Dipesan!',
                text: namaMenu + ' langsung dikirim ke dapur! Nomor antrean lo: ' + data.nomor_antrean,
                confirmButtonColor: '#22c55e'
            });
        } else {
            Swal.fire('Gagal!', data.pesan, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire('Waduh', 'Gagal memproses pesanan langsung, cek kembali jaringan lo, Brek!', 'error');
    });
}
</script>