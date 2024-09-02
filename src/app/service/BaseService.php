<?php

namespace turingAdmins\app\service;
use think\Request;
use turingAdmins\annotation\Inject;
use think\App;
use ReflectionClass , ReflectionMethod;
class BaseService
{
    protected Request $request;

    protected array $params = [];

    protected array $fields = [];

    public function __construct( protected App $app)
    {
        $this->request = $this->app->request;
        if (!empty($this->request->param()))
        {
            $this->params = $this->request->param();
            unset($this->params['page'] , $this->params['page_size']);
        }

    }




    /**
     *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
     *   自带搜索分页数据
     *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
     * @func   getPageList
     * @Time 2024年09月02日 11:14:21
     * @Author Xiaobin
     * @return mixed
     */
    public function getPageList(?array $order = [] )
    {
        $page       =  $this->request->get( 'page' , 1);
        $pageSize   =   $this->request->get('page_size' , 15);
        return $this->mapper->model
            ->  withSearch(array_keys($this->params) , $this->params)
            ->  page( ($page - 1) * $pageSize , $pageSize )
            ->  order($order)
            ->  select();
    }


    public function getFind( int $id)
    {
        return $this->mapper->find($id);
    }

}