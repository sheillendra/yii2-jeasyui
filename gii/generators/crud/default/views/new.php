
<?php
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

echo "<?php\n";
?>

$this->title = '<?=StringHelper::basename($generator->modelClass)?> New';
$this->params['selectedNavAccordion'] = '<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>';
$this->params['selectedNav'] = 'nav-<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-new';