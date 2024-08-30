<?php

namespace turingAdmins;


class Service extends \think\Service
{
    public function register()
    {
        $this->app->config->set(['ab'=>['middleware' => []]],'annotation.route.controllers');
    }
    public function boot()
    {
        $this->app->config->set(['ab'=>['middleware' => []]],'annotation.route.controllers');
    }
}