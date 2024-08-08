<?php

namespace turingAdmin\command\make;

use think\console\input\Argument as InputArgument;

class Validate extends \think\console\command\make\Validate
{
    protected function configure(): void
    {
        $this->setName('turimgAdmin')
        ->addArgument('name', InputArgument::REQUIRED, 'What is the name of the migration?')
        ->setHelp(sprintf('%sCreates a new database migration%s', PHP_EOL, PHP_EOL));
    }
}