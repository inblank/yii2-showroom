<?php

namespace inblank\showroom\controllers\backend;

use inblank\showroom\components\BackendController;
use inblank\showroom\models\Seller;
use inblank\showroom\models\SellerSearch;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

/**
 * SellerController implements the CRUD actions for Seller model.
 */
class SellerController extends BackendController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Seller models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SellerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Seller model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Seller model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        /** @var Seller $model */
        $model = Yii::createObject($this->di('Seller'));

        $post = Yii::$app->request->post();
        if ($model->load($post) && $model->save() && $model->profile->load($post) && $model->profile->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }
        $usersList = ArrayHelper::map(
            Yii::createObject('inblank\showroom\models\User')->find()->all(),
            'id',
            function($m){ return "{$m['name']} ({$m['id']})";}
        );
        return $this->render('create', [
            'model' => $model,
            'usersList' => $usersList
        ]);
    }

    /**
     * Updates an existing Seller model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $post = Yii::$app->request->post();
        if ($model->load($post) && $model->save() && $model->profile->load($post) && $model->profile->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }
        $usersList = ArrayHelper::map(
            Yii::createObject('inblank\showroom\models\User')->find()->all(),
            'id',
            function($m){ return "{$m['name']} ({$m['id']})";}
        );
        return $this->render('update', [
            'model' => $model,
            'usersList'=>$usersList,
        ]);
    }

    /**
     * Deletes an existing Seller model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Seller model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Seller the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Seller::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
