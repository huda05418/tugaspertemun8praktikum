<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require 'config.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: index.php');
    exit;
}

$stmt = mysqli_prepare($conn, "DELETE FROM addrbook WHERE Rowid = ?");
mysqli_stmt_bind_param($stmt, 'i', $id);

if (mysqli_stmt_execute($stmt)) {
    $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Kontak berhasil dihapus.'];
} else {
    $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Gagal menghapus: ' . mysqli_error($conn)];
}
mysqli_stmt_close($stmt);
mysqli_close($conn);

header('Location: index.php');
exit;
