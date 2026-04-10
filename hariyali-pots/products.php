<?php
require_once __DIR__ . '/models/Product.php';
require_once __DIR__ . '/models/Category.php';
include __DIR__ . '/includes/header.php';

$productModel = new Product();
$categoryModel = new Category();
$filters = [
    'category' => $_GET['category'] ?? null,
    'min_price' => $_GET['min_price'] ?? null,
    'max_price' => $_GET['max_price'] ?? null,
    'sort' => $_GET['sort'] ?? null,
    'page' => $_GET['page'] ?? 1,
];
$products = $productModel->list($filters);
$total = $productModel->countAll($filters);
$pages = (int) ceil($total / 9);
$categories = $categoryModel->all();
?>
<h2 class="mb-3">Plant Store</h2>
<form class="row g-2 mb-4">
  <div class="col-md-3"><select name="category" class="form-select"><option value="">All Categories</option><?php foreach ($categories as $c): ?><option value="<?= $c['id'] ?>" <?= (($filters['category'] ?? '') == $c['id']) ? 'selected' : '' ?>><?= e($c['name']) ?></option><?php endforeach; ?></select></div>
  <div class="col-md-2"><input type="number" class="form-control" name="min_price" placeholder="Min"></div>
  <div class="col-md-2"><input type="number" class="form-control" name="max_price" placeholder="Max"></div>
  <div class="col-md-2"><select class="form-select" name="sort"><option value="newest">Newest</option><option value="price_asc">Price low-high</option><option value="price_desc">Price high-low</option></select></div>
  <div class="col-md-2"><button class="btn btn-success w-100">Apply</button></div>
</form>
<div class="row g-4">
<?php foreach ($products as $p): ?>
<div class="col-md-4"><div class="card h-100 product-card"><img src="<?= e($p['image']) ?>" class="card-img-top" alt="<?= e($p['name']) ?>"><div class="card-body"><h5><?= e($p['name']) ?></h5><p><?= e($p['category_name']) ?></p><p class="fw-bold text-success">₹<?= number_format((float)$p['price'], 2) ?></p><a class="btn btn-success" href="<?= base_url('product.php?id=' . $p['id']) ?>">Details</a></div></div></div>
<?php endforeach; ?>
</div>
<nav class="mt-4"><ul class="pagination"><?php for ($i = 1; $i <= max($pages, 1); $i++): ?><li class="page-item <?= ($i == ($filters['page'] ?? 1)) ? 'active' : '' ?>"><a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a></li><?php endfor; ?></ul></nav>
<?php include __DIR__ . '/includes/footer.php'; ?>
