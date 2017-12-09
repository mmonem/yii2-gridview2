<?php

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;
use yii\data\BaseDataProvider;
use yii\grid\Column;
use yii\grid\DataColumn;
use yii\helpers\Html;

/** @var BaseDataProvider $dataProvider */
/** @var string[] $editColumns */
?>
<?php
Modal::begin([
    'toggleButton' => [
        'label' => '<i class="glyphicon glyphicon-plus"></i>',
        'class' => 'btn btn-sm btn-success'
    ],
    'closeButton' => [
        'label' => '<i class="glyphicon glyphicon-remove"></i>',
        'class' => 'btn btn-sm btn-danger',
    ],
    'size' => 'modal-lg',
]);
?>
    <?php
        $form = ActiveForm::begin(['layout' => 'horizontal']);
    /** @var \yii\db\ActiveRecord $model */
    $model = new $dataProvider->query->modelClass();
    ?>

    <?php
    foreach ($editColumns as $col): ?>
        <?php if(is_string($col)): ?>
            <?= $form->field($model, $col)->textInput() ?>
        <?php elseif (is_array($col)): ?>
            <?= $form->field($model, $col['attr'])->dropDownList($col['lookup']) ?>
        <?php endif; ?>
    <?php endforeach; ?>

    <div class="form-group row">
        <?= Html::submitButton('Update', ['class' => 'btn btn-primary col-lg-offset-3']) ?>
    </div>

    <?php ActiveForm::end(); ?>

<?php Modal::end(); ?>
