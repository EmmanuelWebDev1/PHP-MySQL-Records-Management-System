<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_login();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: index.php'); exit; }
verify_csrf();
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if ($id) {
    $stmt = db()->prepare('DELETE FROM records WHERE id = :id');
    $stmt->execute([':id' => $id]);
    set_flash($stmt->rowCount() ? 'success' : 'error', $stmt->rowCount() ? 'Record deleted.' : 'Record not found.');
} else {
    set_flash('error', 'Invalid record.');
}
header('Location: index.php'); exit;
