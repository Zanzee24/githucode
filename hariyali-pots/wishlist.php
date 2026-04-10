<?php
require_once __DIR__ . '/core/auth.php';
require_once __DIR__ . '/models/Wishlist.php';
require_login();
$items = (new Wishlist())->byUser((int)$_SESSION['user']['id']);
include __DIR__ . '/includes/header.php';
?>
<h3>My Wishlist</h3>
<div class="row g-3"><?php foreach($items as $item): ?><div class="col-md-3"><div class="card h-100"><img class="card-img-top" src="<?= e($item['image']) ?>"><div class="card-body"><h6><?= e($item['name']) ?></h6><a class="btn btn-success btn-sm" href="<?= base_url('product.php?id=' . $item['id']) ?>">View</a></div></div></div><?php endforeach; ?></div>
<?php include __DIR__ . '/includes/footer.php'; ?>
