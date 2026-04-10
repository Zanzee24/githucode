<?php
require_once __DIR__ . '/../core/helpers.php';
header('Content-Type: application/json');
$productId = (int)($_POST['product_id'] ?? 0);
$qty = max(1, (int)($_POST['qty'] ?? 1));
if ($productId < 1) {
    echo json_encode(['status' => false, 'message' => 'Invalid product']);
    exit;
}
$_SESSION['cart'][$productId] = ($_SESSION['cart'][$productId] ?? 0) + $qty;
echo json_encode(['status' => true, 'message' => 'Product added to cart']);
