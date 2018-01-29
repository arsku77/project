<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model shop\forms\manage\Shop\CountryForm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tag-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <div class="box box-default">
        <div class="box-body">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'iso_code_2')->textInput(['maxlength' => 2]) ?>
            <?= $form->field($model, 'iso_code_3')->textInput(['maxlength' => 3]) ?>
            <?= $form->field($model, 'iso_number_3')->textInput(['maxlength' => 3]) ?>
            <?= $form->field($model, 'active')->textInput(['maxlength' => 1]) ?>
            <?= $form->field($model, 'sort')->textInput(['maxlength' => 3]) ?>

        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
