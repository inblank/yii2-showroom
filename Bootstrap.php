<?php

namespace inblank\showroom;

use yii;
use yii\base\Application;
use yii\console\Application as ConsoleApplication;
use yii\i18n\PhpMessageSource;
use yii\web\GroupUrlRule;

class Bootstrap implements yii\base\BootstrapInterface{

    /** @var array Model's map */
    private $_modelMap = [
    ];

    /**
     * Bootstrap method to be called during application bootstrap stage.
     * @param Application $app the application currently running
     * @throws yii\base\InvalidConfigException
     */
    public function bootstrap($app)
    {
        if (!isset($app->get('i18n')->translations['showroom*'])) {
            $app->get('i18n')->translations['showroom*'] = [
                'class'    => PhpMessageSource::className(),
                'basePath' => __DIR__ . '/messages',
            ];
        }
        /** @var Module $module */
        /** @var \yii\db\ActiveRecord $modelName */
        if ($app->hasModule('showroom') && ($module = $app->getModule('showroom')) instanceof Module) {
            $this->_modelMap = array_merge($this->_modelMap, $module->modelMap);
            if(!array_key_exists('User', $this->_modelMap)){
                throw new yii\base\InvalidConfigException(Yii::t('showroom_general', 'You must specify the User class in the models map of module'));
            }
            foreach ($this->_modelMap as $name => $definition) {
                $class = "inblank\\showroom\\models\\" . $name;
                Yii::$container->set($class, $definition);
                $modelName = is_array($definition) ? $definition['class'] : $definition;
                $module->modelMap[$name] = $modelName;
            }
            if ($app instanceof ConsoleApplication) {
                $module->controllerNamespace = 'inblank\showroom\commands';
            } else {
                $configUrlRule = [
                    'prefix' => $module->urlPrefix,
                    'rules'  => defined('IS_BACKEND') ? $module->urlRulesBackend : $module->urlRulesFrontend,
                ];

                if ($module->urlPrefix != 'showroom') {
                    $configUrlRule['routePrefix'] = 'showroom';
                }

                $app->urlManager->addRules([new GroupUrlRule($configUrlRule)], false);

                if(defined('IS_BACKEND')){
                    // is backend, and controller have other namespace
                    $module->controllerNamespace = 'inblank\showroom\controllers\backend';
                    $module->frontendUrlManager = new yii\web\UrlManager([
                        'baseUrl'=>'/',
                        'enablePrettyUrl' => true,
                        'showScriptName' => false,
                    ]);
                    $configUrlRule['rules'] = $module->urlRulesFrontend;
                    $module->frontendUrlManager->addRules([new GroupUrlRule($configUrlRule)], false);
                }
            }
        }
    }
}
