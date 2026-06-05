<?php

namespace App\Repositories;

use App\Core\App;

class BookQueryBuilder
{
    private \App\Core\Database $db;
    private array $wheres = [];
    private array $params = [];
    private string $orderBy = 'b.created_at DESC';

    public function __construct(\App\Core\Database $db)
    {
        $this->db = $db;
    }

    public function search(string $term): self
    {
        $this->wheres[] = "(b.title ILIKE ? OR b.author ILIKE ?)";
        $this->params[] = "%{$term}%";
        $this->params[] = "%{$term}%";
        return $this;
    }

    public function whereCategory(int $categoryId): self
    {
        $this->wheres[] = "b.category_id = ?";
        $this->params[] = $categoryId;
        return $this;
    }

    public function orderByRating(): self
    {
        $this->orderBy = 'reviews_avg_rating DESC NULLS LAST';
        return $this;
    }

    public function orderByLatest(): self
    {
        $this->orderBy = 'b.created_at DESC';
        return $this;
    }

    private function buildSelect(): string
    {
        return "SELECT b.*, c.name as category_name, c.slug as category_slug,
                COALESCE(AVG(r.rating), 0) as reviews_avg_rating,
                COUNT(r.id) as reviews_count";
    }

    private function buildFrom(): string
    {
        return "FROM books b
                LEFT JOIN categories c ON b.category_id = c.id
                LEFT JOIN reviews r ON b.id = r.book_id";
    }

    private function buildWhere(): string
    {
        if (empty($this->wheres)) {
            return '';
        }
        return 'WHERE ' . implode(' AND ', $this->wheres);
    }

    public function toCountSql(): string
    {
        return "SELECT b.id " . $this->buildFrom() . " " . $this->buildWhere();
    }

    public function toSql(): string
    {
        return $this->buildSelect() . " " . $this->buildFrom() . " " . $this->buildWhere()
               . " GROUP BY b.id, c.name, c.slug"
               . " ORDER BY " . $this->orderBy;
    }

    public function getParams(): array
    {
        return $this->params;
    }
}
