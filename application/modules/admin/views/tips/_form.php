<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\TipsCategory;

/* @var $this yii\web\View */
/* @var $model app\models\TipsDetail */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tips-detail-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'category_id')->dropDownList(ArrayHelper::map(TipsCategory::find()->all(), 'id', 'category')) ?>

    <?= $form->field($model, 'tips')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'active')->dropDownList(['1' => 'Active', '0' => 'De-Active']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
