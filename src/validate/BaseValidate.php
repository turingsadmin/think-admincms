<?php

namespace turingAdmin\validate;

use think\exception\ValidateException;
use think\Validate;

class BaseValidate extends Validate
{

    public function post()
    {
        return $this;
    }

    public function get()
    {
        return $this;
    }

    public function delete()
    {
        return $this;
    }

    public function put()
    {
        return $this;
    }

    public function goCheck( ?array $data)
    {
        $validate = $this->check($data);
        if(!$validate) throw new ValidateException($this->error);
        return $data;
    }
}