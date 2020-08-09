<?php

/* @var $this yii\web\View */

use yii\widgets\Pjax;

/* @var $dp yii\data\ActiveDataProvider */
/* @var $title string */
/* @var $description string */
/* @var $search_form app\models\SearchForm */

$this->title = $title;

\Yii::$app->view->registerMetaTag([
    'name'    => 'description',
    'content' => $description,
]);
?>
<div>
    <?php
        echo $this->render('search-form', [
            'search_form' => $search_form,
        ]);
    ?>

<div id="catalog-list">
<?php
    echo $this->renderAjax('_car_list', ['dp' => $dp]);
?>
</div>
