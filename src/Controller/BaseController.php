<?php

namespace turingAdmin\Controller;

use think\App;
use think\Request;
use think\Response;
use turingAdmin\model\TraitModel;
use turingAdmin\Service\JsonService;


abstract class BaseController
{

    /**
     * 请求Request实例
     * @var \think\Request
     */
    protected Request $request;

    /**
     * 控制器中间件
     * @var array
     */
    protected array $middleware = [];

    public function __construct(protected App $app)
    {
        $this->request  =   $this->app->request;

        $this->initialize();
    }

    public function __destruct( )
    {

    }

    // 初始化加载
    protected function initialize() : void
    {}


    public function success( string $message = 'SUCCESS' , array | object $data = [] , int $code = 1 , int $show = 0 ) : Response
    {
//        if(is_object($data)) $data = $data->toArray();

        return JsonService::success($message , $data , $code , $show);
    }

    public function fail( string $message = 'SUCCESS' , ?array $data = [] , int $code = 1 , int $show = 0 ) : Response
    {
        return JsonService::fail($message , $data , $code , $show);
    }
}