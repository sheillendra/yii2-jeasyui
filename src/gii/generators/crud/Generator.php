<?php

namespace sheillendra\jeasyui\gii\generators\crud;

use Yii;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\db\Schema;
use yii\gii\CodeFile;
use yii\helpers\Inflector;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\helpers\StringHelper;

class Generator extends \yii\gii\generators\crud\Generator
{
    public $appName = 'backend';
    public $apiName = 'api\modules\v1';
    public $controllerClass = 'backend\controllers\SiteController';
    public $apiControllerClass = 'api\modules\v1\controllers\SiteController';
    public $apiJeasyUIControllerClass = 'api\modules\v1\modules\jeasyui\controllers\SiteController';
    public $extModelClass = 'api\modules\v1\models\ModelExt';
    public $jeasyUiSearchModelClass = 'api\modules\v1\modules\jeasyui\models\ModelSearch';

    public function getName()
    {
        return 'Jquery EasyUI CRUD Generator';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['modelClass', 'appName', 'apiName'], 'required'],
            [
                [
                    'controllerClass', 'modelClass', 'appName', 'apiName', 'searchModelClass',
                    'baseControllerClass',
                    'jeasyUiSearchModelClass'
                ], 'trim', 'chars' => '\ '
            ],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function generate()
    {
        $modelClassName = StringHelper::basename($this->modelClass);

        if (substr($modelClassName, -3) === 'Ext') {
            $baseModelClassName = substr($modelClassName, 0, -3);
        } else {
            $baseModelClassName = $modelClassName;
        }       
        $baseControllerName = $baseModelClassName . 'Controller';
        $baseSearchModelName = $baseModelClassName . 'Search';

        //backend/controller
        $this->controllerClass = $this->appName . '\controllers\\' . $baseControllerName;
        $controllerFile = Yii::getAlias('@' . str_replace('\\', '/', ltrim($this->controllerClass, '\\')) . '.php');

        $files = [
            new CodeFile($controllerFile, $this->render('controller.php', [
                'controllerClass' => $baseControllerName,
                'modelClassName' => $modelClassName
            ])),
        ];

        //ext model api
        $this->extModelClass = $this->apiName . '\models\\' . $modelClassName;
        $extModelClass = Yii::getAlias('@' . str_replace('\\', '/', ltrim($this->extModelClass, '\\') . '.php'));
        $files[] = new CodeFile($extModelClass, $this->render('model.php'));

        //searchmodel api
        $this->searchModelClass = $this->apiName . '\models\\' . $baseSearchModelName;
        $searchModel = Yii::getAlias('@' . str_replace('\\', '/', ltrim($this->searchModelClass, '\\') . '.php'));
        $files[] = new CodeFile($searchModel, $this->render('search.php'));

        //controller api
        $this->apiControllerClass = $this->apiName . '\controllers\\' . $baseControllerName;
        $controllerApiFile = Yii::getAlias('@' . str_replace('\\', '/', ltrim($this->apiControllerClass, '\\')) . '.php');
        $files[] = new CodeFile($controllerApiFile, $this->render('controller-api.php', [
            'baseSearchModelName' => $baseSearchModelName
        ]));

        //jeasyui searchmodel
        $this->jeasyUiSearchModelClass = $this->apiName . '\modules\jeasyui\models\\' . $baseSearchModelName;
        $jeasyUiSearchModel = Yii::getAlias('@' . str_replace('\\', '/', ltrim($this->jeasyUiSearchModelClass, '\\') . '.php'));
        $files[] = new CodeFile($jeasyUiSearchModel, $this->render('search-jeasyui.php'));

        $jeasyUiControllerFile = Yii::getAlias('@' . str_replace('\\', '/', ltrim($this->apiName . '\modules\jeasyui\controllers\\' . $baseControllerName, '\\')) . '.php');
        $files[] = new CodeFile($jeasyUiControllerFile, $this->render('controller-api-jeasyui.php'));

        $viewPath = Yii::getAlias('@' . str_replace('\\', '/', ltrim($this->appName)) . '/themes/jeasyui/views/') . Inflector::camel2id(str_replace('Controller', '', $baseControllerName));
        $templatePath = $this->getTemplatePath() . '/views';
        foreach (scandir($templatePath) as $file) {
            if (is_file($templatePath . '/' . $file) && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                $files[] = new CodeFile("$viewPath/$file", $this->render("views/$file"));
            }
        }

        $assetsPath = Yii::getAlias('@'. str_replace('\\', '/', ltrim($this->appName)) .'/themes/jeasyui/assets');

        $idModelClassName = Inflector::camel2id($baseModelClassName);
        $baseAssetName = $baseModelClassName . 'Asset';

        $files[] = new CodeFile("$assetsPath/{$baseAssetName}.php", $this->render('asset.php', [
            'baseAssetName' => $baseAssetName,
            'modelClassName' => $modelClassName
        ]));

        //PENDING ganti dengan pengecekan ada tanda / atau tidak
        if($this->appName === 'backend'){
            $webrootJeasyUIAsset = Yii::getAlias('@webroot');
        } else {
            $webrootJeasyUIAsset = Yii::getAlias('@' . str_replace('\\', '/', ltrim($this->appName)) .'/web');
        }
        
        $files[] = new CodeFile("{$webrootJeasyUIAsset}/css/jeasyui/{$idModelClassName}.css", $this->render("jeasyui-css.php"));

        $files[] = new CodeFile("{$webrootJeasyUIAsset}/js/jeasyui/{$idModelClassName}.js", $this->render("jeasyui-js.php", [
            'baseModelClassName' => $baseModelClassName,
        ]));

        $layoutPath = Yii::getAlias('@'. str_replace('\\', '/', ltrim($this->appName)) .'/themes/jeasyui/views/layouts');

        $files[] = new CodeFile("{$layoutPath}/_nav-item.php", $this->render("nav-item.php", ['baseControllerName' => $baseControllerName]));

        return $files;
    }
}
