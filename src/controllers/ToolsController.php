<?php

/**
 * @copyright Copyright (c) 2017
 * @author Suryana <suryana869@gmail.com>
 * @since 1.0.0
 */

namespace sheillendra\jeasyui\controllers;

use common\models\Role;
use Yii;
use yii\filters\AccessControl;
use yii\rest\Controller;
use Exception;
use yii\helpers\FileHelper;
use yii\caching\TagDependency;
use yii\filters\ContentNegotiator;
use yii\web\Response;

/**
 * Site controller
 */
class ToolsController extends Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => [Role::SUPERADMIN_ROLE],
                    ],
                ],
            ],
            'contentNegotiator' => [
                'class' => ContentNegotiator::class,
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ],
        ];
    }

    public function actionCleanAssets()
    {
        $result = [
            'success' => true,
            'message' => 'Clean Assets is success'
        ];
        try {
            $assetPath = Yii::$app->getAssetManager()->basePath;
            $assetList = scandir($assetPath);
            foreach ($assetList as $dir) {
                if ($dir == '.' || $dir == '..' || $dir == '.gitignore') {
                    continue;
                }
                FileHelper::removeDirectory("$assetPath/$dir");
            }
        } catch (Exception $e) {
            $result['success'] = false;
            $result['message'] = $e->getMessage();
        }
        return $result;
    }

    public function actionClearSchemaCache()
    {
        $result = [
            'success' => true,
            'message' => 'Clear Schema Cache is success'
        ];
        try {
            Yii::$app->getDb()->getSchema()->refresh();
        } catch (Exception $e) {
            $result['success'] = false;
            $result['message'] = $e->getMessage();
        }
        return $result;
    }

    public function actionInvalidateDependency($tag)
    {
        $result = [
            'success' => true,
            'message' => strtr('Invalidate Dependecy fo {tag} tag is success', ['{tag}' => $tag])
        ];
        try {
            TagDependency::invalidate(Yii::$app->cache, $tag);
        } catch (Exception $e) {
            $result['success'] = false;
            $result['message'] = $e->getMessage();
        }
        return $result;
    }

    public function actionFlushCache()
    {
        $result = [
            'success' => true,
            'message' => 'Flush Cache is success'
        ];
        try {
            Yii::$app->cache->flush();
        } catch (Exception $e) {
            $result['success'] = false;
            $result['message'] = $e->getMessage();
        }
        return $result;
    }
}
