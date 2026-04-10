<?php
require_once __DIR__ . '/../core/auth.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Category.php';
require_admin();
$productModel = new Product();
$categoryModel = new Category();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['name'])) {
    $image = trim($_POST['image'] ?? 'https://via.placeholder.com/300x220?text=Plant');
    $productModel->create([
        'name' => trim($_POST['name']),
        'price' => (float)$_POST['price'],
        'image' => $image,
        'category_id' => (int)$_POST['category_id'],
        'description' => trim($_POST['description']),
        'stock' => (int)$_POST['stock'],
    ]);
    redirect('admin/products.php');
}
$products = $productModel->featured(100);
$categories = $categoryModel->all();
include __DIR__ . '/../includes/header.php';
?>
<h3>Product Management</h3>
<form method="post" class="card p-3 mb-3">
  <div class="row g-2"><div class="col-md-4"><input class="form-control" name="name" placeholder="Product name" required></div><div class="col-md-2"><input class="form-control" name="price" type="number" step="0.01" placeholder="Price" required></div><div class="col-md-2"><input class="form-control" name="stock" type="number" placeholder="Stock" required></div><div class="col-md-4"><select class="form-select" name="category_id"><?php foreach($categories as $c): ?><option value="<?= $c['id'] ?>"><?= e($c['name']) ?></option><?php endforeach; ?></select></div><div class="col-md-8"><input class="form-control" name="image" placeholder="Image URL"></div><div class="col-md-12"><textarea class="form-control" name="description" placeholder="Description"></textarea></div><div class="col-md-2"><button class="btn btn-success">Add Product</button></div></div>
</form>
<table class="table"><tr><th>ID</th><th>Name</th><th>Price</th><th>Stock</th></tr><?php foreach($products as $p): ?><tr><td><?= $p['id'] ?></td><td><?= e($p['name']) ?></td><td>₹<?= number_format((float)$p['price'],2) ?></td><td><?= $p['stock'] ?></td></tr><?php endforeach; ?></table>
<?php include __DIR__ . '/../includes/footer.php'; ?>
