<?php
namespace turingAdmins\annotation;
use ReflectionClass;
use ReflectionMethod;
use turingAdmins\annotation\model\relation\BelongsToMany;
use turingAdmins\annotation\model\relation\HasMany;
use turingAdmins\annotation\model\relation\HasManyThrough;
use turingAdmins\annotation\model\relation\HasOne;
use turingAdmins\annotation\model\relation\HasOneThrough;
use turingAdmins\annotation\model\relation\MorphByMany;
use turingAdmins\annotation\model\relation\MorphMany;
use turingAdmins\annotation\model\relation\MorphOne;
use turingAdmins\annotation\model\relation\MorphTo;
use turingAdmins\annotation\model\relation\MorphToMany;
use think\helper\Str;
use think\Model;
use think\model\Collection;

trait InteractsWithModel
{
    protected array $detected = [];

    protected function detectModelAnnotations()
    {
        if ($this->app->config->get('annotation.model.enable', true)) {

            Model::maker(function (Model $model) {
                $className = get_class($model);
                if (!isset($this->detected[$className])) {
                    $annotations = $this->reader->getAnnotations(new ReflectionClass($model), \think\annotation\model\Relation::class);

                    foreach ($annotations as $annotation) {

                        $relation = function () use ($annotation) {

                            $refMethod = new ReflectionMethod($this, Str::camel(class_basename($annotation)));

                            $args = [];
                            foreach ($refMethod->getParameters() as $param) {
                                $args[] = $annotation->{$param->getName()};
                            }

                            return $refMethod->invokeArgs($this, $args);
                        };

                        call_user_func([$model, 'macro'], $annotation->name, $relation);
                    }

                    $this->detected[$className] = true;
                }
            });

            $this->app->event->listen(ModelGenerator::class, function (ModelGenerator $generator) {

                $annotations = $this->reader->getAnnotations($generator->getReflection(), Relation::class);

                foreach ($annotations as $annotation) {
                    $property = Str::snake($annotation->name);
                    switch (true) {
                        case $annotation instanceof HasOne:
                            $generator->addMethod($annotation->name, \think\model\relation\HasOne::class, [], '');
                            $generator->addProperty($property, $annotation->model, true);
                            break;
                        case $annotation instanceof \think\annotation\model\relation\BelongsTo:
                            $generator->addMethod($annotation->name, \think\model\relation\BelongsTo::class, [], '');
                            $generator->addProperty($property, $annotation->model, true);
                            break;
                        case $annotation instanceof HasMany:
                            $generator->addMethod($annotation->name, \think\model\relation\HasMany::class, [], '');
                            $generator->addProperty($property, $annotation->model . '[]', true);
                            break;
                        case $annotation instanceof HasManyThrough:
                            $generator->addMethod($annotation->name, \think\model\relation\HasManyThrough::class, [], '');
                            $generator->addProperty($property, $annotation->model . '[]', true);
                            break;
                        case $annotation instanceof HasOneThrough:
                            $generator->addMethod($annotation->name, \think\model\relation\HasOneThrough::class, [], '');
                            $generator->addProperty($property, $annotation->model, true);
                            break;
                        case $annotation instanceof BelongsToMany:
                            $generator->addMethod($annotation->name, \think\model\relation\BelongsToMany::class, [], '');
                            $generator->addProperty($property, $annotation->model . '[]', true);
                            break;
                        case $annotation instanceof MorphOne:
                            $generator->addMethod($annotation->name, \think\model\relation\MorphOne::class, [], '');
                            $generator->addProperty($property, $annotation->model, true);
                            break;
                        case $annotation instanceof MorphMany:
                            $generator->addMethod($annotation->name, \think\model\relation\MorphMany::class, [], '');
                            $generator->addProperty($property, 'mixed', true);
                            break;
                        case $annotation instanceof MorphTo:
                            $generator->addMethod($annotation->name, \think\model\relation\MorphTo::class, [], '');
                            $generator->addProperty($property, 'mixed', true);
                            break;
                        case $annotation instanceof MorphToMany:
                        case $annotation instanceof MorphByMany:
                            $generator->addMethod($annotation->name, \think\model\relation\MorphToMany::class, [], '');
                            $generator->addProperty($property, Collection::class, true);
                            break;
                    }
                }
            });
        }
    }
}