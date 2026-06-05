<?php

namespace App\Repositories;

use App\Core\App;
use App\Core\Paginator;

class FavoriteRepository
{
    private \App\Core\Database $db;

    public function __construct()
    {
        $this->db = App::instance()->db();
    }

    public function exists(int $userId, int $bookId): bool
    {
        $count = $this->db->query(
            "SELECT COUNT(*) FROM favorites WHERE user_id = ? AND book_id = ?",
            [$userId, $bookId]
        )->fetchColumn();
        return (int) $count > 0;
    }

    public function add(int $userId, int $bookId): void
    {
        if (!$this->exists($userId, $bookId)) {
            $this->db->insert('favorites', [
                'user_id' => $userId,
                'book_id' => $bookId,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }

    public function remove(int $userId, int $bookId): void
    {
        $this->db->delete('favorites', 'user_id = ? AND book_id = ?', [$userId, $bookId]);
    }

    public function paginateByUser(int $userId, int $perPage = 12): Paginator
    {
        $page = (int) ($_GET['page'] ?? 1);
        $offset = ($page - 1) * $perPage;

        $total = (int) $this->db->query(
            "SELECT COUNT(*) FROM favorites WHERE user_id = ?",
            [$userId]
        )->fetchColumn();

        $items = $this->db->fetchAll(
            "SELECT b.*, c.name as category_name, c.slug as category_slug,
                    COALESCE(AVG(r.rating), 0) as reviews_avg_rating,
                    COUNT(r.id) as reviews_count
             FROM favorites f
             JOIN books b ON f.book_id = b.id
             LEFT JOIN categories c ON b.category_id = c.id
             LEFT JOIN reviews r ON b.id = r.book_id
             WHERE f.user_id = ?
             GROUP BY b.id, c.name, c.slug, f.created_at
             ORDER BY f.created_at DESC
             LIMIT ? OFFSET ?",
            [$userId, $perPage, $offset]
        );

        return new Paginator($items, $total, $perPage, $page);
    }
}
