<?php

namespace inblank\showroom\components;

use Yii;
use inblank\showroom\traits\CommonTrait;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;

/**
 * Class BackendController
 * @package inblank\showroom\components
 *
 * @property \inblank\showroom\Module $module
 */
class BackendController extends Controller{

    use CommonTrait;

    public function init(){
        parent::init();
        $this->getView()->params['frontendUrlManager'] = $this->module->frontendUrlManager;
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['adminAccess'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function checkPermission($permission, $params = []){
        if(Yii::$app->authManager) {
            if (!Yii::$app->user->can($permission, $params)) {
                throw new ForbiddenHttpException(Yii::t('showroom_backend', "You can't perform this action"));
            }
        }
    }
}
