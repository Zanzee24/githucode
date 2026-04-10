<?php
require_once __DIR__ . '/../core/auth.php';
require_once __DIR__ . '/../models/Wishlist.php';
header('Content-Type: application/json');
if (!current_user()) {
    echo json_encode(['status' => false, 'message' => 'Please login']);
    exit;
}
$productId = (int)($_POST['product_id'] ?? 0);
if ($productId < 1) {
    echo json_encode(['status' => false, 'message' => 'Invalid product']);
    exit;
}
(new Wishlist())->toggle((int)$_SESSION['user']['id'], $productId);
echo json_encode(['status' => true, 'message' => 'Wishlist updated']);
