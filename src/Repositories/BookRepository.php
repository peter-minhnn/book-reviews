<?php

namespace App\Repositories;

use App\Core\App;
use App\Core\Paginator;

class BookRepository
{
    private \App\Core\Database $db;

    public function __construct()
    {
        $this->db = App::instance()->db();
    }

    public function findById(int $id): ?array
    {
        return $this->db->fetch(
            "SELECT b.*, c.name as category_name, c.slug as category_slug
             FROM books b
             LEFT JOIN categories c ON b.category_id = c.id
             WHERE b.id = ?",
            [$id]
        );
    }

    public function create(array $data): int
    {
        return $this->db->insert('books', $data);
    }

    public function update(int $id, array $data): void
    {
        $this->db->update('books', $data, 'id = ?', [$id]);
    }

    public function delete(int $id): void
    {
        $book = $this->findById($id);
        if ($book && $book['cover_image']) {
            $path = __DIR__ . '/../../public/uploads/' . $book['cover_image'];
            if (file_exists($path)) {
                unlink($path);
            }
        }
        $this->db->delete('books', 'id = ?', [$id]);
    }

    public function count(): int
    {
        return (int) $this->db->query("SELECT COUNT(*) FROM books")->fetchColumn();
    }

    public function latest(int $limit = 8): array
    {
        return $this->db->fetchAll(
            "SELECT b.*, c.name as category_name, c.slug as category_slug
             FROM books b
             LEFT JOIN categories c ON b.category_id = c.id
             ORDER BY b.created_at DESC
             LIMIT ?",
            [$limit]
        );
    }

    public function topRated(int $limit = 4): array
    {
        return $this->db->fetchAll(
            "SELECT b.*, c.name as category_name, c.slug as category_slug,
                    COALESCE(AVG(r.rating), 0) as reviews_avg_rating
             FROM books b
             LEFT JOIN categories c ON b.category_id = c.id
             LEFT JOIN reviews r ON b.id = r.book_id
             GROUP BY b.id, c.name, c.slug
             ORDER BY reviews_avg_rating DESC
             LIMIT ?",
            [$limit]
        );
    }

    public function averageRating(int $bookId): float
    {
        $result = $this->db->fetch(
            "SELECT COALESCE(AVG(rating), 0) as avg_rating FROM reviews WHERE book_id = ?",
            [$bookId]
        );
        return round((float) ($result['avg_rating'] ?? 0), 1);
    }

    public function reviewsCount(int $bookId): int
    {
        $result = $this->db->fetch(
            "SELECT COUNT(*) as cnt FROM reviews WHERE book_id = ?",
            [$bookId]
        );
        return (int) ($result['cnt'] ?? 0);
    }

    public function query(): BookQueryBuilder
    {
        return new BookQueryBuilder($this->db);
    }

    public function paginateWithQuery(BookQueryBuilder $builder, int $perPage = 12): Paginator
    {
        $page = (int) ($_GET['page'] ?? 1);
        $offset = ($page - 1) * $perPage;

        $countSql = "SELECT COUNT(*) FROM ({$builder->toCountSql()}) as sub";
        $total = (int) $this->db->query($countSql, $builder->getParams())->fetchColumn();

        $sql = $builder->toSql() . " LIMIT ? OFFSET ?";
        $params = array_merge($builder->getParams(), [$perPage, $offset]);
        $items = $this->db->fetchAll($sql, $params);

        return new Paginator($items, $total, $perPage, $page);
    }
}
