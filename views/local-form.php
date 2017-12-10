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

$form = ActiveForm::begin(['layout' => 'horizontal']);
/** @var \yii\db\ActiveRecord $model */
$fullClassName = $dataProvider->query->modelClass;
escape
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

$csrf = Yii::$app->request->getCsrfToken();
$formID = $form->id;
$script2 = <<< JS
    $(document).ready(function(){
        $('form#$formID').submit(function(e){
            e.preventDefault();
            if (!confirm("Sure?")) {
                return false;
            }
            var fields = $fieldsJs;
            var fieldsData = {};
            
            var data = {_csrf: '$csrf'};
            for(var i = 0; i < fields.length; i++) {
                var fieldName = fields[i];
                var selector = '[name="' + fieldName + '"]';
                var el = $(selector).constructor === Array && $(selector).length > 0 ? $(selector)[0] : $(selector);
                if (el.is('select')) {
                    el = el.find(':selected');
                }
                fieldsData[fieldName] = el.val();
            }
            
            console.log({
                    "class": '$fullClassName',
                    "data": fieldsData
                });
        
            $.ajax({
                dataType: 'json',
                type: 'POST',
                url: '$createUrl',
                data: {
                    "class": '$fullClassName',
                    "data": fieldsData
                },
                success: function(data) {
//                    location.reload(); // or whatever
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                }
            });
            
            return false;
       });
    }); 
JS;
$this->registerJs($script2, View::POS_END);
?>

<?php foreach ($editColumns as $col): ?>
    <?php if(is_string($col)): ?>
        <?= $form->field($model, $col)->textInput() ?>
    <?php elseif (is_array($col) && isset($col['select'])): ?>
        <?= $form->field($model, $col['attr'])->dropDownList($col['select']) ?>
    <?php elseif (is_array($col) && isset($col['radio'])): ?>
        <?= $form->field($model, $col['attr'])->radioList($col['radio']) ?>
    <?php endif; ?>
<?php endforeach; ?>

<div class="form-group row">
    <?= Html::submitButton('Update', ['class' => 'btn btn-primary col-lg-offset-3']) ?>
</div>

<?php ActiveForm::end(); ?>

<?php Modal::end(); ?>
