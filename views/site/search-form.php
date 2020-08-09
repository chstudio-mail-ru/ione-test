<?php
/* @var $search_form app\models\SearchForm */

use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

$marks = \app\models\CarMark::getAll();
$models = \app\models\CarModel::getAll($search_form->mark_id);
$drives = \app\models\Drive::getAll();
$engines = \app\models\Engine::getAll();
$params_mark = [
    'options' => [
        ((!empty($search_form->mark_id)) ? $search_form->mark_id : '') => ['Selected'=> true],
    ],
];
$params_model = [
    'options' => [
        ((!empty($search_form->model_id)) ? $search_form->model_id : '') => ['Selected'=> true],
    ],
];
$params_drive = [
    'options' => [
        ((!empty($search_form->drive_id)) ? $search_form->drive_id : '') => ['Selected'=> true],
    ],
];
$params_engine = [
    'options' => [
        ((!empty($search_form->engine_id)) ? $search_form->engine_id : '') => ['Selected'=> true],
    ],
];

$form = ActiveForm::begin([
    'id'      => 'search-form',
    'method'  => 'post',
    /*'options' => [
        'data' => [
            'pjax' => true
        ],
    ],*/
]);

echo $form->field($search_form, 'mark')->dropDownList(
    ['' => 'Марка'] + ArrayHelper::map($marks, 'param_name', 'name'), $params_mark
)->label('Марка');
echo $form->field($search_form, 'model')->dropDownList(
    ['' => 'Модель'] + ArrayHelper::map($models, 'param_name', 'name'), $params_model
)->label('Модель');
echo $form->field($search_form, 'engine')->dropDownList(
    ['' => 'Все типы двигателя'] + ArrayHelper::map($engines, 'param_name', 'name'), $params_engine
)->label('Тип двигателя');
echo $form->field($search_form, 'drive')->dropDownList(
    ['' => 'Все типы привода'] + ArrayHelper::map($drives, 'param_name', 'name'), $params_drive
)->label('Тип привода');

ActiveForm::end();

