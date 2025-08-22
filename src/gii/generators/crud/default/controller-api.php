<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

$controllerClass = StringHelper::basename($generator->controllerClass);
$modelClass = StringHelper::basename($generator->modelClass);

$baseName = str_replace('Ext', '', StringHelper::basename($generator->modelClass));
$constId = strtoupper(Inflector::camel2id($baseName, '_'));

$model = new ($generator->modelClass);
$labels = $model->attributeLabels();
if(method_exists($model, 'getEasyuiAttributes')){
    $easyuiAttributes = $model->easyuiAttributes;
} else {
    $easyuiAttributes = [];
}

$rules = '';
$extraAction = '';
$rules = '';
if(isset($easyuiAttributes['_'])){

    if(isset($easyuiAttributes['_']['extraAction']) && is_array($easyuiAttributes['_']['extraAction'])){

        $rules .= "\n";
        foreach($easyuiAttributes['_']['extraAction'] as $k => $v){
            $extraAction .= "    public function {$k}({$v['params']}){\n";
            $extraAction .= "        {$v['return']}\n";
            $extraAction .= "    }\n";

            $baseActionName = Inflector::camel2id(str_replace('action', '', $k));
            $rules .= "        [\n";
            $rules .= "            'actions' => ['{$baseActionName}'],\n";
            $rules .= "            'allow' => true,\n";
            $rules .= "            'roles' => ['@'],\n";
            $rules .= "        ],\n";
        }
    }
}

echo "<?php\n";
?>

namespace <?= StringHelper::dirname(ltrim($generator->apiControllerClass, '\\')) ?>;

use sheillendra\jeasyui\components\rest\ActiveController;
use <?=$generator->appName?>\models\Permission;
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
            'roles' => [Permission::READ_<?=$constId?>_PERMISSION], 
        ], 
        [ 
            'actions' => ['create'], 
            'allow' => true, 
            'roles' => [Permission::CREATE_<?=$constId?>_PERMISSION], 
        ],
        [ 
            'actions' => ['update'], 
            'allow' => true, 
            'roles' => [Permission::UPDATE_<?=$constId?>_PERMISSION], 
        ],
        [ 
            'actions' => ['delete'], 
            'allow' => true, 
            'roles' => [Permission::DELETE_<?=$constId?>_PERMISSION], 
        ],
<?=$rules?>
    ]; 
    public $modelClass = <?=$modelClass?>::class;
    public $searchModelClass = <?=$baseSearchModelName?>::class;

<?=$extraAction?>

}
