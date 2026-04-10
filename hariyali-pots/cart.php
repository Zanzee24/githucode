<?php
require_once __DIR__ . '/models/Product.php';
require_once __DIR__ . '/core/helpers.php';
include __DIR__ . '/includes/header.php';

$cart = $_SESSION['cart'] ?? [];
$productModel = new Product();
$items = [];
$total = 0;

foreach ($cart as $id => $qty) {
    $p = $productModel->find((int)$id);
    if ($p) {
        $p['quantity'] = $qty;
        $p['line_total'] = $qty * $p['price'];
        $total += $p['line_total'];
        $items[] = $p;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST['qty'] ?? [] as $id => $qty) {
        $_SESSION['cart'][(int)$id] = max(1, (int)$qty);
    }
    if (isset($_POST['remove'])) {
        unset($_SESSION['cart'][(int)$_POST['remove']]);
    }
    redirect('cart.php');
}
?>
<h3>Shopping Cart</h3>
<form method="post">
<table class="table align-middle"><tr><th>Product</th><th>Price</th><th>Qty</th><th>Total</th><th></th></tr>
<?php foreach ($items as $item): ?>
<tr><td><?= e($item['name']) ?></td><td>₹<?= number_format((float)$item['price'],2) ?></td><td><input type="number" min="1" class="form-control" style="width:90px" name="qty[<?= $item['id'] ?>]" value="<?= $item['quantity'] ?>"></td><td>₹<?= number_format((float)$item['line_total'],2) ?></td><td><button class="btn btn-sm btn-danger" name="remove" value="<?= $item['id'] ?>">Remove</button></td></tr>
<?php endforeach; ?>
</table>
<div class="d-flex justify-content-between">
  <h4>Total: ₹<?= number_format((float)$total,2) ?></h4>
  <div><button class="btn btn-outline-success">Update Cart</button> <a href="<?= base_url('checkout.php') ?>" class="btn btn-success">Checkout</a></div>
</div>
</form>
<?php include __DIR__ . '/includes/footer.php'; ?>
