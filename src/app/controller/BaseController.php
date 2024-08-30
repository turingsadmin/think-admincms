<?php
namespace turingAdmins\app\controller;
use think\App;
use think\Request;
use turingAdmins\service\JsonService;

abstract class BaseController
{
    use JsonService;

    protected Request $request;

    /**
     * 控制器中间件
     * @var array
     */
    protected array $middleware = [];

    /**
     * 是否批量验证
     * @var bool
     */
    protected $batchValidate  = false;

    public function __construct( protected App $app)
    {
        $this->request  = $this->app->request;
    }
}