<?php
require_once __DIR__ . '/BaseModel.php';

class Review extends BaseModel
{
    public function byProduct(int $productId): array
    {
        $stmt = $this->db->prepare('SELECT r.*, u.name FROM reviews r JOIN users u ON u.id = r.user_id WHERE product_id = :product_id ORDER BY r.id DESC');
        $stmt->execute([':product_id' => $productId]);
        return $stmt->fetchAll();
    }

    public function average(int $productId): float
    {
        $stmt = $this->db->prepare('SELECT AVG(rating) avg_rating FROM reviews WHERE product_id = :product_id');
        $stmt->execute([':product_id' => $productId]);
        return (float) ($stmt->fetch()['avg_rating'] ?? 0);
    }

    public function create(int $userId, int $productId, int $rating, string $comment): bool
    {
        $stmt = $this->db->prepare('INSERT INTO reviews(user_id, product_id, rating, comment) VALUES (:user_id,:product_id,:rating,:comment)');
        return $stmt->execute([
            ':user_id' => $userId,
            ':product_id' => $productId,
            ':rating' => $rating,
            ':comment' => $comment,
        ]);
    }

    public function all(): array
    {
        return $this->db->query('SELECT r.*, p.name product_name, u.name user_name FROM reviews r JOIN products p ON p.id=r.product_id JOIN users u ON u.id=r.user_id ORDER BY r.id DESC')->fetchAll();
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM reviews WHERE id=:id');
        return $stmt->execute([':id' => $id]);
    }
}
