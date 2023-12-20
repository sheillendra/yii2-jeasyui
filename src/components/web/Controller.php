<?php

namespace sheillendra\components\web;

use common\models\UserExt;

class Controller extends \yii\web\Controller
{
    public $allowRoles = UserExt::ROLE_ADMIN;

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => 'yii\filters\AccessControl',
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => [$this->allowRoles],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all PegawaiExt models.
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
