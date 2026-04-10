<?php
require_once __DIR__ . '/BaseModel.php';

class Order extends BaseModel
{
    public function create(int $userId, float $total, string $address, array $items): int
    {
        $this->db->beginTransaction();
        try {
            $stmt = $this->db->prepare('INSERT INTO orders(user_id,total_price,status,address) VALUES (:user_id,:total_price,:status,:address)');
            $stmt->execute([
                ':user_id' => $userId,
                ':total_price' => $total,
                ':status' => 'Pending',
                ':address' => $address,
            ]);

            $orderId = (int) $this->db->lastInsertId();
            $itemStmt = $this->db->prepare('INSERT INTO order_items(order_id,product_id,quantity,price) VALUES (:order_id,:product_id,:quantity,:price)');
            $stockStmt = $this->db->prepare('UPDATE products SET stock = stock - :qty WHERE id = :id AND stock >= :qty');

            foreach ($items as $item) {
                $itemStmt->execute([
                    ':order_id' => $orderId,
                    ':product_id' => $item['id'],
                    ':quantity' => $item['quantity'],
                    ':price' => $item['price'],
                ]);
                $stockStmt->execute([':qty' => $item['quantity'], ':id' => $item['id']]);
            }

            $this->db->commit();
            return $orderId;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function byUser(int $userId): array
    {
        $stmt = $this->db->prepare('SELECT * FROM orders WHERE user_id = :user_id ORDER BY id DESC');
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll();
    }

    public function findWithItems(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT o.*, u.name, u.email FROM orders o JOIN users u ON o.user_id=u.id WHERE o.id = :id');
        $stmt->execute([':id' => $id]);
        $order = $stmt->fetch();
        if (!$order) {
            return null;
        }

        $itemStmt = $this->db->prepare('SELECT oi.*, p.name FROM order_items oi JOIN products p ON p.id=oi.product_id WHERE oi.order_id = :order_id');
        $itemStmt->execute([':order_id' => $id]);
        $order['items'] = $itemStmt->fetchAll();
        return $order;
    }

    public function all(): array
    {
        return $this->db->query('SELECT o.*, u.name user_name FROM orders o JOIN users u ON u.id=o.user_id ORDER BY o.id DESC')->fetchAll();
    }

    public function updateStatus(int $id, string $status): bool
    {
        $stmt = $this->db->prepare('UPDATE orders SET status=:status WHERE id=:id');
        return $stmt->execute([':status' => $status, ':id' => $id]);
    }

    public function stats(): array
    {
        $users = (int) $this->db->query('SELECT COUNT(*) c FROM users WHERE role="user"')->fetch()['c'];
        $orders = (int) $this->db->query('SELECT COUNT(*) c FROM orders')->fetch()['c'];
        $revenue = (float) $this->db->query('SELECT COALESCE(SUM(total_price),0) c FROM orders')->fetch()['c'];
        return compact('users', 'orders', 'revenue');
    }
}
