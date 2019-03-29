<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2019/3/23
 * Time: 下午2:28
 */

namespace yii\web;

use yii\base\InvalidRouteException;

class Application extends \yii\base\Application
{
    public $catchAll;

    public $requestedRoute;

    public $controller;

    public function handleRequest($request)
    {
        if(empty($this->catchAll)){//数组第一个是操作路径，后面的是参数。
            list($route,$params) = $request->resolve();//解析请求的路由和参数
        } else {
            $route = $this->catchAll[0];
            $params = $this->catchAll;
            unset($params[0]);
        }
        try {
            $this->requestedRoute = $route;
            $result = $this->runAction($route,$params);
            if($result instanceof \yii\web\Response){
                return $result;
            } else {
                $response = $this->getResponse();
                if($response !== null){
                    $response->data = $result;
                }
                return $response;
            }
        } catch (InvalidRouteException $e) {
            throw new NotFoundHttpException(404);
            // TODO 123;
        }
    }

    //核心组件
    public function coreComponents()
    {
        return array_merge(parent::coreComponents(),[
            'request' => ['class' => 'yii\web\Request'],
            'response' => ['class' => 'yii\web\Response'],
        ]);
    }

    public function getRequest()
    {
        return $this->get('request');
    }

    public function getResponse()
    {
        return $this->get('response');
    }
}
