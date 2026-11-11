<?php
require 'global.php';
require 'common.php';

$rowid = (int) ($_GET['rowid'] ?? 0);
if ($rowid <= 0) {
    flash_set('danger', 'Entri tidak valid.');
    header('Location: search.php');
    exit;
}

$link = db_connect();
$stmt = mysqli_prepare($link, 'DELETE FROM ' . DB_TABLE . ' WHERE Rowid = ?');
mysqli_stmt_bind_param($stmt, 'i', $rowid);

if (mysqli_stmt_execute($stmt)) {
    flash_set('success', 'Entri berhasil dihapus.');
} else {
    flash_set('danger', 'Gagal menghapus data: ' . mysqli_error($link));
}
mysqli_stmt_close($stmt);
mysqli_close($link);

header('Location: search.php');
exit;
