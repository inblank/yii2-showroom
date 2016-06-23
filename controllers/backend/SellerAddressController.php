<?php

namespace inblank\showroom\controllers\backend;

use inblank\showroom\components\BackendController;
use inblank\showroom\models\Seller;
use inblank\showroom\models\SellerAddress;
use inblank\showroom\models\SellerAddressSearch;
use Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

/**
 * SellerAddressController implements the CRUD actions for SellerAddress model.
 */
class SellerAddressController extends BackendController
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
     * Lists all SellerAddress models.
     * @param int $seller_id seller identifier
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionList($seller_id)
    {
        $seller = $this->findSeller($seller_id);

        $searchModel = new SellerAddressSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andFilterWhere([
            'seller_id' => $seller_id,
        ]);
        return $this->render('list', [
            'seller' => $seller,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SellerAddress model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $seller = $model->seller;
        return $this->render('view', [
            'model' => $model,
            'seller' => $seller,
        ]);
    }

    /**
     * Creates a new SellerAddress model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param int $seller_id seller identifier
     * @return mixed
     */
    public function actionCreate($seller_id)
    {
        $seller = $this->findSeller($seller_id);
        $model = new SellerAddress([
            'seller_id' => $seller->id
        ]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'seller' => $seller,
            ]);
        }
    }

    /**
     * Updates an existing SellerAddress model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $seller = $model->seller;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'seller' => $seller,
            ]);
        }
    }

    /**
     * Deletes an existing SellerAddress model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $seller_id = $model->seller_id;
        $model->delete();

        return $this->redirect(['list', 'seller_id' => $seller_id]);
    }

    /**
     * Finds the SellerAddress model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SellerAddress the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SellerAddress::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Finds the Seller model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Seller the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findSeller($id)
    {
        if (($model = Seller::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Seller does not exist.');
        }
    }

}
