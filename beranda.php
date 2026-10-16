<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<header class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 pb-20">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
        <div class="space-y-6 text-center lg:text-left">
            <div class="inline-flex items-center gap-2 bg-green-100 text-green-800 text-xs font-bold px-4 py-1.5 rounded-full uppercase tracking-widest">
                <i data-lucide="sparkles" class="w-3.5 h-3.5"></i> 100% Buah Alami & Higienis
            </div>
            <h2 class="text-5xl lg:text-6xl font-black text-gray-900 leading-tight">
                Hilangkan Dahaga, <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-amber-500">Kembalikan Semangat!</span>
            </h2>
            <p class="text-gray-500 text-lg max-w-lg mx-auto lg:mx-0 leading-relaxed">
                Selamat datang di **Es Buah Seger!** Tempat terbaik buat nemuin kesegaran murni dari racikan aneka buah tropis pilihan yang disiram susu kental manis dan sirup rahasia homemade.
            </p>
            <div class="flex items-center justify-center lg:justify-start gap-4">
                <a href="index.php?page=menu" class="bg-orange-500 hover:bg-orange-600 text-white font-bold px-8 py-4 rounded-2xl shadow-xl shadow-orange-200 transition transform hover:-translate-y-1 flex items-center gap-2">
                    Gas Lihat Menu <i data-lucide="arrow-right" class="w-5 h-5"></i>
                </a>
            </div>
        </div>
        <div class="relative flex justify-center">
            <div class="absolute -inset-1 bg-gradient-to-r from-orange-500 to-green-500 rounded-3xl blur-2xl opacity-20 animate-pulse"></div>
            <img src="https://i.pinimg.com/736x/03/a1/9f/03a19f2612751cbf6db62295dc7d0fa8.jpg" alt="Es Buah Segar" class="relative rounded-3xl shadow-2xl w-full max-w-md object-cover h-[400px] border-4 border-white">
        </div>
    </div>
</header>

<section class="bg-white py-16 border-t border-orange-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
            <div class="p-6 space-y-3">
                <div class="bg-orange-100 text-orange-600 w-12 h-12 rounded-2xl flex items-center justify-center mx-auto"><i data-lucide="apple"></i></div>
                <h4 class="font-extrabold text-xl">Buah Selalu Fresh</h4>
                <p class="text-gray-500 text-sm">Dipilih langsung setiap pagi dari penyuplai buah terbaik demi menjaga kualitas rasa.</p>
            </div>
            <div class="p-6 space-y-3">
                <div class="bg-green-100 text-green-600 w-12 h-12 rounded-2xl flex items-center justify-center mx-auto"><i data-lucide="smile"></i></div>
                <h4 class="font-extrabold text-xl">Manisnya Pas</h4>
                <p class="text-gray-500 text-sm">Menggunakan sirup gula murni racikan sendiri, tanpa pemanis buatan yang bikin batuk.</p>
            </div>
            <div class="p-6 space-y-3">
                <div class="bg-amber-100 text-amber-600 w-12 h-12 rounded-2xl flex items-center justify-center mx-auto"><i data-lucide="zap"></i></div>
                <h4 class="font-extrabold text-xl">Pelayanan Kilat</h4>
                <p class="text-gray-500 text-sm">Gak pakai lama! Pesanan es buah lo bakal langsung diracik dalam hitungan menit.</p>
            </div>
        </div>
    </div>
</section>

<script>
    lucide.createIcons();
</script>