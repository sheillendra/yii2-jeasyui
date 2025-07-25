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

$treeDataAction = '';
$treeDataRole = '';
$useTreeData = '';
$extraAction = '';
$extraActionRole = '';
if(isset($easyuiAttributes['_'])){
    if(isset($easyuiAttributes['_']['asTreeData']) && $easyuiAttributes['_']['asTreeData']){
        
        $useTreeData = "use yii\helpers\ArrayHelper;\n";
        $treeDataRole = "\n";

        $treeDataAction = "    public function actionTreeData(\$id = null)\n";
        $treeDataAction .= "    {\n";
        $treeDataAction .= "        \$data = \$this->modelClass::find()->select(['id', 'name as text', 'code'])->where(['parent_id' => \$id])->asArray()->all();\n";
        $treeDataAction .= "        foreach (\$data as \$k => \$v) {\n";
        $treeDataAction .= "            if (strlen(\$v['code']) < 9) {\n";
        $treeDataAction .= "               \$data[\$k]['state'] = 'closed';\n";
        $treeDataAction .= "           }\n";
        $treeDataAction .= "       }\n";
        $treeDataAction .= "       ArrayHelper::multisort(\$data, 'text');\n";
        $treeDataAction .= "       return \$data;\n";
        $treeDataAction .= "   }\n";

        $treeDataRole .= "        [\n";
        $treeDataRole .= "            'actions' => ['tree-data'],\n";
        $treeDataRole .= "            'allow' => true,\n";
        $treeDataRole .= "            'roles' => ['@'],\n";
        $treeDataRole .= "        ],\n";
    }

    if(isset($easyuiAttributes['_']['extraAction']) && is_array($easyuiAttributes['_']['extraAction'])){

        $extraActionRole = "\n";
        foreach($easyuiAttributes['_']['extraAction'] as $k => $v){
            $extraAction .= "    public function {$k}({$v['params']}){\n";
            $extraAction .= "        {$v['return']}\n";
            $extraAction .= "    }\n";

            $baseActionName = Inflector::variablize(str_replace('action', '', $k));
            $extraActionRole .= "        [\n";
            $extraActionRole .= "            'actions' => ['{$baseActionName}'],\n";
            $extraActionRole .= "            'allow' => true,\n";
            $extraActionRole .= "            'roles' => ['@'],\n";
            $extraActionRole .= "        ],\n";
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
<?=$useTreeData?>

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
        ],<?=$treeDataRole?><?=$extraActionRole?>
    ]; 
    public $modelClass = <?=$modelClass?>::class;
    public $searchModelClass = <?=$baseSearchModelName?>::class;

<?=$treeDataAction?>
<?=$extraAction?>

}
