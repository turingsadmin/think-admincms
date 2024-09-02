# 说明

# 控制器操作
## 说明
组件中自带BaseControlle 控制器继承模块
### 使用方式
> use turingAdmins\app\controller\BaseController;
~~~PHP
<?php
declare (strict_types = 1);
use turingAdmins\app\controller\BaseController;
class Index extends BaseController
{
    # 你的方法
}
~~~

## 注入操作类
注入类Mapper 可以快速帮助执行 增删改查的操作 如果不需要可以不用注入此类
### 使用方式
~~~PHP
<?php
declare (strict_types = 1);
use turingAdmins\app\controller\BaseController;
use app\admin\mapper\IndexMapper;
use turingAdmins\annotation\Inject;
class Index extends BaseController
{
    #[Inject]
    protected IndexMapper $mapper;
    # 你的方法
}
~~~
### 注入操作说明
> 当控制器使用注入类后
> 
> 需要在IndexMapper 中 继承 BaseMapper

在映射器中 再注入你的Model 模型 ： 如下
~~~php
<?php

namespace app\admin\mapper;

use app\admin\model\User;
use turingAdmins\annotation\Inject;
use turingAdmins\app\mapper\BaseMapper;

class IndexMapper extends BaseMapper
{

    #[Inject]
    public User $model;

}
~~~

映射器自带方法可以在控制器中 使用 $this->mapper 中调用 ：

#### 列表 getPageLists( ?array $query = [], array $order = [])
> public function getPageList( ? array $query = [] , ? array $order = []) : mixed

控制器调用
$query 中包含你的get参描搜索等内容

$order 中包含排序条件 
~~~php
public function lists() : Json
{
    return $this->success( $this->mapper->getPageList( $this->require->param()  , ['id' => 'DESC']) )
}
~~~

#### 从删除中读取可恢复内容   getPageListByRecycle
> public function getPageListByRecycle( ?array $query , ?array $order = []) : mixed

$query 包含你要查询的条件

$order 包含排序条件


当你的模型中存在 
use SoftDelete;
软删除的引入时要查询删除后的内容可以使用 getPageListByRecycle
~~~php
public function recycle() : Json
{
    return  $this->success( $this->mapper->getPageListByRecycle( $this->request->param() , ['delete_time' =>'DESC']));
}
~~~

#### 新增save
>  public function save(array | object $data = [], string $sequence = null): bool

#### 更新 update
>  public function update(int $id , array $data = []): bool

#### 删除 delete
>  public function delete(int $id ): bool

如果 不存在SoftDelete 此删除为真删除 否则就为软删除

#### 真删除 realDelete
>  public function realDelete(int $id ): bool

不论模型中是否存在不存在SoftDelete 此删除都为真删除

#### 恢复删除内容 recovery
> public function recovery( int $id) : bool

当你的模型中存在
use SoftDelete;
此方法生效


# 注解路由工具
use turingAdmins\annotation\route\PutMapper; <br>
use turingAdmins\annotation\route\PostMapper;<br>
use turingAdmins\annotation\route\GetMapper;<br>
use turingAdmins\annotation\route\DeleteMapper;<br>
use turingAdmins\annotation\route\RouteMapper;<br>
use turingAdmins\annotation\route\GroupController; <br>


~~~php
<?php
namespace app\controller;

use turingAdmins\annotation\route\RouteMapper;

class Index
{
    /**
     * @param string $name 数据名称
     * @return mixed
     */
    [#RouteMapper("GET", "hello/:name", ["https"=>1, "ext"=>"html"])]
    public function hello($name)
    {
    	return 'hello,'.$name;
    }
}
~~~