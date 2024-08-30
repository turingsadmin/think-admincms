<?php

namespace turingAdmins\annotation;

use Ergebnis\Classy\Constructs;
use turingAdmins\annotation\route\GroupController;
use turingAdmins\annotation\route\Middleware;
use turingAdmins\annotation\route\Model;
use turingAdmins\annotation\route\Pattern;
use turingAdmins\annotation\route\Resource;
use turingAdmins\annotation\route\RouteMapper;
use turingAdmins\annotation\route\Validate;
use think\event\RouteLoaded;
use think\helper\Arr;
use think\helper\Str;

use ReflectionClass;
use ReflectionMethod;

trait InteractsWithRoute
{
    protected $route;

    protected $parsedClass = [];

    protected $controllerDir;

    protected $controllerSuffix;

    protected function registerAnnotationRoute()
    {



        if ($this->app->config->get('annotation.route.enable', true)) {
            $this->app->event->listen(RouteLoaded::class, function () {

                $this->route            = $this->app->route;
                $this->controllerDir    = realpath($this->app->getAppPath() . $this->app->config->get('route.controller_layer'));
                $this->controllerSuffix = $this->app->config->get('route.controller_suffix') ? 'Controller' : '';

                $config = [];
                foreach (scandir($this->app->getAppPath()) as $dir)
                {
                    if ($dir !== '.' && $dir !== '..' && is_dir($this->app->getAppPath() . $dir))
                    {
                        if (strpos($this->app->getAppPath() . $dir , 'controller'))
                        {
                            $config[$this->app->getAppPath() . $dir ] = [
                                'middleware'        =>  []
                            ];
                        }else{
                            $config[$this->app->getAppPath() . $dir  . '/controller'] = [
                                'middleware'        =>  []
                            ];
                        }

                    }
                }

                $dirs = array_merge(
                    $config,
                    [$this->controllerDir]
                );

                foreach ($dirs as $dir => $options) {
                    if (is_numeric($dir)) {
                        $dir     = $options;
                        $options = [];
                    }

                    if (is_dir($dir)) {
                        $this->scanDir($dir, $options);
                    }
                }
            });
        }
    }

    protected function scanDir($dir, $options = [])
    {
        $groups = [];
        foreach (Constructs::fromDirectory($dir) as $construct) {
            $class = $construct->name();

            if (in_array($class, $this->parsedClass)) {
                continue;
            }

            $this->parsedClass[] = $class;

            $refClass = new ReflectionClass($class);

            if ($refClass->isAbstract() || $refClass->isInterface() || $refClass->isTrait()) {
                continue;
            }

            $filename = $construct->fileNames()[0];

            $prefix = $class;

            if (Str::startsWith($filename, $this->controllerDir)) {
                //控制器
                $filename = Str::substr($filename, strlen($this->controllerDir) + 1);
                $prefix   = str_replace($this->controllerSuffix . '.php', '', str_replace('/', '.', $filename));
            }

            $routes = [];
            //方法
            foreach ($refClass->getMethods(ReflectionMethod::IS_PUBLIC) as $refMethod) {
                if ($routeAnn = $this->reader->getAnnotation($refMethod, RouteMapper::class)) {

                    $routes[] = function () use ($routeAnn, $prefix, $refMethod) {
                        //注册路由
                        $rule = $this->route->rule($routeAnn->rule, "{$prefix}/{$refMethod->getName()}", $routeAnn->method);

                        $rule->option($routeAnn->options);

                        //变量规则
                        if (!empty($patternsAnn = $this->reader->getAnnotations($refMethod, Pattern::class))) {
                            foreach ($patternsAnn as $patternAnn) {
                                $rule->pattern([$patternAnn->name => $patternAnn->value]);
                            }
                        }

                        //中间件
                        if (!empty($middlewaresAnn = $this->reader->getAnnotations($refMethod, Middleware::class))) {
                            foreach ($middlewaresAnn as $middlewareAnn) {
                                $rule->middleware($middlewareAnn->value, ...$middlewareAnn->params);
                            }
                        }

                        //绑定模型,支持多个
                        if (!empty($modelsAnn = $this->reader->getAnnotations($refMethod, Model::class))) {
                            foreach ($modelsAnn as $modelAnn) {
                                $rule->model($modelAnn->var, $modelAnn->value, $modelAnn->exception);
                            }
                        }

                        //验证
                        if ($validateAnn = $this->reader->getAnnotation($refMethod, Validate::class)) {
                            $rule->validate($validateAnn->value, $validateAnn->scene, $validateAnn->message, $validateAnn->batch);
                        }
                    };
                }
            }

            $groups[] = function () use ($routes, $refClass, $prefix) {
                $groupName    = '';
                $groupOptions = [];
                if ($groupAnn = $this->reader->getAnnotation($refClass, GroupController::class)) {
                    $groupName    = $groupAnn->name;
                    $groupOptions = $groupAnn->options;
                }

                $group = $this->route->group($groupName, function () use ($refClass, $prefix, $routes) {
                    //注册路由
                    foreach ($routes as $route) {
                        $route();
                    }

                    if ($resourceAnn = $this->reader->getAnnotation($refClass, Resource::class)) {
                        //资源路由
                        $this->route->resource($resourceAnn->rule, $prefix)->option($resourceAnn->options);
                    }
                });

                $group->option($groupOptions);

                //变量规则
                if (!empty($patternsAnn = $this->reader->getAnnotations($refClass, Pattern::class))) {
                    foreach ($patternsAnn as $patternAnn) {
                        $group->pattern([$patternAnn->name => $patternAnn->value]);
                    }
                }

                //中间件
                if (!empty($middlewaresAnn = $this->reader->getAnnotations($refClass, Middleware::class))) {
                    foreach ($middlewaresAnn as $middlewareAnn) {
                        $group->middleware($middlewareAnn->value, ...$middlewareAnn->params);
                    }
                }
            };
        }

        if (!empty($groups)) {
            $name = Arr::pull($options, 'name', '');
            $this->route->group($name, function () use ($groups) {
                //注册路由
                foreach ($groups as $group) {
                    $group();
                }
            })->option($options);
        }
    }


}