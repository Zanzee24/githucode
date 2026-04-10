<?php
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../core/helpers.php';
$q = trim($_GET['q'] ?? '');
if (strlen($q) < 2) {
    exit;
}
$items = (new Product())->search($q);
if (!$items) {
    echo '<div class="p-2 text-muted">No products found.</div>';
    exit;
}
foreach ($items as $item) {
    echo '<a class="d-block p-2 text-decoration-none border-bottom" href="' . base_url('product.php?id=' . $item['id']) . '">' . e($item['name']) . '</a>';
}
