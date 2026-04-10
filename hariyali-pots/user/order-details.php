<?php
require_once __DIR__ . '/../core/auth.php';
require_once __DIR__ . '/../models/Order.php';
require_login();
$order = (new Order())->findWithItems((int)($_GET['id'] ?? 0));
$steps = ['Pending', 'Processing', 'Shipped', 'Delivered'];
$current = array_search($order['status'] ?? 'Pending', $steps, true);
include __DIR__ . '/../includes/header.php';
?>
<h3>Order #<?= (int)$order['id'] ?> Details</h3>
<div class="status-track d-flex mb-4"><?php foreach($steps as $i => $step): ?><div class="step <?= $i <= $current ? 'active' : '' ?>"><span class="dot"><?= $i+1 ?></span><div><?= $step ?></div></div><?php endforeach; ?></div>
<ul class="list-group mb-3"><?php foreach(($order['items'] ?? []) as $item): ?><li class="list-group-item d-flex justify-content-between"><span><?= e($item['name']) ?> × <?= (int)$item['quantity'] ?></span><span>₹<?= number_format((float)$item['price'],2) ?></span></li><?php endforeach; ?></ul>
<a class="btn btn-success" href="<?= base_url('user/invoice.php?id=' . (int)$order['id']) ?>">Download Invoice</a>
<?php include __DIR__ . '/../includes/footer.php'; ?>
