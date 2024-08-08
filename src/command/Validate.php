<?php

namespace turingAdmin\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;

abstract class Validate extends Command
{
    protected $type;

    abstract protected function getStub();

    protected function execute(Input $input, Output $output)
    {

    }
}