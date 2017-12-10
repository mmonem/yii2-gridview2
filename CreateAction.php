<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace mmonem\yii2gridview2;

use Yii;
use yii\base\Action;
use yii\web\BadRequestHttpException;
use yii\web\Response;


/**
 * Description of CreateAction
 *
 * @author monem
 */
class CreateAction extends Action {

    /**
     * @inheritdoc
     */
    public function run()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (!Yii::$app->request ->isAjax)
        {
            throw new BadRequestHttpException("Bad Request");
        }

        $data = Yii::$app->request->post();


        return [
            'result' => 'OK',
        ];
    }
}
