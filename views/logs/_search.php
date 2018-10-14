<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\daterange\DateRangePicker;
/* @var $this yii\web\View */
/* @var $model app\models\LogsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="logs-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'data_time')->widget('kartik\daterange\DateRangePicker',[
    'convertFormat'=>true,
    'attribute' => 'data_time',
    'startAttribute'=>'datetime_min',
    'endAttribute'=>'datetime_max',
    'hideInput' => true,
    'pluginOptions'=>[
        'timePicker'=>true,
        'timePickerIncrement'=>10,
        'locale'=>[
            'format'=>'Y-m-d H:i:s',
            'autoclose' => true
        ]
    ]
]) ?>

    <?php // echo $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'code') ?>

    <div class="form-group">
        <?= Html::submitButton('Поиск', ['class' => 'btn btn-primary']) ?>
        <?php // Html::resetButton('Сбросить', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
