<?php
namespace turingAdmins\service;

use think\App;
use think\Request;
use think\response\{Json , Jsonp};
use think\facade\Db;
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

       return $this->reslut( $message , $data , $code , $show );
    }


    public function fail(
        mixed $message = 'ERROR',
        array | object  $data    =   [],
        int    $code    =   1,
        int    $show    =   0
    ) : Json
    {
        return  $this->reslut($message , $data , $code , $show);
    }


    protected function reslut(
        mixed $message = 'SUCCESS',
        mixed $data    = [] ,
        int     $code   =   1,
        int     $show   =   0

    ) : Json | Jsonp
    {


        if(is_object($message))
        {
            $data = $message;
            $message = $code == 1 ? 'SUCCESS' : 'ERROR';
        }



        $result = [
            'code'  =>  $code,
            'show'  =>  $show,
            'message'   =>  $message
        ];


        $array = is_object($data) ? $data->toArray() : $data;


        function isArrayOneDimensional($array) {
            // 检查数组是否为空
            if (empty($array)) {
                return true; // 空数组可以视为“一维”的，但实际情况可能需要根据需求调整
            }

            foreach ($array as $element) {
                // 如果数组中的任何元素是数组，则它不是一维的
                if (is_array($element)) {
                    return false;
                }
            }

            // 如果所有元素都不是数组，则它是一维的
            return true;
        }

        if (isArrayOneDimensional($array))
        {
            $message  = $code == 1 ? 'SUCCESS' : 'ERROR';
            $result['data'] = $array;
        }else{
            $result['data']  = [
                'list'  =>  $array,
                'count' => $data->count(),
                'page'  =>  $this->request->get('page' ,1),
                'page_size' =>  $this->request->get('page_size' , 15),
            ];
        }

        return response(type: 'json')->data($result)->code(200);
    }
}