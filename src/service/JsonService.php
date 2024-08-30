<?php
namespace turingAdmins\service;

use think\App;
use think\Request;
use think\response\{Json , Jsonp};
trait  JsonService
{

    protected Request $request;
    public function __construct( protected App $app)
    {
        $this->request = $this->app->request;
    }

    public function success(
        mixed $message = 'SUCCESS',
        array | object  $data    =   [],
        int    $code    =   1,
        int    $show    =   0
    ) : Json
    {
        # 如果message 是数组
        if (is_array($message))
        {
            return $this->reslut( data:$message ,code: $code , show: $show);
        } else if (is_object($message) )
        {

            return $this->reslut(data:[
                'lists' =>  $message->select(),
                'page'  =>  $this->request->get('page' , 1),
                'count' =>  $message->count(),
                'page_size' =>  $this->request->get('page_size' , 15)
            ] , code:$code , show:$show);
        } else if(is_object($data))
        {
            halt(1);
        }
       return $this->reslut( $message , $data , $code , $show );
    }


    protected function reslut(
        string $message = 'SUCCESS',
        ?array $data    = [] ,
        int     $code   =   1,
        int     $show   =   0

    ) : Json | Jsonp
    {

        $result = [
            'code'      =>  1,
            'message'   =>  $message ,
            'data'      =>  $data,
            'show'      =>  $show
        ];
        return response(type: 'json')->data($result)->code(200);
    }
}