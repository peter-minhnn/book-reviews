<?php

return [
    'host' => $_ENV['DB_HOST'] ?? '127.0.0.1',
    'port' => $_ENV['DB_PORT'] ?? '5432',
    'database' => $_ENV['DB_DATABASE'] ?? 'book_review_db',
    'username' => $_ENV['DB_USERNAME'] ?? 'book_review_user',
    'password' => $_ENV['DB_PASSWORD'] ?? '',
];
