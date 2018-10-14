<?php

namespace app\controllers;

use Yii;
use app\models\Logs;
use app\models\LogsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * LogsController implements the CRUD actions for Logs model.
 */
class LogsController extends Controller
{
    /**
     * {@inheritdoc}
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
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index','update','delete','create','api','test','view',],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                    [
                        'actions' => ['index','create','api','test','view',],
                        'allow' => true,
                        'roles' => ['user'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Logs models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LogsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Logs model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Logs model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Logs();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Logs model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Logs model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Logs model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Logs the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Logs::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionTest(){
        var_dump(Logs::writeLogsInArray('C:\OSPanel\userdata\logs\Apache-PHP-7.2-x64_queriesa.log'));
    }

    public function actionApi(
        $ip = null, 
        $data_time = null, 
        $code = null, 
        $data_time_min = null, 
        $data_time_max = null,
        $type = null,
        $order_column = null,
        $order_condition = null
        )
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        //Агрегация переданных GET параметров
        $requestArray = [
            'ip' => $ip,
            'data_time' => $data_time,
            'code' => $code,
            'type' => $type
        ];
        //Массив который формируется для запроса на основание его API выдает ответ
        $responseArray = [];
        //Формируется массив для запроса к бд
        foreach($requestArray as $key => $value){
            if($value != null){
                $responseArray[$key] = $value;
            }
        }
        $response = Logs::find()
        ->where($responseArray);

        //Если только min граница или только min и дата в неполном формате например 2018-10-10
        if($data_time_min != null && $data_time_max == null){
            if(strlen($data_time_min) < 19)
                $response->andWhere(['like', 'data_time',$data_time_min])
                ->all();
            else
                $response
                ->andWhere(['>=', 'data_time',$data_time_min]);
        }

        //Если только max граница или только max и дата в неполном формате например 2018-10-10
        if($data_time_max != null && $data_time_min == null){
            if(strlen($data_time_max) < 19)
                $response
                ->andWhere(['like', 'data_time',$data_time_max]);
            else
                $response
                ->andWhere(['<=', 'data_time',$data_time_max]);
        }

        //Если стоят строиге границы min и max
        if($data_time_min != null && $data_time_max != null)
            $response
            ->andWhere(['>=', 'data_time',$data_time_min])
            ->andWhere(['<=', 'data_time',$data_time_max]);
        
        //Сортировка
        if($order_column != null && $order_condition != null){
            $response->orderBy([$order_column => 'SORT_' . strtoupper($order_condition)]);
        }
        
        return $response->all();   
    }
}
