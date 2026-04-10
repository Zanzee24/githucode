<?php
require_once __DIR__ . '/../core/auth.php';
require_once __DIR__ . '/../models/Review.php';
require_admin();
$model = new Review();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['delete_id'])) {
    $model->delete((int)$_POST['delete_id']);
    redirect('admin/reviews.php');
}
$reviews = $model->all();
include __DIR__ . '/../includes/header.php';
?>
<h3>Review Management</h3>
<table class="table"><tr><th>User</th><th>Product</th><th>Rating</th><th>Comment</th><th></th></tr><?php foreach($reviews as $r): ?><tr><td><?= e($r['user_name']) ?></td><td><?= e($r['product_name']) ?></td><td><?= (int)$r['rating'] ?></td><td><?= e($r['comment']) ?></td><td><form method="post"><button class="btn btn-sm btn-danger" name="delete_id" value="<?= $r['id'] ?>">Delete</button></form></td></tr><?php endforeach; ?></table>
<?php include __DIR__ . '/../includes/footer.php'; ?>
