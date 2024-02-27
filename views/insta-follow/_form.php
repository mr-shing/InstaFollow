<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\InstaFollow $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="insta-follow-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'owner_id')->textInput() ?>

    <?= $form->field($model, 'follow_by')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
