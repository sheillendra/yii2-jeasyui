<?php
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

$modelClassName = StringHelper::basename($generator->modelClass);
$idModelClassName = Inflector::camel2id($modelClassName);
$varModelClassName = Inflector::variablize($modelClassName);

echo "<?php\n";

?>
use yii\helpers\Url;

<?php
$navItemUrlStart = strpos($oldFileAsText,'$navItemUrl');
$navItemUrlEnd = strpos($oldFileAsText,';',$navItemUrlStart);
$navItemStart = strpos($oldFileAsText,'$navItem',$navItemUrlEnd);
$navItemEnd = strpos($oldFileAsText,';',$navItemStart);
$navItemUrlText = substr($oldFileAsText, $navItemUrlStart,$navItemUrlEnd - $navItemUrlStart + 1 );
$navItemText = substr($oldFileAsText, $navItemStart,$navItemEnd - $navItemStart + 1 );

if(!isset($navItemUrl["$idModelClassName"])){
    $navItemUrlText = str_replace("];",'',$navItemUrlText);
    $navItemUrlText .= "    ,'$idModelClassName' =>[\n";
    $navItemUrlText .= "        'list' =>Url::to(['$idModelClassName/index'],true),\n";
    $navItemUrlText .= "        'new' =>Url::to(['$idModelClassName/new'],true)\n";
    $navItemUrlText .= "    ]\n";
    $navItemUrlText .= "];\n";
    
    $navItemText = str_replace("];",'',$navItemText);
    $navItemText .= "    ,'$idModelClassName' =>[\n";
    $navItemText .= "        'title' =>'$modelClassName',\n";
    $navItemText .= "        'iconCls' =>'icon-tip',\n";
    $navItemText .= "        'content' =><<<HTML\n";
    $navItemText .= '            <a id="nav-'.$idModelClassName.'-list" class="nav-btn" data-icon="icon-help" data-url="{$navItemUrl[\''.$idModelClassName.'\'][\'list\']}" data-tabtitle="'.$modelClassName.' List">List</a>'."\n";
    $navItemText .= '            <a id="nav-'.$idModelClassName.'-new" class="nav-btn" data-icon="icon-help" data-url="{$navItemUrl[\''.$idModelClassName.'\'][\'new\']}" data-tabtitle="'.$modelClassName.' New">New</a>'."\n";
    $navItemText .= "HTML\n";
    $navItemText .= "    ]\n";
    $navItemText .= "];\n";
    
}

echo $navItemUrlText;
echo "\n";
echo $navItemText;