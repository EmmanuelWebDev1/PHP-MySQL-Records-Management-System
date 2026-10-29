<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_login();
$pageTitle = 'Add record';
$errors = [];
$data = ['name'=>'','email'=>'','phone'=>'','department'=>'','notes'=>''];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    foreach ($data as $key => $value) $data[$key] = trim($_POST[$key] ?? '');
    if ($data['name'] === '') $errors[] = 'Name is required.';
    if ($data['email'] !== '' && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Enter a valid email address.';
    if (!$errors) {
        $stmt = db()->prepare('INSERT INTO records (name,email,phone,department,notes,created_at,updated_at) VALUES (:name,:email,:phone,:department,:notes,NOW(),NOW())');
        $stmt->execute($data);
        set_flash('success', 'Record added successfully.');
        header('Location: index.php'); exit;
    }
}
require __DIR__ . '/includes/header.php';
?>
<section class="page-heading"><div><p class="eyebrow">RECORDS / NEW</p><h1>Add a record</h1><p class="muted">Enter the details you want to keep in your directory.</p></div><a class="btn btn-quiet" href="index.php">← Back to records</a></section>
<?php if ($errors): ?><div class="alert error"><strong>Please check the following:</strong><ul><?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
<?php require __DIR__ . '/includes/record-form.php'; ?>
<?php require __DIR__ . '/includes/footer.php'; ?>
