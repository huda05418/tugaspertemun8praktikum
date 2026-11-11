<?php
require 'global.php';
require 'common.php';

$filters = [
    'Name'        => trim($_GET['cn'] ?? ''),
    'Email'       => trim($_GET['mail'] ?? ''),
    'City'        => trim($_GET['locality'] ?? ''),
    'Description' => trim($_GET['description'] ?? ''),
    'Telephone'   => trim($_GET['telephonenumber'] ?? ''),
];

$link    = db_connect();
$results = [];

$where  = [];
$params = [];
$types  = '';
foreach ($filters as $column => $value) {
    if ($value !== '') {
        $where[]  = "$column LIKE ?";
        $params[] = '%' . $value . '%';
        $types   .= 's';
    }
}

$sql = 'SELECT Rowid, Name, Email, City, Description, Telephone FROM ' . DB_TABLE;
if ($where) {
    $sql .= ' WHERE ' . implode(' AND ', $where);
}
$sql .= ' ORDER BY Name ASC';

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
mysqli_close($link);

page_header('Cari Buku Alamat', 'search');
?>
<div class="page-head">
    <h1>Cari Buku Alamat</h1>
    <p class="lead">Isi satu atau beberapa kriteria lalu klik <strong>Cari</strong>. Kosongkan semua untuk menampilkan seluruh entri.</p>
</div>

<div class="card">
    <form method="get" action="search.php">
        <div class="form-grid">
            <div class="form-field">
                <label for="cn">Nama</label>
                <input class="input" type="text" id="cn" name="cn" value="<?= e($_GET['cn'] ?? '') ?>">
            </div>
            <div class="form-field">
                <label for="mail">E-mail</label>
                <input class="input" type="text" id="mail" name="mail" value="<?= e($_GET['mail'] ?? '') ?>">
            </div>
            <div class="form-field">
                <label for="locality">Kota</label>
                <input class="input" type="text" id="locality" name="locality" value="<?= e($_GET['locality'] ?? '') ?>">
            </div>
            <div class="form-field">
                <label for="telephonenumber">Telepon</label>
                <input class="input" type="text" id="telephonenumber" name="telephonenumber" value="<?= e($_GET['telephonenumber'] ?? '') ?>">
            </div>
            <div class="form-field full">
                <label for="description">Deskripsi</label>
                <input class="input" type="text" id="description" name="description" value="<?= e($_GET['description'] ?? '') ?>">
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">&#128269; Cari</button>
            <a href="search.php" class="btn btn-secondary">Reset</a>
        </div>
    </form>
</div>

<div class="card">
    <div class="card-head">
        <h3>Hasil (<?= count($results) ?>)</h3>
    </div>
    <?php if (!$results): ?>
        <div class="empty">
            <div class="empty-icon">&#128220;</div>
            <h3>Tidak ada data</h3>
            <p>Tidak ditemukan entri yang cocok dengan kriteria pencarian.</p>
        </div>
    <?php else: ?>
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr><th>Nama</th><th>E-mail</th><th>Kota</th><th>Telepon</th><th>Deskripsi</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                <?php foreach ($results as $r): ?>
                    <tr>
                        <td class="cell-main"><?= e($r['Name']) ?></td>
                        <td><?= e($r['Email']) ?></td>
                        <td><?= e($r['City']) ?></td>
                        <td><?= e($r['Telephone']) ?></td>
                        <td class="cell-sub"><?= e($r['Description']) ?></td>
                        <td>
                            <div class="actions">
                                <a class="btn-link" href="modify.php?rowid=<?= (int) $r['Rowid'] ?>">Ubah</a>
                                <a class="btn-link danger" href="delete.php?rowid=<?= (int) $r['Rowid'] ?>"
                                   onclick="return confirm('Hapus entri ini?');">Hapus</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
<?php
page_footer();
