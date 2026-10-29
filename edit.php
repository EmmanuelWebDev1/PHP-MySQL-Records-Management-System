<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_login();
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) { set_flash('error', 'Choose a valid record to edit.'); header('Location: index.php'); exit; }
$stmt = db()->prepare('SELECT * FROM records WHERE id = :id');
$stmt->execute([':id' => $id]);
$record = $stmt->fetch();
if (!$record) { set_flash('error', 'Record not found.'); header('Location: index.php'); exit; }
$pageTitle = 'Edit record';
$errors = [];
$data = ['name'=>$record['name'],'email'=>$record['email'],'phone'=>$record['phone'],'department'=>$record['department'],'notes'=>$record['notes']];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    foreach ($data as $key => $value) $data[$key] = trim($_POST[$key] ?? '');
    if ($data['name'] === '') $errors[] = 'Name is required.';
    if ($data['email'] !== '' && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Enter a valid email address.';
    if (!$errors) {
        $stmt = db()->prepare('UPDATE records SET name=:name,email=:email,phone=:phone,department=:department,notes=:notes,updated_at=NOW() WHERE id=:id');
        $stmt->execute($data + [':id' => $id]);
        set_flash('success', 'Record updated successfully.');
        header('Location: index.php'); exit;
    }
}
require __DIR__ . '/includes/header.php';
?>
<section class="page-heading"><div><p class="eyebrow">RECORDS / EDIT</p><h1>Edit record</h1><p class="muted">Update this record's details below.</p></div><a class="btn btn-quiet" href="index.php">← Back to records</a></section>
<?php if ($errors): ?><div class="alert error"><strong>Please check the following:</strong><ul><?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
<?php require __DIR__ . '/includes/record-form.php'; ?>
<?php require __DIR__ . '/includes/footer.php'; ?>
