<?php

namespace App\Command;

class UndefinedCommand
{
    public function execute(): int
    {
        $message = 'Команда не определена';

        echo PHP_EOL . $message . PHP_EOL;

        return 1;
    }
}
