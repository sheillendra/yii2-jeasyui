<?php

use yii\helpers\Inflector;

echo "<?php\n" ?>

use yii\helpers\Url;

/* @var $this \yii\web\View */

$this->params['navItem'] = [
<?php

    $apiControllerPath = Yii::getAlias('@' . str_replace('\\', '/', ltrim($generator->apiName, '\\')). '/controllers');
    $firstId = null;
    $files = scandir($apiControllerPath);
    if(!in_array($baseControllerName . '.php', $files)){
        $files[] = $baseControllerName . '.php';
    }
    foreach ($files as $file) {
        if (($file === $baseControllerName . '.php') || is_file($apiControllerPath . '/' . $file) && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
            $baseName  = str_replace('Controller.php', '', $file);
            $id = Inflector::camel2id($baseName);
    ?>
    '<?=Inflector::variablize($baseName)?>' => [
        'id' => 'nav-<?= $id ?>',
        'text' => '<?= Inflector::camel2words($baseName) ?>',
        'iconCls' => 'fa-solid fa-house',
        'attributes' => [
            'url' => Url::to(['/<?= $id ?>'])
        ]
    ],
<?php      
            if(!$firstId){
                $firstId = $id;
            }
        }
    }?>
];

$this->params['defaultSelectedNav'] = 'nav-<?=$firstId?>';