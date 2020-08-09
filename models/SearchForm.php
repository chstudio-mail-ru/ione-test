<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * SearchForm is the model behind the Search form.
 *
 */
class SearchForm extends Model
{
    public $mark;
    public $mark_id;
    public $model;
    public $model_id;
    public $drive;
    public $drive_id;
    public $engine;
    public $engine_id;

    public function __construct($config = [])
    {
        parent::__construct($config);

        if (isset($config['mark'])) {
            $this->mark = $config['mark'];
            $mark_obj = CarMark::getByParamName($this->mark);
            $this->mark_id = ($mark_obj)? $mark_obj->id : null;
        }
        if (isset($config['model'])) {
            $this->model = $config['model'];
            $model_obj = CarModel::getByParamNameAndMarkId($this->model, $this->mark_id);
            $this->model_id = ($model_obj)? $model_obj->id : null;
        }
        if (isset($config['drive'])) {
            $this->drive = $config['drive'];
            $drive_obj = Drive::getByParamName($this->drive);
            $this->drive_id = ($drive_obj)? $drive_obj->id : null;
        }
        if (isset($config['engine'])) {
            $this->engine = $config['engine'];
            $engine_obj = Engine::getByParamName($this->engine);
            $this->engine_id = ($engine_obj)? $engine_obj->id : null;
        }
    }
}