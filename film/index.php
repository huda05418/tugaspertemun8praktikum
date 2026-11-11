<?php
require 'config.php';
require 'auth.php';
require_login();

$kategori = $_GET['kategori'] ?? 'bintang';
$keyword  = trim($_GET['keyword'] ?? ($_GET['tahun'] ?? ''));

$labels = [
    'bintang'       => 'Bintang Film',
    'pemeran_utama' => 'Pemeran Utama',
    'sutradara'     => 'Sutradara',
    'tahun'         => 'Tahun Terbit',
];

$placeholders = [
    'bintang'       => 'Cari nama bintang film (misal: Reza Rahadian, Dian Sastro, Leonardo DiCaprio)...',
    'pemeran_utama' => 'Cari nama pemeran utama (misal: Nicholas Saputra, Iko Uwais, Christian Bale)...',
    'sutradara'     => 'Cari nama sutradara (misal: Joko Anwar, Christopher Nolan, Riri Riza)...',
    'tahun'         => 'Masukkan tahun rilis film (misal: 2010, 2019, 2022)...',
];

if (!array_key_exists($kategori, $labels)) {
    $kategori = 'bintang';
}

$link    = db_connect();
$results = [];

$params = [];
$types  = '';
$sql    = 'SELECT id, judul, bintang, pemeran_utama, sutradara, tahun, genre, sinopsis FROM film';

if ($kategori === 'tahun') {
    if ($keyword !== '') {
        $sql     .= ' WHERE tahun = ?';
        $params[] = (int) $keyword;
        $types   .= 'i';
    }
} else {
    if ($keyword !== '') {
        $sql     .= ' WHERE ' . $kategori . ' LIKE ?';
        $params[] = '%' . $keyword . '%';
        $types   .= 's';
    }
}
$sql .= ' ORDER BY tahun DESC, judul ASC';

if ($types !== '') {
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, $types, ...$params);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($res)) {
        $results[] = $row;
    }
    mysqli_stmt_close($stmt);
} else {
    if ($res = mysqli_query($link, $sql)) {
        while ($row = mysqli_fetch_assoc($res)) {
            $results[] = $row;
        }
        mysqli_free_result($res);
    }
}

$yearList = [];
$yRes = mysqli_query($link, 'SELECT DISTINCT tahun FROM film ORDER BY tahun DESC');
if ($yRes) {
    while ($yRow = mysqli_fetch_assoc($yRes)) {
        $yearList[] = (int)$yRow['tahun'];
    }
    mysqli_free_result($yRes);
}

mysqli_close($link);

page_header('Pencarian Film', true);
?>

<div class="text-center max-w-2xl mx-auto mb-8">
    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-purple-500/10 border border-purple-500/20 text-purple-400 text-xs font-semibold uppercase tracking-wider mb-3">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        Pencarian Basis Data
    </div>
    <h1 class="text-3xl font-extrabold text-white tracking-tight mb-2">Katalog &amp; Informasi Film</h1>
    <p class="text-slate-400 text-sm">Cari data film berdasarkan bintang film, pemeran utama, sutradara, atau tahun terbit.</p>
</div>

<div class="flex items-center justify-center gap-2 mb-6 flex-wrap">
    <a href="index.php?kategori=bintang<?= $keyword !== '' ? '&keyword=' . urlencode($keyword) : '' ?>" 
       class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold transition-all <?= $kategori === 'bintang' ? 'bg-purple-600 text-white shadow-lg shadow-purple-600/25 ring-2 ring-purple-500/50' : 'bg-slate-800/80 text-slate-300 hover:bg-slate-700/80 border border-slate-700/60' ?>">
        <svg class="w-4 h-4 <?= $kategori === 'bintang' ? 'text-white' : 'text-purple-400' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
        Bintang Film
    </a>

    <a href="index.php?kategori=pemeran_utama<?= $keyword !== '' ? '&keyword=' . urlencode($keyword) : '' ?>" 
       class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold transition-all <?= $kategori === 'pemeran_utama' ? 'bg-purple-600 text-white shadow-lg shadow-purple-600/25 ring-2 ring-purple-500/50' : 'bg-slate-800/80 text-slate-300 hover:bg-slate-700/80 border border-slate-700/60' ?>">
        <svg class="w-4 h-4 <?= $kategori === 'pemeran_utama' ? 'text-white' : 'text-purple-400' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
        Pemeran Utama
    </a>

    <a href="index.php?kategori=sutradara<?= $keyword !== '' ? '&keyword=' . urlencode($keyword) : '' ?>" 
       class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold transition-all <?= $kategori === 'sutradara' ? 'bg-purple-600 text-white shadow-lg shadow-purple-600/25 ring-2 ring-purple-500/50' : 'bg-slate-800/80 text-slate-300 hover:bg-slate-700/80 border border-slate-700/60' ?>">
        <svg class="w-4 h-4 <?= $kategori === 'sutradara' ? 'text-white' : 'text-purple-400' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
        Sutradara
    </a>

    <a href="index.php?kategori=tahun<?= $keyword !== '' ? '&keyword=' . urlencode($keyword) : '' ?>" 
       class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold transition-all <?= $kategori === 'tahun' ? 'bg-purple-600 text-white shadow-lg shadow-purple-600/25 ring-2 ring-purple-500/50' : 'bg-slate-800/80 text-slate-300 hover:bg-slate-700/80 border border-slate-700/60' ?>">
        <svg class="w-4 h-4 <?= $kategori === 'tahun' ? 'text-white' : 'text-purple-400' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
        Tahun Terbit
    </a>
</div>

