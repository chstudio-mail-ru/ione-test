<?php
/* @var $dp yii\data\ActiveDataProvider */

echo  \yii\widgets\ListView::widget([
    'dataProvider' => $dp,
    'itemView' => '_car_item',
    'layout' => "{items}\n{pager}",
    'emptyText' => 'Список пуст',
    'pager' => [
        'nextPageLabel' => '>',
        'prevPageLabel' => '<',
        'maxButtonCount' => 5,
    ],
]);