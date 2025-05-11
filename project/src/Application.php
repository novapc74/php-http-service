<?php

namespace App;

use JetBrains\PhpStorm\NoReturn;
use App\Command\UndefinedCommand;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Container\ContainerExceptionInterface;

class Application
{
    private array $handlers = [];
    private string $command;
    private ContainerInterface $container;

    public function __construct(array $argv, ContainerInterface $container)
    {
        $this->command = $argv[1];
        $this->container = $container;
    }

    public function get(string $command, array $handler = []): void
    {
        $this->handlers[] = [$command, $handler];
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    #[NoReturn] public function run(): void
    {
        foreach ($this->handlers as $item) {

            [$command, $handler] = $item;

            if ($command === $this->command && !empty($handler)) {

                [$class, $classMethod] = $handler;

                $instance = $this->container->get($class);
                echo $instance->$classMethod();

                exit(0);
            }
        }

        echo (new UndefinedCommand())->execute();
        exit(1);
    }
}
