<?php

namespace App\Core;

class Validator
{
    private array $errors = [];
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function required(string $field, string $message = ''): self
    {
        $value = $this->data[$field] ?? null;
        if ($value === null || $value === '') {
            $this->errors[$field][] = $message ?: "Vui lòng nhập trường này.";
        }
        return $this;
    }

    public function string(string $field, string $message = ''): self
    {
        if (isset($this->data[$field]) && !is_string($this->data[$field])) {
            $this->errors[$field][] = $message ?: "Trường này phải là chuỗi.";
        }
        return $this;
    }

    public function max(string $field, int $max, string $message = ''): self
    {
        $value = $this->data[$field] ?? null;
        if ($value !== null && is_string($value) && mb_strlen($value) > $max) {
            $this->errors[$field][] = $message ?: "Không được vượt quá {$max} ký tự.";
        }
        return $this;
    }

    public function email(string $field, string $message = ''): self
    {
        $value = $this->data[$field] ?? null;
        if ($value !== null && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field][] = $message ?: "Email không hợp lệ.";
        }
        return $this;
    }

    public function integer(string $field, string $message = ''): self
    {
        $value = $this->data[$field] ?? null;
        if ($value !== null && !filter_var($value, FILTER_VALIDATE_INT)) {
            $this->errors[$field][] = $message ?: "Trường này phải là số nguyên.";
        }
        return $this;
    }

    public function min(string $field, int $min, string $message = ''): self
    {
        $value = $this->data[$field] ?? null;
        if ($value !== null) {
            if (is_string($value) && mb_strlen($value) < $min) {
                $this->errors[$field][] = $message ?: "Phải có ít nhất {$min} ký tự.";
            } elseif (is_numeric($value) && (int) $value < $min) {
                $this->errors[$field][] = $message ?: "Giá trị tối thiểu là {$min}.";
            }
        }
        return $this;
    }

    public function in(string $field, array $values, string $message = ''): self
    {
        $value = $this->data[$field] ?? null;
        if ($value !== null && !in_array($value, $values)) {
            $this->errors[$field][] = $message ?: "Giá trị không hợp lệ.";
        }
        return $this;
    }

    public function confirmed(string $field, string $message = ''): self
    {
        $value = $this->data[$field] ?? null;
        $confirmation = $this->data[$field . '_confirmation'] ?? null;
        if ($value !== null && $value !== $confirmation) {
            $this->errors[$field][] = $message ?: "Xác nhận không khớp.";
        }
        return $this;
    }

    public function unique(string $field, string $table, string $column, ?int $ignoreId = null, string $message = ''): self
    {
        $value = $this->data[$field] ?? null;
        if ($value !== null) {
            $sql = "SELECT COUNT(*) FROM {$table} WHERE {$column} = ?";
            $params = [$value];
            if ($ignoreId !== null) {
                $sql .= " AND id != ?";
                $params[] = $ignoreId;
            }
            $count = App::instance()->db()->query($sql, $params)->fetchColumn();
            if ($count > 0) {
                $this->errors[$field][] = $message ?: "Giá trị này đã tồn tại.";
            }
        }
        return $this;
    }

    public function nullable(): self
    {
        return $this;
    }

    public function fails(): bool
    {
        return !empty($this->errors);
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function validated(): array
    {
        return $this->data;
    }
}
