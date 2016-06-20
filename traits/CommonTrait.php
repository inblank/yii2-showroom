<?php

namespace inblank\showroom\traits;

use Yii;
use yii\base\InvalidConfigException;

trait CommonTrait{

    static protected $_module;

    static function getModule(){
        if(self::$_module===null){
            if(empty(Yii::$app->modules['showroom'])) {
                throw new InvalidConfigException('You must configure module as `showroom`');
            }
            self::$_module = Yii::$app->getModule('showroom');
        }
        return self::$_module;
    }

    /**
     * Models dependency injection resolver
     * @param string $name class name for resolve
     * @return mixed
     * @throws InvalidConfigException
     */
    public static function di($name)
    {
        $class = 'inblank\showroom\models\\' . $name;
        return empty(self::getModule()->modelMap[$name]) ? $class : self::getModule()->modelMap[$name];
    }

}
