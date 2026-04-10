<?php
require_once __DIR__ . '/models/Product.php';
require_once __DIR__ . '/models/Review.php';
require_once __DIR__ . '/core/auth.php';

$productModel = new Product();
$reviewModel = new Review();
$id = (int) ($_GET['id'] ?? 0);
$product = $productModel->find($id);
if (!$product) {
    http_response_code(404);
    include __DIR__ . '/errors/404.php';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rating'])) {
    require_login();
    if (validate_csrf($_POST['csrf_token'] ?? null)) {
        $rating = max(1, min(5, (int) $_POST['rating']));
        $comment = trim($_POST['comment'] ?? '');
        $reviewModel->create((int)$_SESSION['user']['id'], $id, $rating, $comment);
        flash('success', 'Review submitted.');
    }
    redirect('product.php?id=' . $id);
}

$reviews = $reviewModel->byProduct($id);
$avg = $reviewModel->average($id);
include __DIR__ . '/includes/header.php';
?>
<div class="row g-4">
  <div class="col-md-6"><img class="img-fluid rounded" src="<?= e($product['image']) ?>" alt="<?= e($product['name']) ?>"></div>
  <div class="col-md-6">
    <h2><?= e($product['name']) ?></h2>
    <p class="h4 text-success">₹<?= number_format((float)$product['price'], 2) ?></p>
    <p>Stock: <strong><?= (int)$product['stock'] ?></strong></p>
    <p><?= e($product['description']) ?></p>
    <div class="d-flex gap-2">
      <button class="btn btn-success ajax-cart" data-id="<?= $product['id'] ?>">Add to cart</button>
      <a class="btn btn-outline-success" href="<?= base_url('checkout.php?buy=' . $product['id']) ?>">Buy now</a>
      <button class="btn btn-outline-danger ajax-wishlist" data-id="<?= $product['id'] ?>">♥</button>
    </div>
    <div class="mt-3">Average Rating: <strong><?= number_format($avg, 1) ?>/5</strong></div>
  </div>
</div>

<hr>
<h4>Reviews & Ratings</h4>
<?php if (current_user()): ?>
<form method="post" class="mb-4">
  <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
  <div class="row g-2"><div class="col-md-2"><select name="rating" class="form-select"><?php for($i=5;$i>=1;$i--): ?><option><?= $i ?></option><?php endfor; ?></select></div><div class="col-md-8"><input name="comment" class="form-control" placeholder="Share your plant care experience"></div><div class="col-md-2"><button class="btn btn-success w-100">Submit</button></div></div>
</form>
<?php endif; ?>
<?php foreach ($reviews as $r): ?>
<div class="border rounded p-2 mb-2"><strong><?= e($r['name']) ?></strong> <span class="text-warning">★<?= (int)$r['rating'] ?></span><p class="mb-0"><?= e($r['comment']) ?></p></div>
<?php endforeach; ?>
<?php include __DIR__ . '/includes/footer.php'; ?>
