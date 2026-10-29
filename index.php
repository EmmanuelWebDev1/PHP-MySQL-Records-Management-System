<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_login();
$pageTitle = 'Records';
$search = trim($_GET['q'] ?? '');
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 8;
$offset = ($page - 1) * $perPage;

$where = '';
$params = [];
if ($search !== '') {
    $where = "WHERE name LIKE :q OR email LIKE :q OR phone LIKE :q OR department LIKE :q OR notes LIKE :q";
    $params[':q'] = '%' . $search . '%';
}
$countStmt = db()->prepare("SELECT COUNT(*) FROM records $where");
$countStmt->execute($params);
$total = (int)$countStmt->fetchColumn();
$totalPages = max(1, (int)ceil($total / $perPage));
$page = min($page, $totalPages);
$offset = ($page - 1) * $perPage;
$listStmt = db()->prepare("SELECT * FROM records $where ORDER BY updated_at DESC, id DESC LIMIT :limit OFFSET :offset");
foreach ($params as $key => $value) $listStmt->bindValue($key, $value, PDO::PARAM_STR);
$listStmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$listStmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$listStmt->execute();
$records = $listStmt->fetchAll();
$flash = get_flash();
require __DIR__ . '/includes/header.php';
?>
<section class="page-heading">
  <div><p class="eyebrow">WORKSPACE / DIRECTORY</p><h1>Records management</h1><p class="muted">Create, organize, find, and maintain your records in one place.</p></div>
  <a class="btn btn-primary" href="create.php">＋ Add record</a>
</section>
<?php if ($flash): ?><div class="alert <?= e($flash['type']) ?>"><?= e($flash['message']) ?></div><?php endif; ?>
<section class="panel">
  <div class="panel-toolbar">
    <div><h2>All records <span class="count"><?= $total ?></span></h2><p class="muted small">Search by name, email, phone, department, or notes.</p></div>
    <form class="search-form" method="get" action="index.php">
      <label class="sr-only" for="q">Search records</label><input id="q" name="q" type="search" placeholder="Search records..." value="<?= e($search) ?>">
      <button class="btn btn-dark" type="submit">Search</button>
      <?php if ($search !== ''): ?><a class="btn btn-quiet" href="index.php">Clear</a><?php endif; ?>
    </form>
  </div>
  <?php if (!$records): ?>
    <div class="empty-state"><div class="empty-icon">⌕</div><h3><?= $search ? 'No matching records' : 'Your records start here' ?></h3><p><?= $search ? 'Try another keyword or clear your search.' : 'Add your first record to start building your directory.' ?></p><?php if (!$search): ?><a class="btn btn-primary" href="create.php">Add your first record</a><?php endif; ?></div>
  <?php else: ?>
  <div class="table-wrap"><table><thead><tr><th>Person / Record</th><th>Department</th><th>Phone</th><th>Updated</th><th class="right">Actions</th></tr></thead><tbody>
  <?php foreach ($records as $record): ?>
    <tr>
      <td><div class="person-cell"><span class="avatar"><?= e(strtoupper(mb_substr($record['name'], 0, 1))) ?></span><div><strong><?= e($record['name']) ?></strong><span><?= e($record['email'] ?: 'No email added') ?></span></div></div></td>
      <td><?= e($record['department'] ?: '—') ?></td><td><?= e($record['phone'] ?: '—') ?></td>
      <td><span class="date"><?= e(date('M j, Y', strtotime($record['updated_at']))) ?></span></td>
      <td><div class="actions"><a class="action-link" href="edit.php?id=<?= (int)$record['id'] ?>">Edit</a><form method="post" action="delete.php" class="inline-form" onsubmit="return confirm('Delete this record permanently?');"><?= csrf_field() ?><input type="hidden" name="id" value="<?= (int)$record['id'] ?>"><button class="action-link danger" type="submit">Delete</button></form></div></td>
    </tr>
  <?php endforeach; ?>
  </tbody></table></div>
  <?php if ($totalPages > 1): ?><div class="pagination"><span>Page <?= $page ?> of <?= $totalPages ?></span><div><?php if ($page > 1): ?><a class="btn btn-quiet" href="?q=<?= urlencode($search) ?>&page=<?= $page-1 ?>">← Previous</a><?php endif; ?><?php if ($page < $totalPages): ?><a class="btn btn-quiet" href="?q=<?= urlencode($search) ?>&page=<?= $page+1 ?>">Next →</a><?php endif; ?></div></div><?php endif; ?>
  <?php endif; ?>
</section>
<section class="tip-bar"><span class="tip-icon">✦</span><p><strong>Keep your data useful.</strong> Add clear contact details and use the notes field for relevant context.</p></section>
<?php require __DIR__ . '/includes/footer.php'; ?>
