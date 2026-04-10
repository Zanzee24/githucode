<?php
require_once __DIR__ . '/core/auth.php';
require_once __DIR__ . '/models/Order.php';
require_login();
$order = (new Order())->findWithItems((int)($_GET['id'] ?? 0));
include __DIR__ . '/includes/header.php';
?>
<div class="text-center py-5">
  <h2 class="text-success">Order Confirmed 🎉</h2>
  <p>Your order #<?= (int)($order['id'] ?? 0) ?> has been placed successfully.</p>
  <a class="btn btn-success" href="<?= base_url('user/order-details.php?id=' . (int)$order['id']) ?>">Track Order</a>
  <a class="btn btn-outline-success" href="<?= base_url('user/invoice.php?id=' . (int)$order['id']) ?>">Download Invoice</a>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
