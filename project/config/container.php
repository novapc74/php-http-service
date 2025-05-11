<?php

use App\Command\TestCommand;

return [
    'settings' => fn() => require __DIR__ . '/settings.php',
    TestCommand::class => DI\autowire(TestCommand::class),
];
