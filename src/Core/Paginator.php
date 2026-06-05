<?php

namespace App\Core;

class Paginator
{
    private array $items;
    private int $total;
    private int $perPage;
    private int $currentPage;
    private string $path;

    public function __construct(array $items, int $total, int $perPage, int $currentPage, string $path = '')
    {
        $this->items = $items;
        $this->total = $total;
        $this->perPage = $perPage;
        $this->currentPage = $currentPage;
        $this->path = $path ?: Request::uri();
    }

    public function items(): array
    {
        return $this->items;
    }

    public function total(): int
    {
        return $this->total;
    }

    public function perPage(): int
    {
        return $this->perPage;
    }

    public function currentPage(): int
    {
        return $this->currentPage;
    }

    public function lastPage(): int
    {
        return max(1, (int) ceil($this->total / $this->perPage));
    }

    public function hasPages(): bool
    {
        return $this->lastPage() > 1;
    }

    public function onFirstPage(): bool
    {
        return $this->currentPage <= 1;
    }

    public function hasMorePages(): bool
    {
        return $this->currentPage < $this->lastPage();
    }

    public function previousPageUrl(): ?string
    {
        if ($this->currentPage > 1) {
            return $this->url($this->currentPage - 1);
        }
        return null;
    }

    public function nextPageUrl(): ?string
    {
        if ($this->hasMorePages()) {
            return $this->url($this->currentPage + 1);
        }
        return null;
    }

    public function url(int $page): string
    {
        $query = $_GET;
        $query['page'] = $page;
        return $this->path . '?' . http_build_query($query);
    }

    public function links(): string
    {
        return view('vendor.pagination.tailwind', ['paginator' => $this]);
    }
}
