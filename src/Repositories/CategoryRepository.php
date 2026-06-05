<?php

namespace App\Repositories;

use App\Core\App;
use App\Core\Paginator;

class CategoryRepository
{
    private \App\Core\Database $db;

    public function __construct()
    {
        $this->db = App::instance()->db();
    }

    public function findById(int $id): ?array
    {
        return $this->db->fetch("SELECT * FROM categories WHERE id = ?", [$id]);
    }

    public function findBySlug(string $slug): ?array
    {
        return $this->db->fetch("SELECT * FROM categories WHERE slug = ?", [$slug]);
    }

    public function all(): array
    {
        return $this->db->fetchAll("SELECT * FROM categories ORDER BY name");
    }

    public function create(array $data): int
    {
        return $this->db->insert('categories', $data);
    }

    public function update(int $id, array $data): void
    {
        $this->db->update('categories', $data, 'id = ?', [$id]);
    }

    public function delete(int $id): void
    {
        $this->db->delete('categories', 'id = ?', [$id]);
    }

    public function paginate(int $perPage = 10): Paginator
    {
        $page = (int) ($_GET['page'] ?? 1);
        $offset = ($page - 1) * $perPage;

        $total = (int) $this->db->query("SELECT COUNT(*) FROM categories")->fetchColumn();
        $items = $this->db->fetchAll(
            "SELECT c.*, (SELECT COUNT(*) FROM books WHERE category_id = c.id) as books_count
             FROM categories c ORDER BY c.name LIMIT ? OFFSET ?",
            [$perPage, $offset]
        );

        return new Paginator($items, $total, $perPage, $page);
    }
}
