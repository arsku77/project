<?php

use shop\helpers\CountryHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $country shop\entities\Shop\Country */

$this->title = $country->name;
$this->params['breadcrumbs'][] = ['label' => 'Countries', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <p>

        <?= Html::a('Update', ['update', 'id' => $country->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $country->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>


        <?php if ($country->isActive()): ?>
            <?= Html::a('Draft', ['draft', 'id' => $country->id], ['class' => 'btn btn-primary', 'data-method' => 'post']) ?>
        <?php else: ?>
            <?= Html::a('Activate', ['activate', 'id' => $country->id], ['class' => 'btn btn-success', 'data-method' => 'post']) ?>
        <?php endif; ?>


    </p>

    <div class="box">
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $country,
                'attributes' => [
                    'id',
                    'name',
                    'iso_code_2',
                    'iso_code_3',
                    'iso_number_3',
                    [
                        'attribute' => 'active',
                        'value' => CountryHelper::statusLabel($country->active),
                        'format' => 'raw',
                    ],
                    'sort',
                ],
            ]) ?>
        </div>
    </div>
</div>
