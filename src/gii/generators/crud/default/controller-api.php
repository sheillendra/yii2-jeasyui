<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

$controllerClass = StringHelper::basename($generator->controllerClass);
$modelClass = StringHelper::basename($generator->modelClass);

$baseName = str_replace('Ext', '', StringHelper::basename($generator->modelClass));
$constId = strtoupper(Inflector::camel2id($baseName, '_'));

echo "<?php\n";
?>

namespace <?= StringHelper::dirname(ltrim($generator->apiControllerClass, '\\')) ?>;

use sheillendra\jeasyui\components\rest\ActiveController;
use <?=$generator->appName?>\models\User;
use <?=$generator->apiName?>\models\<?=$modelClass?>;
use <?=$generator->searchModelClass?>;

/**
 * <?=$controllerClass?> only implements the jEasyUI get asset and page for <?=$modelClass?> model.
 */
class <?=$controllerClass?> extends ActiveController
{
    public $rules = [ 
        [ 
            'actions' => ['index'], 
            'allow' => true, 
            'roles' => [User::READ_<?=$constId?>_PERMISSION], 
        ], 
        [ 
            'actions' => ['create'], 
            'allow' => true, 
            'roles' => [User::CREATE_<?=$constId?>_PERMISSION], 
        ],
        [ 
            'actions' => ['update'], 
            'allow' => true, 
            'roles' => [User::UPDATE_<?=$constId?>_PERMISSION], 
        ],
        [ 
            'actions' => ['delete'], 
            'allow' => true, 
            'roles' => [User::DELETE_<?=$constId?>_PERMISSION], 
        ],
    ]; 
    public $modelClass = <?=$modelClass?>::class;
    public $searchModelClass = <?=$baseSearchModelName?>::class;
}
