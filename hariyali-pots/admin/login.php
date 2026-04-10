<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../core/helpers.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'] ?? '';
    $user = $email ? (new User())->findByEmail($email) : null;
    if ($user && $user['role'] === 'admin' && password_verify($password, $user['password'])) {
        $_SESSION['admin'] = ['id' => $user['id'], 'name' => $user['name']];
        header('Location: ' . base_url('admin/index.php'));
        exit;
    }
    flash('error', 'Invalid admin credentials.');
}
include __DIR__ . '/../includes/header.php';
?>
<h3>Admin Login</h3>
<form method="post" class="col-md-5"><input class="form-control mb-2" type="email" name="email" required><input class="form-control mb-2" type="password" name="password" required><button class="btn btn-success">Login</button></form>
<?php include __DIR__ . '/../includes/footer.php'; ?>
