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
    public $jeasyUiSearchModelClass = 'api\modules\v1\modules\jeasyui\models\ModelSearch';

    public function getName()
    {
        return 'jEasyUI CRUD Generator';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['modelClass', 'appName', 'apiName'], 'required'],
            [['controllerClass', 'modelClass', 'appName', 'apiName', 'searchModelClass', 'baseControllerClass'], 'trim', 'chars' => '\ '],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function generate()
    {
        $modelClassName = StringHelper::basename($this->modelClass);

        $baseControllerName = substr($modelClassName, 0, -3) . 'Controller';
        $baseSearchModelName = substr($modelClassName, 0, -3) . 'Search';

        $this->controllerClass = $this->appName . '\controllers\\' . $baseControllerName;
        $controllerFile = Yii::getAlias('@' . str_replace('\\', '/', ltrim($this->controllerClass, '\\')) . '.php');

        $files = [
            new CodeFile($controllerFile, $this->render('controller.php')),
        ];

        $this->apiControllerClass = $this->apiName . '\controllers\\' . $baseControllerName;
        $controllerApiFile = Yii::getAlias('@' . str_replace('\\', '/', ltrim($this->apiControllerClass, '\\')) . '.php');

        $files[] = new CodeFile($controllerApiFile, $this->render('controller-api.php'));

        $controllerApiFile = Yii::getAlias('@' . str_replace('\\', '/', ltrim($this->apiName . '\modules\jeasyui\\'. $baseControllerName, '\\')) . '.php');
        $files[] = new CodeFile($controllerApiFile, $this->render('controller-api-jeasyui.php'));

        $this->searchModelClass = $this->apiName . '\models\\'. $baseSearchModelName;
        $searchModel = Yii::getAlias('@' . str_replace('\\', '/', ltrim($this->searchModelClass, '\\') . '.php'));
        $files[] = new CodeFile($searchModel, $this->render('search.php'));

        $this->jeasyUiSearchModelClass = $this->apiName . '\modules\jeasyui\models\\'. $baseSearchModelName;
        $jeasyUiSearchModel = Yii::getAlias('@' . str_replace('\\', '/', ltrim($this->jeasyUiSearchModelClass, '\\') . '.php'));
        $files[] = new CodeFile($jeasyUiSearchModel, $this->render('search-jeasyui.php'));

        $this->viewPath = '@' . $this->appName . '/themes/jeasyui/views/' . Inflector::camel2id(str_replace('Controller', '',$baseControllerName));
        $viewPath = $this->getViewPath();
        $templatePath = $this->getTemplatePath() . '/views';
        foreach (scandir($templatePath) as $file) {
            if (empty($this->searchModelClass) && $file === '_search.php') {
                continue;
            }
            if (is_file($templatePath . '/' . $file) && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                $files[] = new CodeFile("$viewPath/$file", $this->render("views/$file"));
            }
        }

        return $files;

        $assetsPath = Yii::getAlias('@app/assets');
        $assetsTemplatePath = $this->getTemplatePath() . '/assets';

        $idModelClassName = Inflector::camel2id($modelClassName);
        $varModelClassName = Inflector::variablize($modelClassName);

        foreach (scandir($assetsTemplatePath) as $file) {
            if (is_file($assetsTemplatePath . '/' . $file) && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                $files[] = new CodeFile("$assetsPath/{$modelClassName}$file", $this->render("assets/$file"));
            }
        }

        $cssPath = $this->getTemplatePath() . '/views/assets/css';
        foreach (scandir($cssPath) as $file) {
            if (is_file($cssPath . '/' . $file) && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                $file_path_as = str_replace('.php', '.css', $file);
                $files[] = new CodeFile("$viewPath/assets/css/{$idModelClassName}$file_path_as", $this->render("views/assets/css/$file"));
            }
        }

        $jsPath = $this->getTemplatePath() . '/views/assets/js';
        foreach (scandir($jsPath) as $file) {
            if (is_file($jsPath . '/' . $file) && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                $file_path_as = str_replace('.php', '.js', $file);
                $files[] = new CodeFile("$viewPath/assets/js/{$idModelClassName}$file_path_as", $this->render("views/assets/js/$file"));
            }
        }

        $layoutsPath = Yii::getAlias('@app/views/layouts');
        $layoutsTemplatePath = $this->getTemplatePath() . '/views/layouts';

        if (file_exists("$layoutsPath/_nav-item.php")) {
            require("$layoutsPath/_nav-item.php");
            $oldFileAsText = file_get_contents("$layoutsPath/_nav-item.php");
        } else {
            foreach (scandir($layoutsTemplatePath) as $file) {
                if (
                    is_file($layoutsTemplatePath . '/' . $file) &&
                    pathinfo($file, PATHINFO_EXTENSION) === 'php' &&
                    $file !== '_nav-item.php' &&
                    $file !== '_nav-item-def.php'
                ) {
                    $files[] = new CodeFile("$layoutsPath/$file", $this->render("views/layouts/$file"));
                }
            }
            require("$layoutsTemplatePath/_nav-item-def.php");
            $oldFileAsText = file_get_contents("$layoutsTemplatePath/_nav-item-def.php");

            $webPath = Yii::getAlias('@app/web');
            $files[] = new CodeFile("$webPath/js/app.js", $this->render("app.js"));
            $files[] = new CodeFile("$webPath/css/site.css", $this->render("site.css"));

            $controllersPath = Yii::getAlias('@app/controllers');
            $files[] = new CodeFile("$controllersPath/SiteController.php", $this->render("SiteController.php"));

            $sitePath = Yii::getAlias('@app/views/site');
            $siteTemplatePath = $this->getTemplatePath() . '/views/site';
            foreach (scandir($siteTemplatePath) as $file) {
                if (is_file($siteTemplatePath . '/' . $file)) {
                    $files[] = new CodeFile("$sitePath/$file", $this->render("views/site/$file"));
                }
            }

            foreach (scandir($siteTemplatePath . '/assets') as $file) {
                if (is_file($siteTemplatePath . '/assets/' . $file)) {
                    $files[] = new CodeFile("$sitePath/assets/$file", $this->render("views/site/assets/$file"));
                }
            }

            foreach (scandir($siteTemplatePath . '/assets/css') as $file) {
                if (is_file($siteTemplatePath . '/assets/css/' . $file)) {
                    $files[] = new CodeFile("$sitePath/assets/css/$file", $this->render("views/site/assets/css/$file"));
                }
            }

            foreach (scandir($siteTemplatePath . '/assets/js') as $file) {
                if (is_file($siteTemplatePath . '/assets/js/' . $file)) {
                    $files[] = new CodeFile("$sitePath/assets/js/$file", $this->render("views/site/assets/js/$file"));
                }
            }

            $files[] = new CodeFile("$assetsPath/AppAsset.php", $this->render("AppAsset.php"));

            $componentsPath = Yii::getAlias('@app/components');
            $files[] = new CodeFile("$componentsPath/helpers/Regex.php", $this->render("Regex.php"));
        }

        $files[] = new CodeFile(
            "$layoutsPath/_nav-item.php",
            $this->render("views/layouts/_nav-item.php", [
                'navItemUrl' => $navItemUrl,
                'navItem' => $navItem,
                'oldFileAsText' => $oldFileAsText
            ])
        );
        return $files;
    }
}
