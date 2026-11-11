<?php
require 'global.php';
require 'common.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: search.php');
    exit;
}

csrf_verify();

$rowid = (int) ($_GET['rowid'] ?? 0);
if ($rowid <= 0) {
    flash_set('danger', 'Entri tidak valid.');
    header('Location: search.php');
    exit;
}

$cn              = trim($_POST['cn'] ?? '');
$mail            = trim($_POST['mail'] ?? '');
$locality        = trim($_POST['locality'] ?? '');
$description     = trim($_POST['description'] ?? '');
$telephonenumber = trim($_POST['telephonenumber'] ?? '');

if ($cn === '' || $mail === '') {
    flash_set('danger', 'Nama dan E-mail wajib diisi.');
    header('Location: modify.php?rowid=' . $rowid);
    exit;
}

$link = db_connect();
$stmt = mysqli_prepare($link, 'UPDATE ' . DB_TABLE . ' SET Name = ?, Email = ?, City = ?, Description = ?, Telephone = ? WHERE Rowid = ?');
mysqli_stmt_bind_param($stmt, 'sssssi', $cn, $mail, $locality, $description, $telephonenumber, $rowid);

if (mysqli_stmt_execute($stmt)) {
    flash_set('success', 'Entri berhasil diperbarui.');
} else {
    flash_set('danger', 'Gagal memperbarui data: ' . mysqli_error($link));
}
mysqli_stmt_close($stmt);
mysqli_close($link);

header('Location: search.php');
exit;
