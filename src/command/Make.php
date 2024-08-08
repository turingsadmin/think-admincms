<?php

namespace turingAdmin\command;

use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\Output;

abstract class Make extends Command
{
    protected $type;

    abstract protected function getStub();

    protected function configure(): void
    {
        $this->addArgument('name', Argument::REQUIRED, "The name of the class");
    }
    protected function execute(Input $input, Output $output)
    {
        $name = trim($input->getArgument('name'));
        $classname = $this->getClassName($name);
        $pathname = $this->getPathName($classname);


        if (is_file($pathname)) {
            $output->writeln('<error>' . $this->type . ':' . $classname . ' already exists!</error>');
            return false;
        }
        if (!is_dir(dirname($pathname))) {
            mkdir(dirname($pathname), 0755, true);
        }
        # 创建文件
        file_put_contents($pathname, $this->buildClass($classname));

    }


    protected function buildClass(string $name)
    {
        $stub = file_get_contents($this->getStub());

        $namespace = trim(implode('\\', array_slice(explode('\\', $name), 0, -1)), '\\');

        $class = str_replace($namespace . '\\', '', $name);

        return str_replace(['{%className%}', '{%actionSuffix%}', '{%namespace%}', '{%app_namespace%}'], [
            $class,
            $this->app->config->get('route.action_suffix'),
            $namespace,
            $this->app->getNamespace(),
        ], $stub);
    }

    protected function getPathName(string $name): string
    {
        $name = substr($name, 4);

        return $this->app->getBasePath() . ltrim(str_replace('\\', '/', $name), '/') . '.php';
    }

    protected function getClassName(string $name): string
    {
        if (str_contains($name, '\\')) {
            return $name;
        }

        if (str_contains($name, '@')) {
            [$app, $name] = explode('@', $name);
        } else {
            $app = '';
        }

        if (str_contains($name, '/')) {
            $name = str_replace('/', '\\', $name);
        }

        return $this->getNamespace($app) . '\\' . $name;
    }

    protected function getNamespace(string $app): string
    {
        return 'app' . ($app ? '\\' . $app : '');
    }
}