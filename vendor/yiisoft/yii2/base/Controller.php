<?php
/**
 * Created by PhpStorm.
 * User: zhanghongbo
 * Date: 2019/3/25
 * Time: 下午8:11
 */

namespace yii\base;


class Controller extends Component implements ViewContextInterface
{
    const EVENT_BEFORE_ACTION = 'beforeAction';

    const EVENT_AFTER_ACTION = 'afterAction';

    private $_viewPath;

    public $module;

    public $id;

    public $defaultAction = 'index';

    public $action;


    public function __construct($id,$module,array $config = [])
    {
        $this->id = $id;
        $this->module = $module;
        parent::__construct($config);
    }

    public function actions()
    {

    }

    public function getViewPath()
    {
        if($this->_viewPath === null){
            $this->_viewPath = $this->module->getViewPath() . DIRECTORY_SEPARATOR . $this->id;
        }
        return $this->_viewPath;
    }

    public function runAction($id,$params = [])
    {
        $action = $this->createAction($id);
        if($action === null){

        }

        if(\Yii::$app->requestedAction === null){
            \Yii::$app->requestedAction = $action;
        }

        $oladAction = $this->action;
        $this->action = $action;
        $modules = [];
        $runAction = true;
        foreach($this->getModules() as $module){
            if($module->beforeAction($action)){
                array_unshift($modules,$module);
            } else {
                $runAction = false;
                break;
            }
        }
        $result = null;
        if($runAction && $this->beforeAction($action)){
            $result = $action->runWithParams($params);
            $result = $this->afterAction($action,$result);
            foreach($modules as $module){
                $result = $module->afterAction($action,$result);
            }
        }
        $this->action = $oladAction;
        return $result;
    }

    public function beforeAction($action)
    {
        $event = new ActionEvent($action);
        $this->trigger(self::EVENT_BEFORE_ACTION,$event);
        return $event->isValid;
    }

    public function afterAction($action,$result)
    {
        $event = new ActionEvent($action);
        $event->result = $result;
        $this->trigger(self::EVENT_AFTER_ACTION,$event);
        return $event->result;
    }

    public function getModules()
    {
        $modules = [$this->module];
        $module = $this->module;
        while($module->module !== null){
            array_unshift($modules,$module->module);
            $module = $module->module;
        }
        return $modules;
    }

    //创建控制器方法
    public function createAction($id)
    {
        if($id === ''){
            $id = $this->defaultAction;
        }

        $actionMap = $this->actions();
        if(isset($actionMap[$id])){
            return \Yii::createObject($actionMap[$id],[$id,$this]);
        } elseif(preg_match('/^[a-z0-9\\-_]+$/',$id) && strpos($id,'--') === false && trim($id,'-') === $id) {
            $methodName = 'action' . str_replace(' ','',ucwords(implode(' ',explode('-',$id))));
            if(method_exists($this,$methodName)){
                $method = new \ReflectionMethod($this,$methodName);
                if($method->isPublic() && $method->getName() === $methodName){
                    return new InlineAction($id,$this,$methodName);
                }
            }
        }
        return null;
    }

    public function bindActionParams()
    {
        return [];
    }
}