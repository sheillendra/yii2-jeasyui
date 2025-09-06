<?php

namespace sheillendra\jeasyui\components\rest;


use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class Controller extends \yii\rest\Controller
{
    public $rules = [
        [
            'allow' => true,
            'roles' => ['@'],
        ],
    ];

    public $verbActions = [];

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => $this->rules,
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => $this->verbActions,
            ],
        ];
    }

    /**
     * Index.
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
