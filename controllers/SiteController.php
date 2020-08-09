<?php

namespace app\controllers;

use app\models\Car;
use app\models\CarMark;
use app\models\CarModel;
use app\models\Drive;
use app\models\Engine;
use app\models\SearchForm;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\filters\VerbFilter;

class SiteController extends Controller
{
    public $mark;
    public $model;
    public $engine;
    public $drive;

    /**
     * {@inheritdoc}
     */
    /*
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }*/

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            /*'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],*/
        ];
    }

    /**
     * Displays catalog.
     *
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionIndex()
    {
        $mark = Yii::$app->request->get('mark');
        if ($mark == 'ajax') {
            $mark = '';
        }
        $model = Yii::$app->request->get('model');
        if ($model == 'ajax' || $model == 'request') {
            $model = '';
        }
        $engine = Yii::$app->request->get('engine');
        $drive = Yii::$app->request->get('drive');
        $form_params = ['mark' => $mark, 'model' => $model, 'engine' => $engine, 'drive' => $drive];

        $search_form = new SearchForm($form_params);

        $carsDataProvider = Car::getCarsDataProviderByParamsNames($mark, $model, $engine, $drive);
        $title = Car::getTitleByParamsNames($mark, $model);
        $description = Car::getDescriptionByParamsNames($mark, $model);

        if ($mark != "" && empty($carsDataProvider->models) || $mark != "" && empty($carsDataProvider->models)) {  //заданы в url несуществующие марка или модель
            throw new NotFoundHttpException('404 Not found');
        }

        return $this->render('index', [
            'dp' => $carsDataProvider,
            'title' => $title,
            'description' => $description,
            'search_form' => $search_form
        ]);
    }

    /**
     * Initialize catalog.
     *
     * @return string
     */
    public function actionCatalogInit()
    {
        $marks = ['lexus' => 'Lexus', 'toyota' => 'Toyota'];
        $models = ['lexus' => ['es' => 'ES', 'gx' => 'GX'], 'toyota' => ['camry' => 'Camry', 'corolla' => 'Corolla']];
        $engines = ['benzin' => 'Бензин', 'disel' => 'Дизель', 'gibrid' => 'Гибрид'];
        $drives = ['full' => 'Полный', 'front' => 'Передний'];
        $log = "";

        foreach ($marks as $mark_param_name => $mark_name) {
            $mark_obj = CarMark::addRecord($mark_name, $mark_param_name);
            $log .= 'Добавлен CarMark '.$mark_obj->id.' '.$mark_obj->name.'<br />';
            if ($mark_obj && isset($models[$mark_param_name])) {
                foreach ($models[$mark_param_name] as $model_param_name => $model_name) {
                    $model_obj = CarModel::addRecord($model_name, $model_param_name, $mark_obj->id);
                    $log .= 'Добавлен CarModel '.$model_obj->id.' '.$model_obj->name.'<br />';
                    foreach ($engines as $engine_param_name => $engine_name) {
                        $engine_obj = Engine::addRecord($engine_name, $engine_param_name);
                        $log .= 'Добавлен Engine '.$engine_obj->id.''.$engine_obj->name.'<br />';
                        foreach ($drives as $drive_param_name => $drive_name) {
                            $drive_obj = Drive::addRecord($drive_name, $drive_param_name);
                            $log .= 'Добавлен Drive '.$drive_obj->id.' '.$drive_obj->name.'<br />';
                            $car_obj = Car::addRecord($mark_obj->id, $model_obj->id, $engine_obj->id, $drive_obj->id);
                            $log .= 'Добавлен Car '.$car_obj->id.' '.$mark_obj->id.' '.$mark_obj->name.' '.$model_obj->id.' '.$model_obj->name.' '.$engine_obj->id.' '.$engine_obj->name.' '.$drive_obj->id.' '.$drive_obj->name.'<br />';
                        }
                    }
                }
            }
        }

        return $this->render('init', ['log' => $log]);
    }

    public function actionSearch()
    {
        $ajax = Yii::$app->request;
        if($ajax -> isAjax) {
            $arr = $ajax->post();
            $mark = $arr['mark'];
            $model = $arr['model'];
            $engine = $arr['engine'];
            $drive = $arr['drive'];
            $dp = Car::getCarsDataProviderByParamsNames($mark, $model, $engine, $drive);

            return $this->renderAjax('_car_list', ['dp' => $dp]);
        }
        return false;
    }

}
