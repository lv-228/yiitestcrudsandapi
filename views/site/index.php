<?php

/* @var $this yii\web\View */
use yii\helpers\Url;
use yii\helpers\Html;
$this->title = 'Логи Apache';
if(Yii::$app->user->isGuest)
    $link = Html::a("Авторизируйтесь для получения доступа!",['site/login'],['class'=>"btn btn-lg btn-danger"]);
else
    $link = Html::a("Просмотреть логи",['logs/index'],['class'=>"btn btn-lg btn-success"]);
?>
<div class="site-index">

    <div class="jumbotron">
    <?php if(Yii::$app->user->isGuest): ?>
        <h3>Пожалуйста авторизируйтесь для дальнейших действий<br>(log:admin pas:admin, log:user pas:user)</h3>
        <p><?= Html::a("Авторизация",['/site/login'],['class' => 'btn btn-danger']) ?></p>
    <?php endif; ?>
    <?php if(!Yii::$app->user->isGuest): ?>
        <h1>Вы успешно авторизированны с правами: 
        <?php
        $getRole = Yii::$app->authManager->getRolesByUser(Yii::$app->user->identity->id);
        $key = array_keys($getRole); 
        echo $getRole[$key[0]]->description;
        ?></h1>
    <?php endif; ?>
    </div>

    <div class="body-content">
    <?php if(!Yii::$app->user->isGuest): ?>
    <div class="row">
            <div class="col-lg-4">
                <h2>Логи</h2>

                <p><?= Html::a("Логи",['/logs/index'],['class' => 'btn btn-warning']) ?></p>

                <p>Просмотреть логи Apache можно здесь</p>

                
            </div>
            <div class="col-lg-4">
                <h2>Тест записи логов</h2>

                <p><?= Html::a("Тест запись логов",['/logs/test'],['class' => 'btn btn-warning']) ?></p>

                <p>Предварительный просмотор массива который будет записан в БД</p>

                
            </div>
            <div class="col-lg-4">
                <h2>API</h2>
                <p><?= Html::a("API",['/logs/test'],['class' => 'btn btn-warning']) ?></p>
                <p style='white-space:pre-wrap'>Просмотр API имеются возможные GET параметры: 
[$ip - ip с которого пришел запрос]

[$data_time - точная дата и время в php формате 
Y-m-d H:i:s]

[$code - код ответа сервера (например 200)]

[$data_time_min - минимальная граница для фильтрации по дате, может быть не полной (допустим 2018-10-10)]

[$data_time_max - максимальная граница для фильтрации по дате, может быть не полной (допустим 2018-10-10)]

[$type - Тип запроса (GET, POST и т.д.)]

[$order_column - Сортировать по данному столбцу]

[$order_condition - Правило сортировки (desc - по убыванию asc - по возростанию)]

[Пример GET массива: 
?data_time_min=11:27:49&data_time_max=2018-10-25 11:30&code=404&order_column=data_time&order_condition=desc]
                </p>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
