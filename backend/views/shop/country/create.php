<?php

/* @var $this yii\web\View */
/* @var $model shop\forms\manage\Shop\CountryForm */

$this->title = 'Create Country';
$this->params['breadcrumbs'][] = ['label' => 'Tags', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tag-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
