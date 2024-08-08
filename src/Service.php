<?php

namespace turingAdmin;

use turingAdmin\command\make\Controller;

class Service extends \think\Service
{
    public function boot()
    {
        $this->commands([
            Controller::class
        ]);
    }
}