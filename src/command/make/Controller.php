<?php
namespace turingAdmin\command\make;
use think\console\Command;
use think\console\input\Argument as InputArgument;
use turingAdmin\command\Make;
class Controller extends Make
{
    protected $type = "controller";

    protected function configure(): void
    {
        parent::configure();
        $this   ->  setName('turingAdmin:makeController')
            ->setHelp(sprintf('%sCreates a new database migration%s', PHP_EOL, PHP_EOL));
    }

    protected function getStub(): string
    {
        $stubPath = __DIR__ . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR;
        return $stubPath . 'controller.stub';
    }

    protected function getClassName(string $name): string
    {
        return parent::getClassName($name) . ($this->app->config->get('route.controller_suffix') ? 'Controller' : '');
    }

    protected function getNamespace(string $app): string
    {
        return parent::getNamespace($app) . '\\controller';
    }
}