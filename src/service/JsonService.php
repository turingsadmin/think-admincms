<?php

namespace turingAdmin\service;

use think\Response;
class JsonService
{

    public static function success(string $message = 'success' , array | object $data = [] , int $code = 1 , int $show = 0) : Response
    {
        return self::result($message , $data , $code , $show);
    }

    public static function fail( string $message = 'fail' , array | object $data = [] , int $code = 0 , int $show = 0) :Response
    {
        return  self::result($message , $data , $code , $show);
    }


    protected static function result(string $message , array | object $data = [] , int $code = 0 , int $show = 0 , int $httpCode = 200)
    {
        $data  = compact('code' , 'data' , 'message' , 'show');
        return json($data, $httpCode);
    }
}