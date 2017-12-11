<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace mmonem\yii2gridview2;

use Yii;
use yii\base\Action;
use yii\db\ActiveRecord;
use yii\web\Response;


/**
 * Description of CreateAction
 *
 * @author monem
 */
class CreateAction extends Action {

    public $modelClass;

    /**
     * @inheritdoc
     */
    public function run()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (!Yii::$app->request ->isAjax) {
            return ['result' => 'FAILED 1'];
        }

        $modelClass = Yii::$app->request->post('_modelClass', '');
        if ($modelClass !== $this->modelClass) {
            return ['result' => 'FAILED 2'];
        }

        /** @var ActiveRecord $model */
        $model = new $modelClass();
        if (!$model->load(Yii::$app->request->post())) {
            return ['result' => 'FAILED 3'];
        }

        if (!$model->save()) {
            return ['result' => 'FAILED 4', 'message' => 'Data validation failed', 'errors' => $model->errors];
        }

        return [
            'result' => 'OK',
        ];
    }
}
