<?php
require_once __DIR__ . '/../core/auth.php';
require_once __DIR__ . '/../models/User.php';
require_admin();
$model = new User();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $model->updateBlockStatus((int)$_POST['id'], (int)$_POST['block']);
    redirect('admin/users.php');
}
$users = $model->all();
include __DIR__ . '/../includes/header.php';
?>
<h3>User Management</h3>
<table class="table"><tr><th>Name</th><th>Email</th><th>Role</th><th>Status</th><th>Action</th></tr><?php foreach($users as $u): ?><tr><td><?= e($u['name']) ?></td><td><?= e($u['email']) ?></td><td><?= e($u['role']) ?></td><td><?= $u['is_blocked'] ? 'Blocked' : 'Active' ?></td><td><?php if($u['role']==='user'): ?><form method="post"><input type="hidden" name="id" value="<?= $u['id'] ?>"><input type="hidden" name="block" value="<?= $u['is_blocked']?0:1 ?>"><button class="btn btn-sm btn-<?= $u['is_blocked']?'success':'danger' ?>"><?= $u['is_blocked']?'Unblock':'Block' ?></button></form><?php endif; ?></td></tr><?php endforeach; ?></table>
<?php include __DIR__ . '/../includes/footer.php'; ?>
