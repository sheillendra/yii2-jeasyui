<?php

namespace sheillendra\jeasyui\components;

use Yii;
use yii\filters\AccessControl;

class Module extends \yii\base\Module
{
    const USER_ACCESS_MODULE_ID = '';
    const USER_ACCESS_MODULE_NAME = '';
    
    public $controllerNamespace;
    public $defaultRoute = 'list';
    public $menuNumber;
    
    /**
     * 
     */
    public function init()
    {
        $modulePathAlias = '@app/modules/'.$this->id;
        parent::init();
        
        $this->controllerNamespace = 'app\modules\\'.$this->id.'\controllers';
        Yii::$app->getI18n()->translations[$this->id.'*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => $modulePathAlias.'/messages',
        ];
        
        if (isset(Yii::$app->view->theme->active)) {
            Yii::$app->view->theme->pathMap[Yii::$app->view->theme->active][$modulePathAlias.'/views'] = 
                [$modulePathAlias.'/themes/' . Yii::$app->view->theme->active . '/views'];
        }
    }
    
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => [static::USER_ACCESS_MODULE_ID],
                    ],
                ],
            ],
        ];
    }
    
}
