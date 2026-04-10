<?php
require_once __DIR__ . '/BaseModel.php';

class Wishlist extends BaseModel
{
    public function toggle(int $userId, int $productId): bool
    {
        $stmt = $this->db->prepare('SELECT id FROM wishlist WHERE user_id=:user_id AND product_id=:product_id');
        $stmt->execute([':user_id' => $userId, ':product_id' => $productId]);
        $existing = $stmt->fetch();

        if ($existing) {
            $del = $this->db->prepare('DELETE FROM wishlist WHERE id=:id');
            return $del->execute([':id' => $existing['id']]);
        }

        $ins = $this->db->prepare('INSERT INTO wishlist(user_id, product_id) VALUES (:user_id,:product_id)');
        return $ins->execute([':user_id' => $userId, ':product_id' => $productId]);
    }

    public function byUser(int $userId): array
    {
        $stmt = $this->db->prepare('SELECT w.id, p.* FROM wishlist w JOIN products p ON p.id = w.product_id WHERE w.user_id=:user_id');
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll();
    }
}
