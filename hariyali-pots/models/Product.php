<?php
require_once __DIR__ . '/BaseModel.php';

class Product extends BaseModel
{
    public function featured(int $limit = 8): array
    {
        $stmt = $this->db->prepare('SELECT p.*, c.name AS category_name FROM products p JOIN categories c ON p.category_id = c.id ORDER BY p.created_at DESC LIMIT :limit');
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT p.*, c.name AS category_name FROM products p JOIN categories c ON p.category_id = c.id WHERE p.id = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function search(string $term): array
    {
        $stmt = $this->db->prepare('SELECT id, name FROM products WHERE name LIKE :term LIMIT 8');
        $stmt->execute([':term' => '%' . $term . '%']);
        return $stmt->fetchAll();
    }

    public function list(array $filters = []): array
    {
        $where = [];
        $params = [];

        if (!empty($filters['category'])) {
            $where[] = 'p.category_id = :category';
            $params[':category'] = (int) $filters['category'];
        }

        if (!empty($filters['min_price'])) {
            $where[] = 'p.price >= :min_price';
            $params[':min_price'] = (float) $filters['min_price'];
        }

        if (!empty($filters['max_price'])) {
            $where[] = 'p.price <= :max_price';
            $params[':max_price'] = (float) $filters['max_price'];
        }

        $orderBy = 'p.created_at DESC';
        if (($filters['sort'] ?? '') === 'price_asc') {
            $orderBy = 'p.price ASC';
        } elseif (($filters['sort'] ?? '') === 'price_desc') {
            $orderBy = 'p.price DESC';
        }

        $page = max(1, (int)($filters['page'] ?? 1));
        $perPage = 9;
        $offset = ($page - 1) * $perPage;

        $whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';
        $sql = "SELECT p.*, c.name AS category_name FROM products p JOIN categories c ON c.id = p.category_id $whereSql ORDER BY $orderBy LIMIT :offset, :perPage";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v);
        }
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function countAll(array $filters = []): int
    {
        $where = [];
        $params = [];

        if (!empty($filters['category'])) {
            $where[] = 'category_id = :category';
            $params[':category'] = (int) $filters['category'];
        }
        $whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM products $whereSql");
        $stmt->execute($params);
        return (int) $stmt->fetch()['count'];
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare('INSERT INTO products(name, price, image, category_id, description, stock) VALUES (:name,:price,:image,:category_id,:description,:stock)');
        return $stmt->execute([
            ':name' => $data['name'],
            ':price' => $data['price'],
            ':image' => $data['image'],
            ':category_id' => $data['category_id'],
            ':description' => $data['description'],
            ':stock' => $data['stock'],
        ]);
    }

    public function updateStock(int $id, int $stock): bool
    {
        $stmt = $this->db->prepare('UPDATE products SET stock = :stock WHERE id = :id');
        return $stmt->execute([':stock' => $stock, ':id' => $id]);
    }
}
