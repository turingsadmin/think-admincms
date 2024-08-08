<?php

namespace turimgAdmin;

use turimgAdmin\command\make\Controller;

class Service extends \think\Service
{
    public function boot()
    {
        $this->commands([
            Controller::class
        ]);
    }
}