<?php
require_once __DIR__ . '/../core/auth.php';
require_once __DIR__ . '/../models/Order.php';
require_login();
$orders = (new Order())->byUser((int)$_SESSION['user']['id']);
include __DIR__ . '/../includes/header.php';
?>
<h3>Order History</h3>
<table class="table"><tr><th>ID</th><th>Total</th><th>Status</th><th>Date</th><th></th></tr><?php foreach($orders as $o): ?><tr><td>#<?= $o['id'] ?></td><td>₹<?= number_format((float)$o['total_price'],2) ?></td><td><span class="badge bg-success"><?= e($o['status']) ?></span></td><td><?= e($o['created_at']) ?></td><td><a class="btn btn-sm btn-outline-success" href="<?= base_url('user/order-details.php?id=' . $o['id']) ?>">Details</a></td></tr><?php endforeach; ?></table>
<?php include __DIR__ . '/../includes/footer.php'; ?>
