<?php

namespace App\Facades;

use App\Service\Singleton\Singleton;
use Exception;

class Config extends Singleton
{
    private array $settings;
    private ?string $name;

    protected function __construct()
    {
        $this->settings = require __DIR__ . '/../../config/settings.php';

        parent::__construct();
    }

    public static function name(string $name): self
    {
        $instance = self::getInstance();
        $instance->setName($name);

        return $instance;
    }

    /**
     * @throws Exception
     */
    public function data(): array
    {
        if (!$name = $this->name) {
            throw new Exception('Configuration name is not defined.');
        }

        if ($data = $this->settings[$name] ?? null) {
            return $data;
        }

        throw new Exception("Setting $name not found.");
    }

    private function setName(string $name): void
    {
        $this->name = $name;
    }
}
