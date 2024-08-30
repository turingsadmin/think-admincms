<?php

namespace turingAdmins\app\model;

class BaseModel extends \think\Model
{


    public function getPageList()
    {
        $page       =   Request()->get('page' , 1);
        $pageSize   =   Request()->get('page_size' , 15);
        return $this->page( ( $page - 1) * $pageSize , $pageSize );
    }
}