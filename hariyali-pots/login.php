<?php
require_once __DIR__ . '/models/User.php';
require_once __DIR__ . '/core/helpers.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'] ?? '';
    $userModel = new User();
    $user = $email ? $userModel->findByEmail($email) : null;

    if ($user && !$user['is_blocked'] && password_verify($password, $user['password'])) {
        session_regenerate_id(true);
        $_SESSION['user'] = ['id' => $user['id'], 'name' => $user['name'], 'email' => $user['email']];
        redirect('index.php');
    }
    flash('error', 'Invalid credentials or blocked account.');
}
include __DIR__ . '/includes/header.php';
?>
<h3>Login</h3>
<form method="post" class="col-md-6">
  <input required name="email" type="email" class="form-control mb-2" placeholder="Email">
  <input required name="password" type="password" class="form-control mb-2" placeholder="Password">
  <button class="btn btn-success">Login</button>
</form>
<p class="mt-3">New user? <a href="<?= base_url('register.php') ?>">Register here</a></p>
<?php include __DIR__ . '/includes/footer.php'; ?>
