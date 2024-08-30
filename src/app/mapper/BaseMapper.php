<?php

namespace turingAdmins\app\mapper;
use think\{App, Model, Request};

abstract class BaseMapper
{
    protected Request $request;

    public function __construct( protected App $app)
    {
        $this->request = $this->app->request;
    }


    /**
     *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
     *   查询一条数据
     *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
     * @func   find
     * @Time 2024年08月30日 16:29:40
     * @Author Xiaobin
     * @param int $id
     * @return array|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    protected function find( int $id)
    {
        return $this->model->find($id);
    }


    /**
     *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
     *   查询当前数据列表
     *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
     * @func   getPageList
     * @Time 2024年08月30日 16:29:56
     * @Author Xiaobin
     * @return mixed
     */
    public function getPageList() : mixed
    {
        return $this->model->getPageList();
    }


    /**
     *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
     *   新增数据业务逻辑
     *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
     * @func   save
     * @Time 2024年08月30日 16:30:39
     * @Author Xiaobin
     * @param array|null $params    新增数组内容
     * @return int
     */
    public function save( ? array $params = [] ) : int
    {
        return  $this->model->save( $params );
    }


    /**
     *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
     *  数据更新
     *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
     * @func   update
     * @Time 2024年08月30日 16:30:17
     * @Author Xiaobin
     * @param int $id   数据id
     * @param array|null $data  更新内容
     * @return bool
     */
    public function update( int $id , ?array $data ) : bool
    {
        $model = $this->model->findOrEmpty($id);
        return $model->save($data);
    }


    /**
     *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
     *   真数据删除
     * @func   forceDelete
     * @Time 2024年08月30日 16:27:01
     * @Author Xiaobin
     * @param int $id   数据id
     * @return bool
     */
    public function forceDelete( int $id )
    {
        $model  =   $this->model->findOrEmpty($id);
        return $model->force()->delete();
    }

    /**
     *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
     *   如果有软删除 则为软删除数据 如果没有 则是真删除
     *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
     * @func   delete
     * @Time 2024年08月30日 16:33:01
     * @Author Xiaobin
     * @param int $id
     * @return bool
     */
    public function delete( int $id)
    {
        $model  =   $this->model->findOrEmpty($id);
        return $model->delete();
    }


    protected function uploadFile()
    {

    }

}