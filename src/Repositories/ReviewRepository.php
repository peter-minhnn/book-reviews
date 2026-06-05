<?php

namespace App\Repositories;

use App\Core\App;
use App\Core\Paginator;

class ReviewRepository
{
    private \App\Core\Database $db;

    public function __construct()
    {
        $this->db = App::instance()->db();
    }

    public function findById(int $id): ?array
    {
        return $this->db->fetch(
            "SELECT r.*, u.name as user_name, b.title as book_title
             FROM reviews r
             LEFT JOIN users u ON r.user_id = u.id
             LEFT JOIN books b ON r.book_id = b.id
             WHERE r.id = ?",
            [$id]
        );
    }

    public function findByBook(int $bookId): array
    {
        return $this->db->fetchAll(
            "SELECT r.*, u.name as user_name
             FROM reviews r
             LEFT JOIN users u ON r.user_id = u.id
             WHERE r.book_id = ?
             ORDER BY r.created_at DESC",
            [$bookId]
        );
    }

    public function findByUserAndBook(int $userId, int $bookId): ?array
    {
        return $this->db->fetch(
            "SELECT * FROM reviews WHERE user_id = ? AND book_id = ?",
            [$userId, $bookId]
        );
    }

    public function exists(int $userId, int $bookId): bool
    {
        $count = $this->db->query(
            "SELECT COUNT(*) FROM reviews WHERE user_id = ? AND book_id = ?",
            [$userId, $bookId]
        )->fetchColumn();
        return (int) $count > 0;
    }

    public function create(array $data): int
    {
        return $this->db->insert('reviews', $data);
    }

    public function update(int $id, array $data): void
    {
        $this->db->update('reviews', $data, 'id = ?', [$id]);
    }

    public function delete(int $id): void
    {
        $this->db->delete('reviews', 'id = ?', [$id]);
    }

    public function count(): int
    {
        return (int) $this->db->query("SELECT COUNT(*) FROM reviews")->fetchColumn();
    }

    public function latest(int $limit = 5): array
    {
        return $this->db->fetchAll(
            "SELECT r.*, u.name as user_name, b.title as book_title
             FROM reviews r
             LEFT JOIN users u ON r.user_id = u.id
             LEFT JOIN books b ON r.book_id = b.id
             ORDER BY r.created_at DESC
             LIMIT ?",
            [$limit]
        );
    }

    public function latestForSSE(int $sinceId, int $limit = 5): array
    {
        return $this->db->fetchAll(
            "SELECT r.*, u.name as user_name, u.name as reviewer_name, b.title as book_title, b.id as book_id
             FROM reviews r
             LEFT JOIN users u ON r.user_id = u.id
             LEFT JOIN books b ON r.book_id = b.id
             WHERE r.id > ?
             ORDER BY r.created_at DESC
             LIMIT ?",
            [$sinceId, $limit]
        );
    }

    public function maxId(): int
    {
        return (int) $this->db->query("SELECT COALESCE(MAX(id), 0) FROM reviews")->fetchColumn();
    }

    public function paginate(int $perPage = 15): Paginator
    {
        $page = (int) ($_GET['page'] ?? 1);
        $offset = ($page - 1) * $perPage;

        $total = $this->count();
        $items = $this->db->fetchAll(
            "SELECT r.*, u.name as user_name, b.title as book_title
             FROM reviews r
             LEFT JOIN users u ON r.user_id = u.id
             LEFT JOIN books b ON r.book_id = b.id
             ORDER BY r.created_at DESC
             LIMIT ? OFFSET ?",
            [$perPage, $offset]
        );

        return new Paginator($items, $total, $perPage, $page);
    }
}
