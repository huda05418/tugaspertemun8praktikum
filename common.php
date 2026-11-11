<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

function csrf_verify(): void
{
    $token = $_POST['csrf_token'] ?? '';
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        die('Sesi form tidak valid. Muat ulang halaman lalu coba lagi.');
    }
}

function flash_set(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function flash_get(): ?array
{
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $flash;
}

function page_header(string $title, string $active = ''): void
{
    $flash = flash_get();
    ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title) ?> &middot; My Address Book</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header class="navbar">
    <div class="navbar-inner container">
        <a href="main.php" class="brand">
            <span class="brand-mark">&#128218;</span>
            <span>My Address Book</span>
        </a>
        <nav class="nav-links">
            <a href="main.php" class="nav-link <?= $active === 'home' ? 'is-active' : '' ?>">Beranda</a>
            <a href="search.php" class="nav-link <?= $active === 'search' ? 'is-active' : '' ?>">Cari</a>
            <a href="add.php" class="nav-link <?= $active === 'add' ? 'is-active' : '' ?>">Tambah</a>
        </nav>
    </div>
</header>

<main class="container">
    <?php if ($flash): ?>
        <div class="alert alert-<?= e($flash['type']) ?>"><?= e($flash['message']) ?></div>
    <?php endif;
}

function page_footer(): void
{
    ?>
</main>
<footer class="footer">
    <div class="container">My Address Book &middot; Modul 08 &mdash; PHP 8 (mysqli)</div>
</footer>
</body>
</html>
<?php
}

function address_form(array $values, string $action, string $submitLabel, string $cancelUrl): void
{
    ?>
    <form method="post" action="<?= e($action) ?>" class="card">
        <?= csrf_field() ?>
        <div class="form-grid">
            <div class="form-field">
                <label for="cn">Nama <span class="req">*</span></label>
                <input class="input" type="text" id="cn" name="cn" maxlength="100"
                       value="<?= e($values['cn'] ?? '') ?>" required autofocus>
            </div>
            <div class="form-field">
                <label for="mail">E-mail <span class="req">*</span></label>
                <input class="input" type="email" id="mail" name="mail" maxlength="100"
                       value="<?= e($values['mail'] ?? '') ?>" required>
            </div>
            <div class="form-field">
                <label for="locality">Kota</label>
                <input class="input" type="text" id="locality" name="locality" maxlength="50"
                       value="<?= e($values['locality'] ?? '') ?>">
            </div>
            <div class="form-field">
                <label for="telephonenumber">Telepon</label>
                <input class="input" type="text" id="telephonenumber" name="telephonenumber" maxlength="20"
                       value="<?= e($values['telephonenumber'] ?? '') ?>">
            </div>
            <div class="form-field full">
                <label for="description">Deskripsi</label>
                <textarea class="textarea" id="description" name="description"><?= e($values['description'] ?? '') ?></textarea>
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary"><?= e($submitLabel) ?></button>
            <a href="<?= e($cancelUrl) ?>" class="btn btn-secondary">Batal</a>
        </div>
    </form>
    <?php
}
