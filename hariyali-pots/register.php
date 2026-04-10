<?php
require_once __DIR__ . '/models/User.php';
require_once __DIR__ . '/core/helpers.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'] ?? '';

    if ($name && $email && strlen($password) >= 6) {
        $userModel = new User();
        if (!$userModel->findByEmail($email)) {
            $userModel->create($name, $email, $password);
            flash('success', 'Registration successful. Please login.');
            redirect('login.php');
        }
    }
    flash('error', 'Invalid details or email already exists.');
}
include __DIR__ . '/includes/header.php';
?>
<h3>Register</h3>
<form method="post" class="col-md-6">
  <input required name="name" class="form-control mb-2" placeholder="Full Name">
  <input required name="email" type="email" class="form-control mb-2" placeholder="Email">
  <input required name="password" type="password" class="form-control mb-2" placeholder="Password (min 6 chars)">
  <button class="btn btn-success">Create Account</button>
</form>
<?php include __DIR__ . '/includes/footer.php'; ?>
