<?php
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

$baseName = str_replace('Ext', '', StringHelper::basename($generator->modelClass));
echo "<?php\n";
?>

/* @var $this \yii\web\View */

use sheillendra\jeasyui\components\helpers\Regex;

$this->title = '<?= $baseName?>';

if (Yii::$app->request->isAjax) {
    $this->context->layout = '//ajax';
    echo $this->renderAjax('_index');
} else {
    $this->params['selectedNav'] = 'nav-<?= Inflector::camel2id($baseName) ?>';
    $this->params['tabOptions']['content'] = preg_replace(Regex::HTML_MINIFIED, ' ', $this->render('_index'));
}