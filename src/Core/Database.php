<?php

namespace App\Core;

use PDO;
use PDOStatement;

class Database
{
    private ?PDO $pdo = null;

    public function connect(): PDO
    {
        if ($this->pdo) {
            return $this->pdo;
        }

        $config = require __DIR__ . '/../../config/database.php';
        $dsn = "pgsql:host={$config['host']};port={$config['port']};dbname={$config['database']}";

        $this->pdo = new PDO($dsn, $config['username'], $config['password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);

        return $this->pdo;
    }

    public function query(string $sql, array $params = []): PDOStatement
    {
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function fetch(string $sql, array $params = []): ?array
    {
        $result = $this->query($sql, $params)->fetch();
        return $result ?: null;
    }

    public function fetchAll(string $sql, array $params = []): array
    {
        return $this->query($sql, $params)->fetchAll();
    }

    public function insert(string $table, array $data): int
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));

        $this->query(
            "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})",
            array_values($data)
        );

        return (int) $this->connect()->lastInsertId();
    }

    public function update(string $table, array $data, string $where, array $whereParams = []): int
    {
        $sets = implode(', ', array_map(fn($col) => "{$col} = ?", array_keys($data)));
        $params = array_merge(array_values($data), $whereParams);

        $stmt = $this->query(
            "UPDATE {$table} SET {$sets} WHERE {$where}",
            $params
        );

        return $stmt->rowCount();
    }

    public function delete(string $table, string $where, array $params = []): int
    {
        $stmt = $this->query("DELETE FROM {$table} WHERE {$where}", $params);
        return $stmt->rowCount();
    }

    public function beginTransaction(): void
    {
        $this->connect()->beginTransaction();
    }

    public function commit(): void
    {
        $this->connect()->commit();
    }

    public function rollback(): void
    {
        $this->connect()->rollBack();
    }
}
