<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\LogsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
if(Yii::$app->authManager->getRolesByUser(Yii::$app->user->identity->id)['admin'])
    $template = '{view} {update} {delete}';
else
    $template = '{view}';


$this->title = 'Логи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="logs-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php 
        
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            //'id',
            'ip',
            'data_time',
            'req',
            'res',
            'type',
            'code',

            [   'class' => 'yii\grid\ActionColumn',
                'template' => $template
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
