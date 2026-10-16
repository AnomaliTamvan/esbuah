<?php
// 🌟 Cek apakah kunci rahasia dari index.php ada. 
// Kalau gak ada, artinya user maksa ketik manual di URL!

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Es Buah Seger!</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-orange-50 flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 rounded-3xl shadow-xl w-full max-w-md border-t-8 border-orange-500">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-extrabold text-orange-600">ES BUAH <span class="text-green-500">SEGER!</span></h1>
            <p class="text-gray-500">Masuk untuk mulai segarkan harimu</p>
        </div>

        <form action="proses_login.php" method="POST" class="space-y-6">

            <?php if (isset($_GET['pesan']) && $_GET['pesan'] == 'gagal') : ?>
                <div class="p-4 rounded-xl bg-red-100 border border-red-400 text-red-700 text-sm font-bold flex items-center gap-2">
                    ❌ Username atau Password salah!
                </div>
            <?php endif; ?>

            <div>
                <label class="block text-sm font-semibold text-gray-700">Username</label>
                <input type="text" name="username" placeholder="Username" required 
                    class="w-full mt-1 px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 outline-none transition">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700">Password</label>
                <div class="relative mt-1">
                    <input type="password" id="passwordInput" name="password" placeholder="••••••••" required 
                        class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 outline-none transition pr-12">
                    
                    <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-orange-500 transition">
                        <i id="eyeIcon" data-lucide="eye" class="w-5 h-5"></i>
                    </button>
                </div>
            </div>

            <button type="submit" 
                class="w-full bg-orange-500 hover:bg-orange-600 text-white font-extrabold py-3 rounded-2xl shadow-lg shadow-orange-200 tracking-widest mt-4 transition transform hover:-translate-y-1">
                MASUK 
            </button>
        </form>

        <div class="mt-6 text-center">
            <p class="text-sm text-gray-500">Belum punya akun? <a href="register.php" class="text-green-600 font-bold hover:underline">Daftar dulu!</a></p>
        </div>
    </div>

    <script>
        // Inisialisasi awal agar icon Lucide muncul di layar
        lucide.createIcons();

        function togglePassword() {
            const passwordInput = document.getElementById('passwordInput');
            const eyeIcon = document.getElementById('eyeIcon');
            
            if (passwordInput.type === 'password') {
                // Ubah input jadi kelihatan teksnya
                passwordInput.type = 'text';
                // Ubah icon jadi mata dicoret (eye-off)
                eyeIcon.setAttribute('data-lucide', 'eye-off');
            } else {
                // Sembunyikan kembali password-nya
                passwordInput.type = 'password';
                // Balikin icon jadi mata terbuka (eye)
                eyeIcon.setAttribute('data-lucide', 'eye');
            }
            
            // Render ulang icon biar perubahannya langsung kelihatan, Brek!
            lucide.createIcons();
        }
    </script>

</body>
</html>