<?php
/**
 * Created by PhpStorm.
 * User: zhanghongbo
 * Date: 2019/3/25
 * Time: 上午10:11
 */

namespace yii\web;


use yii\base\Component;
use Yii;

class UrlManager extends Component
{
    public $enablePrettyUrl = true;//美化URL 如果开启不url里的前缀和后缀

    public $enableStrictParsing = false;

    public $rules = [];

    public $suffix;

    public $routeParam = 'r';


    //解析请求
    public function parseRequest($request)
    {
        if($this->enablePrettyUrl){
            $pathInfo = $request->getPathInfo();
            //url规则
            foreach($this->rules as $rule){
                if(($result = $rule->parseRequest($this,$request)) !== false){//怎么进到这里执行的？
                    return $result;
                }
            }
            if($this->enableStrictParsing){
                return false;
            }

            //从$pathInfo末端2位开始找//，2个字符匹配
            if(strlen($pathInfo) > 1 && substr_compare($pathInfo,'//',-2,2) === 0){
                return false;//不能有//
            }

            $suffix = (string)$this->suffix;
            if($suffix !== '' && $pathInfo !== ''){
                $n = strlen($this->suffix);
                if(substr_compare($pathInfo,$this->suffix,-$n,$n) === 0){
                    $pathInfo = substr($pathInfo,0,-$n);
                    if($pathInfo === ''){
                        return false;
                    }
                } else {
                    return false;
                }
            }
            return [$pathInfo,[]];
        } else {
            $route = $request->getQueryParam($this->routeParam,'');
            if(is_array($route)){
                $route = '';
            }
            return [(string) $route,[]];
        }
    }
}