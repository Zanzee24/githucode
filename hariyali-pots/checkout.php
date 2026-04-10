<?php
require_once __DIR__ . '/core/auth.php';
require_once __DIR__ . '/models/Product.php';
require_once __DIR__ . '/models/Order.php';
require_login();

$productModel = new Product();
$orderModel = new Order();
$items = [];
$total = 0;

if (!empty($_GET['buy'])) {
    $p = $productModel->find((int)$_GET['buy']);
    if ($p) {
        $p['quantity'] = 1;
        $items[] = $p;
        $total = (float)$p['price'];
    }
} else {
    foreach (($_SESSION['cart'] ?? []) as $id => $qty) {
        $p = $productModel->find((int)$id);
        if ($p) {
            $p['quantity'] = $qty;
            $items[] = $p;
            $total += $qty * $p['price'];
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $address = trim($_POST['address'] ?? '');
    if ($address && $items) {
        $orderId = $orderModel->create((int)$_SESSION['user']['id'], $total, $address, $items);
        unset($_SESSION['cart']);
        redirect('order-confirmation.php?id=' . $orderId);
    }
}

include __DIR__ . '/includes/header.php';
?>
<h3>Checkout</h3>
<div class="row">
  <div class="col-md-6">
    <form method="post">
      <label class="form-label">Delivery Address</label>
      <textarea class="form-control mb-3" name="address" required></textarea>
      <label class="form-label">Payment Method</label>
      <input class="form-control mb-3" value="Cash on Delivery (COD)" readonly>
      <button class="btn btn-success">Place Order</button>
    </form>
  </div>
  <div class="col-md-6">
    <h5>Order Summary</h5>
    <ul class="list-group mb-3"><?php foreach($items as $it): ?><li class="list-group-item d-flex justify-content-between"><span><?= e($it['name']) ?> x <?= (int)$it['quantity'] ?></span><span>₹<?= number_format((float)($it['price']*$it['quantity']),2) ?></span></li><?php endforeach; ?></ul>
    <h4>Total ₹<?= number_format((float)$total,2) ?></h4>
  </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
