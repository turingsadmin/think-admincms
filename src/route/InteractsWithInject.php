<?php

namespace turingAdmin\route;

use think\annotation\Reader;

class InteractsWithInject extends \think\Service
{
    protected Reader $reader;

    public function boot(Reader $reader)
    {
        $this->reader = $reader;
    }
}