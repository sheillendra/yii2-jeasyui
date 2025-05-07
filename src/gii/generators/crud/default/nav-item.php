<?php

use yii\helpers\Inflector;

echo "<?php\n" ?>

use yii\helpers\Url;

/* @var $this \yii\web\View */

$this->params['navItem'] = [
<?php

    $apiControllerPath = Yii::getAlias('@' . str_replace('\\', '/', ltrim($generator->apiName, '\\')). '/controllers');
    $firstId = null;
    if(!is_dir($apiControllerPath)){
        mkdir($apiControllerPath, 0777, true);
    }
    $files = scandir($apiControllerPath);
    if(!in_array($baseControllerName . '.php', $files)){
        $files[] = $baseControllerName . '.php';
    }
    
    //print_r($files);exit;

    foreach ($files as $file) {
        $iconCls = 'fa-solid fa-house';
        if (($file === $baseControllerName . '.php') || is_file($apiControllerPath . '/' . $file) && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
            $baseName  = str_replace('Controller.php', '', $file);
            $id = Inflector::camel2id($baseName);
            $modelPath = Yii::getAlias('@' . $generator->appName . '/models') . DIRECTORY_SEPARATOR . $baseName . 'Ext.php'; 
            if(file_exists($modelPath)){
                $model = new ('\\' . $generator->appName . '\models\\'. $baseName . 'Ext');
                if(method_exists($model, 'getEasyuiAttributes')){
                    $easyuiAttribute = $model->easyuiAttributes;
                    if(isset($easyuiAttribute['_']) && isset($easyuiAttribute['_']['iconCls'])){
                        $iconCls = $easyuiAttribute['_']['iconCls'];
                    }
                }
            }
    ?>
    '<?=Inflector::variablize($baseName)?>' => [
        'id' => 'nav-<?= $id ?>',
        'text' => '<?= Inflector::camel2words($baseName) ?>',
        'iconCls' => '<?=$iconCls?>',
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