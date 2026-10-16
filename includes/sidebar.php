<aside class="w-64 bg-slate-900 text-slate-300 min-h-screen p-6 flex flex-col justify-between border-r border-slate-800 shadow-2xl">
    <div>
        <div class="mb-10 px-2 flex items-center gap-3">
            <div class="bg-gradient-to-tr from-orange-500 to-amber-400 text-white p-2.5 rounded-xl shadow-lg shadow-orange-500/20">
                <i data-lucide="citrus" class="w-6 h-6"></i>
            </div>
            <div>
                <h1 class="text-lg font-black tracking-wider text-white leading-none">ES BUAH</h1>
                <span class="text-xs font-bold text-emerald-400 tracking-widest uppercase">Central Admin</span>
            </div>
        </div>

        <nav class="space-y-1.5">
            <p class="text-[10px] text-slate-500 uppercase font-black tracking-widest mb-2 px-3">Menu Utama</p>
            
            <a href="dashboard.php?page=beranda" class="flex items-center gap-3.5 p-3 rounded-xl hover:bg-slate-800 hover:text-white text-slate-400 font-bold text-sm transition group">
                <i data-lucide="home" class="w-4 h-4 text-slate-500 group-hover:text-orange-400 transition"></i> 
                Dashboard
            </a>
            
            <a href="dashboard.php?page=antrean" class="flex items-center gap-3.5 p-3 rounded-xl hover:bg-slate-800 hover:text-white text-slate-400 font-bold text-sm transition group">
                <i data-lucide="clipboard-list" class="w-4 h-4 text-slate-500 group-hover:text-orange-400 transition"></i> 
                Antrean Pesanan
            </a>

            <?php if ($_SESSION['role'] == 'admin') : ?>
                <p class="text-[10px] text-slate-500 uppercase font-black tracking-widest mt-6 mb-2 px-3">Master Data</p>
                
                <a href="dashboard.php?page=kelola_menu" class="flex items-center gap-3.5 p-3 rounded-xl hover:bg-slate-800 hover:text-white text-slate-400 font-bold text-sm transition group">
                    <i data-lucide="coffee" class="w-4 h-4 text-slate-500 group-hover:text-emerald-400 transition"></i> 
                    Kelola Menu
                </a>
                
                <a href="dashboard.php?page=kelola_user" class="flex items-center gap-3.5 p-3 rounded-xl hover:bg-slate-800 hover:text-white text-slate-400 font-bold text-sm transition group">
                    <i data-lucide="users" class="w-4 h-4 text-slate-500 group-hover:text-emerald-400 transition"></i> 
                    Kelola User
                </a>
                
                <a href="dashboard.php?page=laporan" class="flex items-center gap-3.5 p-3 rounded-xl hover:bg-slate-800 hover:text-white text-slate-400 font-bold text-sm transition group">
                    <i data-lucide="bar-chart-3" class="w-4 h-4 text-slate-500 group-hover:text-emerald-400 transition"></i> 
                    Laporan Analitik
                </a>
            <?php endif; ?>
        </nav>
    </div>

    <div>
        <a href="../logout.php" class="flex items-center justify-center gap-2 p-3 w-full rounded-xl bg-red-500/10 text-red-400 hover:bg-red-600 hover:text-white font-bold text-sm transition shadow-lg shadow-red-950/20">
            <i data-lucide="log-out" class="w-4 h-4"></i> 
            Keluar Sistem
        </a>
    </div>
</aside>