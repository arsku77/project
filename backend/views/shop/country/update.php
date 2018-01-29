<?php

/* @var $this yii\web\View */
/* @var $country shop\entities\Shop\Tag */
/* @var $model shop\forms\manage\Shop\TagForm */

$this->title = 'Update Country: ' . $country->name;
$this->params['breadcrumbs'][] = ['label' => 'Countries', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $country->name, 'url' => ['view', 'id' => $country->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tag-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
