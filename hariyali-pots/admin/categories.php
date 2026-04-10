<?php
require_once __DIR__ . '/../core/auth.php';
require_once __DIR__ . '/../models/Category.php';
require_admin();
$model = new Category();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['name'])) $model->create(trim($_POST['name']));
    if (!empty($_POST['update_id']) && !empty($_POST['update_name'])) $model->update((int)$_POST['update_id'], trim($_POST['update_name']));
    if (!empty($_POST['delete_id'])) $model->delete((int)$_POST['delete_id']);
    redirect('admin/categories.php');
}
$categories = $model->all();
include __DIR__ . '/../includes/header.php';
?>
<h3>Category Management</h3>
<form method="post" class="row g-2 mb-3"><div class="col-md-4"><input class="form-control" name="name" placeholder="New category"></div><div class="col-md-2"><button class="btn btn-success">Add</button></div></form>
<table class="table"><tr><th>ID</th><th>Name</th><th>Actions</th></tr><?php foreach($categories as $c): ?><tr><td><?= $c['id'] ?></td><td><?= e($c['name']) ?></td><td><form method="post" class="d-flex gap-2"><input type="hidden" name="update_id" value="<?= $c['id'] ?>"><input class="form-control" name="update_name" value="<?= e($c['name']) ?>"><button class="btn btn-sm btn-warning">Update</button><button class="btn btn-sm btn-danger" name="delete_id" value="<?= $c['id'] ?>">Delete</button></form></td></tr><?php endforeach; ?></table>
<?php include __DIR__ . '/../includes/footer.php'; ?>
