<?php

namespace sheillendra\jeasyui\controllers;

use Yii;
use yii\helpers\Json;
use sheillendra\jeasyui\components\web\Controller;
use sheillendra\jeasyui\models\ChangePasswordForm;

class ProfileController extends Controller
{
    public function actionChangePassword()
    {
        $result = [
            'success' => false,
            'message' => '',
        ];
        $model = new ChangePasswordForm();
        if ($model->load(Yii::$app->request->post(), '') && $model->change()) {
            Yii::$app->user->logout();
            $result['success'] = true;
        } else {
            $result['message'] = implode(', ', $model->getFirstErrors());
        }
        echo Json::encode($result);
        return Yii::$app->end();
    }
}
