<?php

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;
use yii\data\BaseDataProvider;
use yii\helpers\Html;
use yii\web\View;

/** @var BaseDataProvider $dataProvider */
/** @var string $createUrl */
/** @var string[] $editColumns */
?>
<?php
$modal = Modal::begin([
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

$form = ActiveForm::begin(['layout' => 'horizontal']);
/** @var \yii\db\ActiveRecord $model */
$fullClassName = $dataProvider->query->modelClass;

$model = new $fullClassName();
$modelClassParts = explode('\\', $model->className());
$modelClassName = $modelClassParts[count($modelClassParts) - 1];

$fields = [];
foreach ($editColumns as $col) {
    if(is_string($col)) {
        $fields[] = "${modelClassName}[$col]";
    }
    elseif (is_array($col)) {
        $fields[] = "${modelClassName}[${col['attr']}]";
    }
}
$fieldsJs = json_encode($fields);

$formID = $form->id;
$script2 = <<< JS
    $(document).ready(function(){
        var request;

        $('form#$formID').submit(function(e){
            e.preventDefault();
            e.stopImmediatePropagation();
            
            if (request) {
                request.abort();
            }
            
            request = $.ajax({
                type: 'post',
                url: '$createUrl',
                data: $(this).serialize()
            });
            
//            $(this).find("input, select").prop("disabled", true);

            request.done(function(data) {
                console.log(data);
                $('#$modal->id').modal('toggle');
            });
            
            request.fail(function(jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            });
            
            return false;
       });
    }); 
JS;
$this->registerJs($script2, View::POS_END);
?>

<?=Html::hiddenInput('_csrf', Yii::$app->request->getCsrfToken())?>
<?=Html::hiddenInput('_modelClass', $fullClassName)?>
<?php foreach ($editColumns as $col): ?>
    <?php if(is_string($col)): ?>
        <?= $form->field($model, $col)->textInput() ?>
    <?php elseif (is_array($col)): ?>
        <?php if (isset($col['select'])): ?>
            <?= $form->field($model, $col['attr'])->dropDownList($col['select']) ?>
        <?php elseif (isset($col['radio'])): ?>
            <?= $form->field($model, $col['attr'])->radioList($col['radio']) ?>
        <?php elseif (isset($col['hidden'])): ?>
            <?=Html::activeHiddenInput($model, $col['attr'], ['value' => $col['hidden']])?>
        <?php endif; ?>
    <?php endif; ?>
<?php endforeach; ?>

<div class="form-group row">
    <?= Html::submitButton('Update', ['class' => 'btn btn-primary col-lg-offset-3']) ?>
</div>

<?php ActiveForm::end(); ?>

<?php Modal::end(); ?>
