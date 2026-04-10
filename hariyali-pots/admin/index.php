<?php
require_once __DIR__ . '/../core/auth.php';
require_once __DIR__ . '/../models/Order.php';
require_admin();
$stats = (new Order())->stats();
include __DIR__ . '/../includes/header.php';
?>
<h3>Admin Dashboard</h3>
<div class="row g-3">
  <div class="col-md-4"><div class="card p-3"><h5>Total Users</h5><h2><?= $stats['users'] ?></h2></div></div>
  <div class="col-md-4"><div class="card p-3"><h5>Total Orders</h5><h2><?= $stats['orders'] ?></h2></div></div>
  <div class="col-md-4"><div class="card p-3"><h5>Total Revenue</h5><h2>₹<?= number_format((float)$stats['revenue'],2) ?></h2></div></div>
</div>
<div class="mt-4 d-flex gap-2 flex-wrap">
  <a class="btn btn-outline-success" href="<?= base_url('admin/products.php') ?>">Manage Products</a>
  <a class="btn btn-outline-success" href="<?= base_url('admin/categories.php') ?>">Manage Categories</a>
  <a class="btn btn-outline-success" href="<?= base_url('admin/orders.php') ?>">Manage Orders</a>
  <a class="btn btn-outline-success" href="<?= base_url('admin/users.php') ?>">Manage Users</a>
  <a class="btn btn-outline-success" href="<?= base_url('admin/reviews.php') ?>">Manage Reviews</a>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
