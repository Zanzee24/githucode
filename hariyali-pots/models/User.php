<?php
require_once __DIR__ . '/BaseModel.php';

class User extends BaseModel
{
    public function create(string $name, string $email, string $password, string $role = 'user'): bool
    {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare('INSERT INTO users(name, email, password, role) VALUES (:name, :email, :password, :role)');
        return $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':password' => $hash,
            ':role' => $role,
        ]);
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute([':email' => $email]);
        return $stmt->fetch() ?: null;
    }

    public function all(): array
    {
        return $this->db->query('SELECT id, name, email, role, is_blocked, created_at FROM users ORDER BY id DESC')->fetchAll();
    }

    public function updateBlockStatus(int $id, int $blocked): bool
    {
        $stmt = $this->db->prepare('UPDATE users SET is_blocked = :blocked WHERE id = :id');
        return $stmt->execute([':blocked' => $blocked, ':id' => $id]);
    }
}
