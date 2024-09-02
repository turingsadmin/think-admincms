<?php

namespace turingAdmins\app\validate;

use think\exception\ValidateException;
use think\Validate;

class BaseValidate extends Validate
{

    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';

    protected String $method = 'GET';

    public function get() : mixed
    {
        if(!$this->request->isGet())
        {
            throw new ValidateException('请求方式错误:请使用GET方法传递');
        }
        $this->method = 'GET';
        return $this;
    }

    public function post() : mixed
    {
        if(!$this->request->isPost())
        {
            throw new ValidateException('请求方式错误:请使用POST方法传递');
        }
        $this->method = 'POST';

        return $this;
    }

    public function put() : mixed
    {
        if(!$this->request->isPut())
        {
            throw new ValidateException('请求方式错误:请使用PUT方法传递');
        }
        $this->method = 'PUT';

        return $this;
    }

    public function delete() : mixed
    {
        if(!$this->request->isDelete())
        {
            throw new ValidateException('请求方式错误:请使用DELETE方法传递');
        }
        $this->method = 'DELETE';

        return $this;
    }


    public function goCheck( ?array $data = []): ?array
    {
        if($this->request->method() !== $this->method)
        {
            throw new ValidateException("请求方式错误:请使用{$this->method}方法传递！(或者未规定请求方式)");
        }
        if (empty($data))
        {
            switch ($this->method)
            {
                case self::METHOD_GET:
                case self::METHOD_DELETE:
                case self::METHOD_PUT:
                    $data = $this->request->param();
                    break;
                case self::METHOD_POST:
                    $data = $this->request->post();
                    break;
            }
        }

        $validate = $this->check($data);
        if(!$validate){
            throw new ValidateException($this->getError());
        }

        return $data;
    }

}