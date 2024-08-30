<?php

namespace turingAdmins\app\validate;

use think\exception\ValidateException;
use think\Validate;

class BaseValidate extends Validate
{



    public function get() : mixed
    {
        return $this;
    }

    public function post() : mixed
    {
        return $this;
    }

    public function put() : mixed
    {
        return $this;
    }

    public function delete() : mixed
    {
        return $this;
    }


    public function goCheck( ?array $data): ?array
    {
        $validate = $this->check($data);
        if(!$validate){
            throw new ValidateException($this->getError());
        }

        return $data;
    }

}