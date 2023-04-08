<?php

namespace apidas\modules\v1\components\rest;

class ActiveController extends \yii\rest\ActiveController {

    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'rows'
    ];

    public function behaviors() {

        return array_merge(parent::behaviors(), [
            'authenticator' => [
                'class' => \yii\filters\auth\QueryParamAuth::class,
            ],
            'access' => [
                'class' => \yii\filters\AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ]
            ],
            'corsFilter' => \yii\filters\Cors::class
        ]);
    }

    protected function verbs() {
        $verbs = parent::verbs();
        $verbs['update'][] = 'POST';
        $verbs['delete'][] = 'POST';
        return $verbs;
    }

}