<div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl p-6 shadow-xl backdrop-blur-sm mb-8">
    <form method="get" action="index.php" id="searchForm" class="space-y-4">
        <input type="hidden" name="kategori" id="kategoriInput" value="<?= e($kategori) ?>">

        <div class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="<?= $kategori === 'tahun' ? 'number' : 'text' ?>" 
                       name="keyword" id="keywordField"
                       value="<?= e($keyword) ?>" 
                       placeholder="<?= e($placeholders[$kategori]) ?>" autofocus
                       class="w-full pl-11 pr-4 py-3 bg-slate-900/90 border border-slate-700 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
            </div>

            <div class="flex gap-2">
                <button type="submit" class="px-6 py-3 bg-purple-600 hover:bg-purple-500 text-white font-semibold text-sm rounded-xl shadow-lg shadow-purple-600/20 transition-all flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    Cari Film
                </button>
                <?php if ($keyword !== ''): ?>
                    <a href="index.php?kategori=<?= e($kategori) ?>" class="px-4 py-3 bg-slate-700/70 hover:bg-slate-700 text-slate-300 font-semibold text-sm rounded-xl transition-colors flex items-center justify-center gap-1.5" title="Reset pencarian">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        Reset
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($kategori === 'tahun' && $yearList): ?>
            <div class="pt-3 border-t border-slate-700/50 flex items-center gap-2 flex-wrap">
                <span class="text-xs text-slate-400 font-medium">Filter Tahun Cepat:</span>
                <?php foreach ($yearList as $yr): ?>
                    <a href="index.php?kategori=tahun&keyword=<?= $yr ?>" 
                       class="px-2.5 py-1 rounded-lg text-xs font-semibold transition-colors <?= ($keyword == $yr) ? 'bg-purple-500 text-white' : 'bg-slate-700/60 text-slate-300 hover:bg-slate-700' ?>">
                        <?= $yr ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </form>
</div>

<div class="flex items-center justify-between mb-6">
    <div class="flex items-center gap-2">
        <h2 class="text-lg font-bold text-white">Hasil Pencarian: <?= e($labels[$kategori]) ?></h2>
        <?php if ($keyword !== ''): ?>
            <span class="text-xs px-2.5 py-1 rounded-md bg-purple-500/10 border border-purple-500/20 text-purple-300">
                "<?= e($keyword) ?>"
            </span>
        <?php endif; ?>
    </div>
    <span class="text-xs font-semibold text-slate-400 bg-slate-800 px-3 py-1 rounded-full border border-slate-700">
        <?= count($results) ?> Film Ditemukan
    </span>
</div>

<?php if (empty($results)): ?>
    <div class="bg-slate-800/40 border border-slate-700/60 rounded-2xl p-12 text-center text-slate-400">
        <div class="w-16 h-16 rounded-2xl bg-slate-800 text-slate-500 mx-auto flex items-center justify-center mb-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"></path></svg>
        </div>
        <h3 class="text-base font-semibold text-white mb-1">Film Tidak Ditemukan</h3>
        <p class="text-xs text-slate-400 max-w-sm mx-auto">Tidak ada rekaman film yang sesuai dengan kriteria <?= e($labels[$kategori]) ?>: "<?= e($keyword) ?>".</p>
        <a href="index.php?kategori=<?= e($kategori) ?>" class="inline-flex items-center gap-1.5 mt-4 px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white text-xs font-semibold rounded-xl transition-colors">
            Tampilkan Semua Film
        </a>
    </div>
<?php else: ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($results as $f): ?>
            <article class="bg-slate-800/60 border border-slate-700/60 rounded-2xl overflow-hidden hover:border-purple-500/50 hover:shadow-xl hover:shadow-purple-500/10 transition-all flex flex-col justify-between group">
                <div>
                    <div class="h-32 bg-gradient-to-br from-purple-900/60 via-slate-800 to-indigo-950 flex items-center justify-center relative border-b border-slate-700/50">
                        <div class="w-14 h-14 rounded-2xl bg-purple-500/10 border border-purple-500/20 text-purple-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"></path>
                            </svg>
                        </div>
                        <div class="absolute top-3 right-3 flex gap-1.5">
                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-slate-900/80 backdrop-blur-sm text-purple-300 border border-purple-500/30">
                                <?= e((string)$f['tahun']) ?>
                            </span>
                        </div>
                    </div>

                    <div class="p-5">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-[11px] font-medium px-2 py-0.5 rounded bg-slate-700/50 text-slate-300 border border-slate-600/40">
                                <?= e($f['genre'] ?: 'Umum') ?>
                            </span>
                        </div>

                        <h3 class="text-lg font-bold text-white group-hover:text-purple-300 transition-colors mb-2 leading-snug">
                            <?= e($f['judul']) ?>
                        </h3>

                        <p class="text-xs text-slate-400 leading-relaxed line-clamp-3 mb-4">
                            <?= e($f['sinopsis']) ?>
                        </p>
                    </div>
                </div>

                <div class="px-5 py-4 bg-slate-900/40 border-t border-slate-700/50 text-xs space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500 flex items-center gap-1.5 font-medium">
                            <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                            Bintang
                        </span>
                        <span class="text-slate-200 font-semibold text-right"><?= e($f['bintang']) ?></span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500 flex items-center gap-1.5 font-medium">
                            <svg class="w-3.5 h-3.5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            Pemeran
                        </span>
                        <span class="text-slate-200 font-semibold text-right"><?= e($f['pemeran_utama']) ?></span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500 flex items-center gap-1.5 font-medium">
                            <svg class="w-3.5 h-3.5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                            Sutradara
                        </span>
                        <span class="text-slate-200 font-semibold text-right"><?= e($f['sutradara']) ?></span>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php
page_footer();
