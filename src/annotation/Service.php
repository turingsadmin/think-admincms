<?php

namespace turingAdmins\annotation;

class Service extends \think\Service
{
    public function boot()
    {
        halt('annotation');
    }
}