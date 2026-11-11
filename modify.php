<?php
require 'global.php';
require 'common.php';

$rowid = (int) ($_GET['rowid'] ?? 0);

if ($rowid <= 0) {
    flash_set('danger', 'Tidak ada entri yang dipilih.');
    header('Location: search.php');
    exit;
}

$link = db_connect();
$stmt = mysqli_prepare($link, 'SELECT Rowid, Name, Email, City, Description, Telephone FROM ' . DB_TABLE . ' WHERE Rowid = ?');
mysqli_stmt_bind_param($stmt, 'i', $rowid);
mysqli_stmt_execute($stmt);
$res  = mysqli_stmt_get_result($stmt);
$row  = mysqli_fetch_assoc($res);
mysqli_stmt_close($stmt);
mysqli_close($link);

if (!$row) {
    flash_set('danger', 'Entri tidak ditemukan.');
    header('Location: search.php');
    exit;
}

$values = [
    'cn'              => $row['Name'],
    'mail'            => $row['Email'],
    'locality'        => $row['City'],
    'description'     => $row['Description'],
    'telephonenumber' => $row['Telephone'],
];

page_header('Ubah Entri', 'search');
?>
<div class="page-head">
    <h1>Ubah Entri</h1>
    <p class="lead">Perbarui data lalu klik <strong>Simpan Perubahan</strong>.</p>
</div>
<?php address_form($values, 'update.php?rowid=' . $rowid, 'Simpan Perubahan', 'search.php'); ?>
<?php
page_footer();
