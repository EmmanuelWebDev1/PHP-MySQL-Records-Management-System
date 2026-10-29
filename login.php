<?php
require_once __DIR__ . '/includes/bootstrap.php';
if (is_logged_in()) { header('Location: index.php'); exit; }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $stmt = db()->prepare('SELECT id, username, password_hash FROM users WHERE username = :username LIMIT 1');
    $stmt->execute([':username' => $username]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = (int)$user['id'];
        $_SESSION['username'] = $user['username'];
        header('Location: index.php'); exit;
    }
    $error = 'Incorrect username or password.';
}
$pageTitle = 'Sign in';
require __DIR__ . '/includes/header.php';
?>
<div class="login-wrap"><form class="form-card login-card" method="post"><?= csrf_field() ?><div class="login-mark">RM</div><p class="eyebrow">WELCOME BACK</p><h1>Sign in</h1><p class="muted">Access your records workspace.</p>
<?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>
<div class="field"><label for="username">Username</label><input id="username" name="username" autocomplete="username" required value="<?= e($_POST['username'] ?? '') ?>"></div>
<div class="field"><label for="password">Password</label><input id="password" name="password" type="password" autocomplete="current-password" required></div>
<button class="btn btn-primary full-width" type="submit">Sign in <span>→</span></button><p class="form-note">Demo login: <strong>admin</strong> / <strong>password</strong>. Change it before deployment.</p></form></div>
<?php require __DIR__ . '/includes/footer.php'; ?>
