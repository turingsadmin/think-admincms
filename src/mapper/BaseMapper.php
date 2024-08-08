<?php

namespace turingAdmin\mapper;
use think\App;
use think\Exception;
use think\exception\ValidateException;
use think\Request;

abstract class BaseMapper
{
    protected $model;

    protected Request $request;
    public function __construct( protected App $app)
    {
        $this->request = $this->app->request;
        $this->assignModel();
    }


    abstract public function assignModel();




    public function save()
    {
        halt($this->model->fetchSql(true)->select());
    }

    public function update( int $id , array $data = []) : mixed
    {
        $model = $this->model->findOrEmpty($id);
        if($model->isEmpty()) throw new ValidateException('未查询到内容信息');
        return  $model->update($data);
    }

    public function delete( int $id) : mixed
    {
        $model = $this->model->findOrEmpty($id);
        if($model->isEmpty()) throw new ValidateException('未查询到内容信息');
        return  $model -> delete($id);
    }


    public function saveAll( ? array $data = [])
    {
        return $this->model->saveAll($data);
    }

}