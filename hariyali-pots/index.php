<?php
require_once __DIR__ . '/models/Product.php';
require_once __DIR__ . '/models/Category.php';
include __DIR__ . '/includes/header.php';

$productModel = new Product();
$categoryModel = new Category();
$featured = $productModel->featured();
$categories = $categoryModel->all();
?>
<div class="hero mb-4">
  <h1>Bring Nature Home with Hariyali Pots</h1>
  <p>Premium indoor plants, outdoor greens, succulents, and designer pots.</p>
  <a href="<?= base_url('products.php') ?>" class="btn btn-light btn-lg">Shop Now</a>
</div>

<div class="search-box mb-4">
  <input id="liveSearch" class="form-control form-control-lg" placeholder="Search plants and pots...">
  <div id="searchResults"></div>
</div>

<h3>Categories</h3>
<div class="row g-3 mb-4">
<?php foreach ($categories as $cat): ?>
  <div class="col-6 col-md-3"><a href="<?= base_url('products.php?category=' . $cat['id']) ?>" class="btn btn-outline-success w-100"><?= e($cat['name']) ?></a></div>
<?php endforeach; ?>
</div>

<h3>Featured Products</h3>
<div class="row g-4">
<?php foreach ($featured as $p): ?>
  <div class="col-md-3">
    <div class="card product-card h-100">
      <img src="<?= e($p['image']) ?>" class="card-img-top" alt="<?= e($p['name']) ?>">
      <div class="card-body d-flex flex-column">
        <h6><?= e($p['name']) ?></h6>
        <p class="text-success fw-bold">₹<?= number_format((float)$p['price'], 2) ?></p>
        <div class="mt-auto d-flex gap-2">
          <a href="<?= base_url('product.php?id=' . $p['id']) ?>" class="btn btn-sm btn-success">View</a>
          <button class="btn btn-sm btn-outline-success ajax-cart" data-id="<?= $p['id'] ?>">Add</button>
        </div>
      </div>
    </div>
  </div>
<?php endforeach; ?>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
