<?php

/* @var $this yii\web\View */
/* @var $log string */

use yii\helpers\Html;

$this->title = 'Catalog initialized';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= $log ?>
    </p>
    <p>
        Catalog initialized OK!
    </p>

</div>
