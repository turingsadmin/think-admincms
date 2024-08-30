<?php

namespace turingAdmins\annotation;

class Service extends \think\Service
{
    use InteractsWithInject , InteractsWithRoute , InteractsWithModel;
    protected Reader $reader;
    public function boot( Reader $reader)
    {
        $this->reader = $reader;

        $this->autoInject();

        $this->registerAnnotationRoute();

        $this->detectModelAnnotations();

    }


}