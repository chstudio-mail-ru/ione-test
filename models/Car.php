<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "model".
 *
 * @property integer $id
 * @property integer $mark_id
 * @property integer $model_id
 * @property integer $engine_id
 * @property integer $drive_id
 * @property integer $price
 * @property integer $discount
 * @property integer $date_add
 */
class Car extends ActiveRecord
{
    public static function tableName(){
        return 'car';
    }

    public function rules(){
        return [
            [['mark_id', 'model_id', 'engine_id', 'drive_id'], 'required'],
            [['mark_id', 'model_id', 'engine_id', 'drive_id', 'price', 'discount', 'date_add'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mark_id' => 'Mark ID',
            'model_id' => 'Model ID',
            'engine_id' => 'Engine ID',
            'drive_id' => 'Drive ID',
            'price' => 'Price',
            'discount' => 'Discount',
            'date_add' => 'date add',
        ];
    }

    public static function getCarById($id)
    {
        return self::find()->where(['id' => $id])->one();
    }

    public function getMark() {
        return $this->hasOne(CarMark::className(), ['id' => 'mark_id']);
    }

    public function getModel() {
        return $this->hasOne(CarModel::className(), ['id' => 'model_id']);
    }

    public function getEngine() {
        return $this->hasOne(Engine::className(), ['id' => 'engine_id']);
    }

    public function getDrive() {
        return $this->hasOne(Drive::className(), ['id' => 'drive_id']);
    }

    private static function getCarsQueryByParamsIds($mark_id, $model_id, $engine_id = 0, $drive_id = 0)
    {
        $query = self::find();

        if ($mark_id > 0) {
            $query->andWhere(['mark_id' => $mark_id]);
        }
        if ($model_id > 0) {
            $query->andWhere(['model_id' => $model_id]);
        }
        if ($engine_id > 0) {
            $query->andWhere(['engine_id' => $engine_id]);
        }
        if ($drive_id > 0) {
            $query->andWhere(['engine_id' => $drive_id]);
        }

        return $query;
    }

    private static function getCarsQueryByParamsNames($mark, $model, $engine = "", $drive = "")
    {
        $query = self::find()->joinWith(['mark', 'model', 'engine', 'drive']);

        if ($mark) {
            $query->andWhere(['mark.param_name' => $mark]);
        }
        if ($model) {
            $query->andWhere(['model.param_name' => $model]);
        }
        if ($engine) {
            $query->andWhere(['engine.param_name' => $engine]);
        }
        if ($drive) {
            $query->andWhere(['drive.param_name' => $drive]);
        }

        return $query;
    }

    public static function getCarsDataProviderByParamsNames($mark, $model, $engine, $drive)
    {
        $onPage = 10;
        $limit = [1, 50];

        $query = self::getCarsQueryByParamsNames($mark, $model, $engine, $drive);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'defaultPageSize' => $onPage,
                'pageSizeParam' => 'onPage',
                'pageSizeLimit' => $limit,
                'forcePageParam' => false,
                'pageParam' => 'page',
            ],
        ]);

        return $dataProvider;
    }

    public static function getTitleByParamsNames($mark, $model)
    {
        $mark_obj = CarMark::getByParamName($mark);
        if ($mark_obj) {
            $model_obj = CarModel::getByParamNameAndMarkId($model, $mark_obj->id);
        } else {
            $model_obj = false;
        }

        if ($mark_obj && $model_obj) {
            return "Продажа новых автомобилей {$mark_obj->name} {$model_obj->name} в Санкт-Петербурге";
        } elseif ($mark_obj) {
            return "Продажа новых автомобилей {$mark_obj->name} в Санкт-Петербурге";
        } else {
            return "Продажа новых автомобилей в Санкт-Петербурге";
        }
    }

    public static function getDescriptionByParamsNames($mark, $model)
    {
        $mark_obj = CarMark::getByParamName($mark);
        if ($mark_obj) {
            $model_obj = CarModel::getByParamNameAndMarkId($model, $mark_obj->id);
        } else {
            $model_obj = false;
        }

        //тут изменить description, чтобы не совпадало с title (для SEO)
        if ($mark_obj && $model_obj) {
            return "Продажа новых автомобилей {$mark_obj->name} {$model_obj->name} в Санкт-Петербурге";
        } elseif ($mark_obj) {
            return "Продажа новых автомобилей {$mark_obj->name} в Санкт-Петербурге";
        } else {
            return "Продажа новых автомобилей в Санкт-Петербурге";
        }
    }

    public static function addRecord($mark_id, $model_id, $engine_id, $drive_id, $price = 0, $discount = 0)
    {
        $obj = new self;
        $obj->mark_id = $mark_id;
        $obj->model_id = $model_id;
        $obj->engine_id = $engine_id;
        $obj->drive_id = $drive_id;
        $obj->price = $price;
        $obj->discount = $discount;
        $obj->date_add = time();
        $obj->save();

        return $obj;
    }
}
