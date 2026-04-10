<?php
require_once __DIR__ . '/../core/auth.php';
require_once __DIR__ . '/../models/Order.php';
require_admin();
$model = new Order();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['order_id'])) {
    $allowed = ['Pending', 'Processing', 'Shipped', 'Delivered'];
    $status = in_array($_POST['status'], $allowed, true) ? $_POST['status'] : 'Pending';
    $model->updateStatus((int)$_POST['order_id'], $status);
    redirect('admin/orders.php');
}
$orders = $model->all();
include __DIR__ . '/../includes/header.php';
?>
<h3>Order Management</h3>
<table class="table"><tr><th>Order</th><th>User</th><th>Total</th><th>Status</th><th>Action</th></tr><?php foreach($orders as $o): ?><tr><td>#<?= $o['id'] ?></td><td><?= e($o['user_name']) ?></td><td>₹<?= number_format((float)$o['total_price'],2) ?></td><td><?= e($o['status']) ?></td><td><form method="post" class="d-flex gap-2"><input type="hidden" name="order_id" value="<?= $o['id'] ?>"><select name="status" class="form-select form-select-sm"><?php foreach(['Pending','Processing','Shipped','Delivered'] as $s): ?><option <?= $o['status']===$s?'selected':'' ?>><?= $s ?></option><?php endforeach; ?></select><button class="btn btn-sm btn-success">Update</button></form></td></tr><?php endforeach; ?></table>
<?php include __DIR__ . '/../includes/footer.php'; ?>
