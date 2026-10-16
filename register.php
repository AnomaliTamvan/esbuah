<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Es Buah Seger!</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-orange-50 flex items-center justify-center min-h-screen p-4">

    <div class="bg-white p-8 rounded-3xl shadow-xl w-full max-w-md border-t-8 border-green-500">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-extrabold text-green-600">GABUNG <span class="text-orange-500">SEKARANG!</span></h1>
            <p class="text-gray-500 text-sm">Daftar sekarang buat mulai jajan es buah seger.</p>
        </div>

        <form action="proses_register.php" method="POST" class="space-y-5">

            <?php if (isset($_GET['pesan']) && $_GET['pesan'] == 'password') : ?>
                <div class="p-4 rounded-xl bg-red-100 border border-red-400 text-red-700 text-sm font-bold flex items-center gap-2">
                    ❌ Password dengan Konfirmasinya berbeda!
                </div>
            <?php endif; ?>

            <div>
                <label class="block text-sm font-semibold text-gray-700">Nama Lengkap</label>
                <input type="text" name="nama" placeholder="Nama Lengkap" required 
                    class="w-full mt-1 px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none transition">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700">Username</label>
                <input type="text" name="username" placeholder="Username" required 
                    class="w-full mt-1 px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none transition">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700">Email</label>
                <input type="email" name="email" placeholder="Email@contoh.com" required 
                    class="w-full mt-1 px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none transition">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700">Password</label>
                <div class="relative mt-1">
                    <input type="password" id="registerPassword" name="password" placeholder="••••••••" required 
                        class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none transition pr-12">
                    <button type="button" onclick="toggleRegPassword('registerPassword', 'regEyeIcon1')" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-green-500 transition">
                        <i id="regEyeIcon1" data-lucide="eye" class="w-5 h-5"></i>
                    </button>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700">Konfirmasi Password</label>
                <div class="relative mt-1">
                    <input type="password" id="confirmPassword" name="confirm_password" placeholder="••••••••" required 
                        class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none transition pr-12">
                    <button type="button" onclick="toggleRegPassword('confirmPassword', 'regEyeIcon2')" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-green-500 transition">
                        <i id="regEyeIcon2" data-lucide="eye" class="w-5 h-5"></i>
                    </button>
                </div>
            </div>

            <button type="submit" 
                class="w-full bg-green-500 hover:bg-green-600 text-white font-extrabold py-3 rounded-2xl shadow-lg shadow-green-200 tracking-widest mt-4 transition transform hover:-translate-y-1">
                DAFTAR
            </button>
        </form>

        <div class="mt-6 text-center">
            <p class="text-sm text-gray-500">Sudah punya akun? <a href="login.php" class="text-orange-500 font-bold hover:underline">Login!</a></p>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Render icon Lucide pertama kali
        lucide.createIcons();

        // Kita bikin fungsi yang bisa nerima parameter ID input & ID Icon biar kodenya efisien
        function toggleRegPassword(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const eyeIcon = document.getElementById(iconId);
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.setAttribute('data-lucide', 'eye-off');
            } else {
                passwordInput.type = 'password';
                eyeIcon.setAttribute('data-lucide', 'eye');
            }
            
            // Render ulang icon setelah berubah
            lucide.createIcons();
        }

        // 🌟 POP-UP SWEETALERT2 (+1 LEVEL) 🌟
        // Mengambil parameter dari URL browser
        const urlParams = new URLSearchParams(window.location.search);
        const status = urlParams.get('status');
        const pesan = urlParams.get('pesan');

        // A. JIKA BERHASIL DAFTAR
        if (status === 'sukses') {
            Swal.fire({
                icon: 'success',
                title: 'Sukses!',
                text: 'Akun berhasil ditambahkan. Login sekarang!',
                confirmButtonColor: '#22c55e', // Warna hijau seger
                confirmButtonText: 'Yuk Login!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Otomatis pindah ke halaman login pas tombol diklik
                    window.location.href = 'login.php';
                }
            });
        }

        // B. JIKA USERNAME KEMBAR / SUDAH ADA
        if (pesan === 'username_kembar') {
            Swal.fire({
                icon: 'warning',
                title: 'Username Sudah Digunakan!',
                confirmButtonColor: '#eab308'
            });
        }
    </script>

</body>
</html>