<?php

namespace App\Repositories;

use App\Core\App;
use App\Core\Paginator;

class UserRepository
{
    private \App\Core\Database $db;

    public function __construct()
    {
        $this->db = App::instance()->db();
    }

    public function findById(int $id): ?array
    {
        return $this->db->fetch("SELECT * FROM users WHERE id = ?", [$id]);
    }

    public function findByEmail(string $email): ?array
    {
        return $this->db->fetch("SELECT * FROM users WHERE email = ?", [$email]);
    }

    public function create(array $data): int
    {
        return $this->db->insert('users', $data);
    }

    public function update(int $id, array $data): void
    {
        $this->db->update('users', $data, 'id = ?', [$id]);
    }

    public function delete(int $id): void
    {
        $this->db->delete('users', 'id = ?', [$id]);
    }

    public function count(): int
    {
        return (int) $this->db->query("SELECT COUNT(*) FROM users")->fetchColumn();
    }

    public function paginate(int $perPage = 10): Paginator
    {
        $page = (int) ($_GET['page'] ?? 1);
        $offset = ($page - 1) * $perPage;

        $total = $this->count();
        $items = $this->db->fetchAll(
            "SELECT u.*, (SELECT COUNT(*) FROM reviews WHERE user_id = u.id) as reviews_count
             FROM users u ORDER BY u.created_at DESC LIMIT ? OFFSET ?",
            [$perPage, $offset]
        );

        return new Paginator($items, $total, $perPage, $page);
    }

    public function isAdmin(int $userId): bool
    {
        $user = $this->findById($userId);
        return $user && ($user['role'] ?? '') === 'admin';
    }
}
