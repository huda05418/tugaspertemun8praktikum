<?php
require 'global.php';
require 'common.php';

$link = db_connect();

$recent = [];
if ($res = mysqli_query($link, 'SELECT Rowid, Name, Email, City, Telephone FROM ' . DB_TABLE . ' ORDER BY Rowid DESC LIMIT 5')) {
    while ($row = mysqli_fetch_assoc($res)) {
        $recent[] = $row;
    }
    mysqli_free_result($res);
}

$count = 0;
if ($res = mysqli_query($link, 'SELECT COUNT(*) AS total FROM ' . DB_TABLE)) {
    $count = (int) mysqli_fetch_assoc($res)['total'];
    mysqli_free_result($res);
}
mysqli_close($link);

page_header('Beranda', 'home');
?>
<div class="hero">
    <h1 class="hero-title">Kelola kontak Anda dengan rapi</h1>
    <p class="hero-sub">Aplikasi buku alamat sederhana — tambahkan, cari, ubah, dan hapus entri dengan mudah.</p>
</div>

<div class="action-grid">
    <a href="add.php" class="action-card">
        <span class="icon">&#10133;</span>
        <span>
            <h3>Tambah Entri Baru</h3>
            <p>Tambahkan kontak baru ke dalam buku alamat Anda.</p>
        </span>
    </a>
    <a href="search.php" class="action-card">
        <span class="icon">&#128269;</span>
        <span>
            <h3>Cari Buku Alamat</h3>
            <p>Cari entri berdasarkan nama, e-mail, kota, dan lainnya.</p>
        </span>
    </a>
</div>

<div class="card">
    <div class="card-head">
        <h3>Entri Terbaru</h3>
        <span class="chip"><?= $count ?> entri tersimpan</span>
    </div>
    <?php if (!$recent): ?>
        <div class="empty">
            <div class="empty-icon">&#128220;</div>
            <h3>Belum ada data</h3>
            <p>Mulai dengan menambahkan entri pertama Anda.</p>
            <a href="add.php" class="btn btn-primary">Tambah Entri</a>
        </div>
    <?php else: ?>
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr><th>Nama</th><th>E-mail</th><th>Kota</th><th>Telepon</th><th></th></tr>
                </thead>
                <tbody>
                <?php foreach ($recent as $r): ?>
                    <tr>
                        <td class="cell-main"><?= e($r['Name']) ?></td>
                        <td><?= e($r['Email']) ?></td>
                        <td><?= e($r['City']) ?></td>
                        <td><?= e($r['Telephone']) ?></td>
                        <td style="text-align:right;">
                            <a class="btn-link" href="modify.php?rowid=<?= (int) $r['Rowid'] ?>">Ubah</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="form-actions">
            <a href="search.php" class="btn btn-secondary btn-sm">Lihat Semua Entri</a>
        </div>
    <?php endif; ?>
</div>
<?php
page_footer();
