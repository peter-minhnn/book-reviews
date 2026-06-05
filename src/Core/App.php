<?php

namespace App\Core;

class App
{
    private static ?App $instance = null;
    private Router $router;
    private Database $db;

    public function __construct()
    {
        self::$instance = $this;
        $this->router = new Router();
    }

    public static function instance(): self
    {
        return self::$instance;
    }

    public function router(): Router
    {
        return $this->router;
    }

    public function db(): Database
    {
        if (!isset($this->db)) {
            $this->db = new Database();
        }
        return $this->db;
    }

    public function run(): void
    {
        Session::start();

        // Load routes
        $app = $this;
        require __DIR__ . '/../../routes/web.php';

        $this->router->dispatch();

        // Age flash data AFTER response so views can read flash messages
        Session::ageFlashData();
    }
}
