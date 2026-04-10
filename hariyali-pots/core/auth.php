<?php
require_once __DIR__ . '/helpers.php';

function current_user(): ?array
{
    return $_SESSION['user'] ?? null;
}

function require_login(): void
{
    if (!current_user()) {
        flash('error', 'Please login first.');
        redirect('login.php');
    }
}

function require_admin(): void
{
    if (empty($_SESSION['admin'])) {
        flash('error', 'Admin login required.');
        header('Location: ' . base_url('admin/login.php'));
        exit;
    }
}
