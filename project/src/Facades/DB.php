<?php

namespace App\Facades;

use PDO;
use Exception;
use PDOException;
use App\Service\Singleton\Singleton;

class DB extends Singleton
{
    private PDO $connection;
    private static string $table;

    /**
     * @throws Exception
     */
    protected function __construct()
    {
        $settings = Config::name('mariadb')->data();

        try {
            $this->connection = new PDO(
                "mysql:host={$settings['host']};dbname={$settings['name']}",
                $settings['user'],
                $settings['password']
            );
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
            exit(1);
        }

        parent::__construct();
    }

    public static function table(string $table): static
    {
        $instance = static::getInstance();
        self::$table = $table;

        return $instance;
    }

    public function findAll(): ?array
    {
        if (!$table = self::$table) {
            return null;
        }

        $sql = "SELECT * FROM $table";

        return $this->connection->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $id): ?array
    {
        if (!$table = self::$table) {
            return null;
        }

        $sql = "SELECT * FROM $table WHERE id = $id";

        return $this->connection->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}
