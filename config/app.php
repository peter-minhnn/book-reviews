<?php

return [
    'name' => $_ENV['APP_NAME'] ?? 'Book Review',
    'env' => $_ENV['APP_ENV'] ?? 'production',
    'url' => $_ENV['APP_URL'] ?? 'http://localhost',
    'locale' => $_ENV['APP_LOCALE'] ?? 'vi',
];
