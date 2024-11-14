<?php

declare(strict_types=1);

namespace App;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SomeCommand extends Command
{
    protected static $defaultName = 'app:command';

    /**
     * @SomeAnnotation(value="hello")
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        dump(__CLASS__ . ':: INSIDE COMMAND');

        return 0;
    }

    public function getName()
    {
        return static::$defaultName;
    }
}
