<?php

namespace turingAdmin\Controller;

use think\App;
use think\Request;

abstract class BaseController
{
    protected Request $request;
    public function __construct(protected App $app)
    {
        $this->request  =   $this->app->request;
    }
}