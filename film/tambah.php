<?php
require 'config.php';
require 'auth.php';
require_login();

$errors = [];
$data = [
    'judul'         => '',
    'bintang'       => '',
    'pemeran_utama' => '',
    'sutradara'     => '',
    'tahun'         => date('Y'),
    'genre'         => '',
    'sinopsis'      => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();

    $data['judul']         = trim($_POST['judul'] ?? '');
    $data['bintang']       = trim($_POST['bintang'] ?? '');
    $data['pemeran_utama'] = trim($_POST['pemeran_utama'] ?? '');
    $data['sutradara']     = trim($_POST['sutradara'] ?? '');
    $data['tahun']         = (int)($_POST['tahun'] ?? 0);
    $data['genre']         = trim($_POST['genre'] ?? '');
    $data['sinopsis']      = trim($_POST['sinopsis'] ?? '');

    if ($data['judul'] === '') $errors[] = 'Judul film wajib diisi.';
    if ($data['bintang'] === '') $errors[] = 'Bintang film wajib diisi.';
    if ($data['pemeran_utama'] === '') $errors[] = 'Pemeran utama wajib diisi.';
    if ($data['sutradara'] === '') $errors[] = 'Sutradara wajib diisi.';
    if ($data['tahun'] < 1900 || $data['tahun'] > 2100) $errors[] = 'Tahun terbit tidak valid.';

    if (empty($errors)) {
        $link = db_connect();
        $stmt = mysqli_prepare($link,
            'INSERT INTO film (judul, bintang, pemeran_utama, sutradara, tahun, genre, sinopsis)
             VALUES (?, ?, ?, ?, ?, ?, ?)'
        );
        mysqli_stmt_bind_param($stmt, 'ssssiss',
            $data['judul'],
            $data['bintang'],
            $data['pemeran_utama'],
            $data['sutradara'],
            $data['tahun'],
            $data['genre'],
            $data['sinopsis']
        );

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            mysqli_close($link);
            flash_set('success', 'Film "' . $data['judul'] . '" berhasil ditambahkan ke katalog!');
            header('Location: index.php');
            exit;
        } else {
            $errors[] = 'Gagal menyimpan data film: ' . mysqli_error($link);
            mysqli_stmt_close($stmt);
            mysqli_close($link);
        }
    }
}

page_header('Tambah Film', true);
?>
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="index.php" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-400 hover:text-white transition-colors mb-3">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Katalog
        </a>
        <h1 class="text-2xl font-bold text-white tracking-tight">Tambah Film Baru</h1>
        <p class="text-xs text-slate-400 mt-1">Lengkapi informasi judul, pemeran, sutradara, dan rincian film di bawah ini.</p>
    </div>

    <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl p-8 shadow-xl backdrop-blur-sm">
        <?php if (!empty($errors)): ?>
            <div class="mb-6 p-4 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-300 text-sm">
                <ul class="list-disc list-inside space-y-1">
                    <?php foreach ($errors as $err): ?>
                        <li><?= e($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post" action="tambah.php" class="space-y-5">
            <?= csrf_field() ?>

            <div>
                <label for="judul" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">
                    Judul Film <span class="text-rose-400">*</span>
                </label>
                <input type="text" id="judul" name="judul" value="<?= e($data['judul']) ?>" required autofocus
                       placeholder="Misal: Interstellar, The Raid..."
                       class="w-full px-4 py-2.5 bg-slate-900/80 border border-slate-700 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="bintang" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">
                        Bintang Film <span class="text-rose-400">*</span>
                    </label>
                    <input type="text" id="bintang" name="bintang" value="<?= e($data['bintang']) ?>" required
                           placeholder="Nama bintang film"
                           class="w-full px-4 py-2.5 bg-slate-900/80 border border-slate-700 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                </div>
                <div>
                    <label for="pemeran_utama" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">
                        Pemeran Utama <span class="text-rose-400">*</span>
                    </label>
                    <input type="text" id="pemeran_utama" name="pemeran_utama" value="<?= e($data['pemeran_utama']) ?>" required
                           placeholder="Nama pemeran utama"
                           class="w-full px-4 py-2.5 bg-slate-900/80 border border-slate-700 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="sutradara" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">
                        Sutradara <span class="text-rose-400">*</span>
                    </label>
                    <input type="text" id="sutradara" name="sutradara" value="<?= e($data['sutradara']) ?>" required
                           placeholder="Nama sutradara"
                           class="w-full px-4 py-2.5 bg-slate-900/80 border border-slate-700 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                </div>
                <div>
                    <label for="tahun" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">
                        Tahun Terbit <span class="text-rose-400">*</span>
                    </label>
                    <input type="number" id="tahun" name="tahun" value="<?= e((string)$data['tahun']) ?>" min="1900" max="2100" required
                           class="w-full px-4 py-2.5 bg-slate-900/80 border border-slate-700 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                </div>
            </div>

            <div>
                <label for="genre" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">
                    Genre Film
                </label>
                <input type="text" id="genre" name="genre" value="<?= e($data['genre']) ?>"
                       placeholder="Misal: Laga, Horor, Drama, Fiksi Ilmiah..."
                       class="w-full px-4 py-2.5 bg-slate-900/80 border border-slate-700 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
            </div>

            <div>
                <label for="sinopsis" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">
                    Sinopsis Singkat
                </label>
                <textarea id="sinopsis" name="sinopsis" rows="3"
                          placeholder="Ringkasan jalan cerita film..."
                          class="w-full px-4 py-2.5 bg-slate-900/80 border border-slate-700 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"><?= e($data['sinopsis']) ?></textarea>
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-700/60">
                <a href="index.php" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-400 hover:text-white hover:bg-slate-700/60 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-purple-600 hover:bg-purple-500 text-white font-semibold text-sm rounded-xl shadow-lg shadow-purple-600/20 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Simpan Film
                </button>
            </div>
        </form>
    </div>
</div>
<?php
page_footer();
