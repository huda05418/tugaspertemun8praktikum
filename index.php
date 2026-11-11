<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Aplikasi &mdash; Pemrograman Web PHP &amp; MySQL</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen flex flex-col justify-between selection:bg-indigo-500 selection:text-white">
    <div class="fixed inset-0 overflow-hidden pointer-events-none -z-10">
        <div class="absolute -top-40 -left-40 w-96 h-96 bg-indigo-600/20 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 -right-40 w-96 h-96 bg-purple-600/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 left-1/3 w-96 h-96 bg-blue-600/20 rounded-full blur-3xl"></div>
    </div>

    <header class="pt-16 pb-8 px-6 text-center max-w-3xl mx-auto">
        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 text-xs font-semibold tracking-wider uppercase mb-5 backdrop-blur-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
            </svg>
            PHP 8 &amp; MySQL Project
        </div>
        <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight text-white mb-4">
            Katalog Sistem &amp; Aplikasi Web
        </h1>
        <p class="text-slate-400 text-base sm:text-lg leading-relaxed max-w-2xl mx-auto">
            Pilih aplikasi di bawah ini untuk melihat modul pengelolaan kontak dan pencarian basis data interaktif.
        </p>
    </header>

    <main class="max-w-5xl mx-auto px-6 py-6 w-full">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            
            <a href="addressbook/" class="group relative bg-slate-800/60 hover:bg-slate-800/90 border border-slate-700/60 hover:border-indigo-500/60 rounded-2xl p-8 transition-all duration-300 hover:shadow-2xl hover:shadow-indigo-500/10 flex flex-col justify-between backdrop-blur-sm transform hover:-translate-y-1">
                <div>
                    <div class="w-14 h-14 rounded-xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center mb-6 group-hover:scale-110 group-hover:bg-indigo-500 group-hover:text-white transition-all duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>

                    <div class="flex items-center justify-between mb-3">
                        <h2 class="text-2xl font-bold text-white group-hover:text-indigo-300 transition-colors">
                            Buku Alamat Kontak
                        </h2>
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-md bg-slate-700/60 text-slate-300 border border-slate-600/40">
                            CRUD Data
                        </span>
                    </div>

                    <p class="text-slate-400 text-sm leading-relaxed mb-6">
                        Sistem manajemen kontak lengkap dengan fitur penambahan, pencarian multispesifik, perubahan, serta penghapusan entri buku alamat.
                    </p>
                </div>

                <div class="pt-6 border-t border-slate-700/60 flex items-center justify-between text-indigo-400 font-semibold text-sm group-hover:text-indigo-300">
                    <span>Buka Address Book</span>
                    <svg class="w-5 h-5 transform group-hover:translate-x-1.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </div>
            </a>

            <a href="film/" class="group relative bg-slate-800/60 hover:bg-slate-800/90 border border-slate-700/60 hover:border-purple-500/60 rounded-2xl p-8 transition-all duration-300 hover:shadow-2xl hover:shadow-purple-500/10 flex flex-col justify-between backdrop-blur-sm transform hover:-translate-y-1">
                <div>
                    <div class="w-14 h-14 rounded-xl bg-purple-500/10 border border-purple-500/20 text-purple-400 flex items-center justify-center mb-6 group-hover:scale-110 group-hover:bg-purple-500 group-hover:text-white transition-all duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"></path>
                        </svg>
                    </div>

                    <div class="flex items-center justify-between mb-3">
                        <h2 class="text-2xl font-bold text-white group-hover:text-purple-300 transition-colors">
                            Katalog &amp; Pencarian Film
                        </h2>
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-md bg-purple-500/10 text-purple-300 border border-purple-500/30">
                            Auth + Filter
                        </span>
                    </div>

                    <p class="text-slate-400 text-sm leading-relaxed mb-6">
                        Pencarian data film berdasarkan bintang film, pemeran utama, sutradara, dan tahun terbit, terproteksi sistem autentikasi login.
                    </p>
                </div>

                <div class="pt-6 border-t border-slate-700/60 flex items-center justify-between text-purple-400 font-semibold text-sm group-hover:text-purple-300">
                    <span>Masuk ke Katalog Film</span>
                    <svg class="w-5 h-5 transform group-hover:translate-x-1.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </div>
            </a>

        </div>
    </main>

    <footer class="py-8 text-center text-xs text-slate-500 border-t border-slate-800/80 mt-12">
        <p>Aplikasi Web Terintegrasi &middot; Menggunakan PHP 8 &amp; MySQL &middot; Tailwind CSS</p>
    </footer>
</body>
</html>
