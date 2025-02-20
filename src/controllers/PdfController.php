<?php

namespace sheillendra\jeasyui\controllers;

use Yii;
use yii\web\Controller;

/**
 * Feedback controller
 */
class PdfController extends Controller
{

    public $layout = '//blank';

    public function actionIndex($name)
    {
        $pdfFullPath = Yii::getAlias('@uploads/' . $name);
        $info = pathinfo($pdfFullPath);
        if($info['extension'] === 'pdf'){
            return $this->response($pdfFullPath, $info);
        }
        return 'File is not PDF';
    }

    protected function response($pdfFullPath, $info)
    {
        $response = Yii::$app->getResponse();
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'inline;filename="'. $info['filename'] .'"');
        $response->headers->set('Content-Transfer-Encoding', 'binary');
        $response->headers->set('Content-Length', filesize($pdfFullPath));
        $response->headers->set('Accept-Ranges', 'bytes');

        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'max-age=86400');
        $response->headers->set('Expires', gmdate('D, d M Y H:i:s \G\M\T', time() + 86400));
        $response->format = $response::FORMAT_RAW;

        if (!is_resource($response->stream = fopen($pdfFullPath, 'r'))) {
            throw new \yii\web\ServerErrorHttpException('file access failed: permission deny');
        }
        return $response->send();
    }

}
