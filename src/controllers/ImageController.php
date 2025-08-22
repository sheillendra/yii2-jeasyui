<?php

namespace sheillendra\jeasyui\controllers;

use sheillendra\jeasyui\assets\YiiEasyUIAsset;
use Yii;
use yii\web\Controller;

/**
 * Feedback controller
 */
class ImageController extends Controller
{

    public $layout = '//blank';

    public function actionIndex($name)
    {
        $imgFullPath = Yii::getAlias('@uploads/' . $name);
        if(empty($name) || !file_exists($imgFullPath)){
            //$asset = YiiEasyUIAsset::register($this->view);
            //$imgFullPath = Yii::$app->assetManager->getPublishedPath($asset->sourcePath) . '/img/no-photo-available-vector.avif';
            $imgFullPath = Yii::getAlias('@webroot/img/no-image.jpg');
        }
        
        $size = getimagesize($imgFullPath);
        if(isset($size['mime'])){
            return $this->response($imgFullPath, $size);
        }
        return 'File is not an image';
    }

    protected function response($imgFullPath, $size)
    {
        $response = Yii::$app->getResponse();
        $response->headers->set('Content-Type', $size['mime']);
        $response->headers->set('Content-Length', filesize($imgFullPath));
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'max-age=86400');
        $response->headers->set('Expires', gmdate('D, d M Y H:i:s \G\M\T', time() + 86400));
        $response->format = $response::FORMAT_RAW;

        if (!is_resource($response->stream = fopen($imgFullPath, 'r'))) {
            throw new \yii\web\ServerErrorHttpException('file access failed: permission deny');
        }
        return $response->send();
    }

}
