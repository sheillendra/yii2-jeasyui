<?php

namespace sheillendra\jeasyui\components\helpers;

use Yii;
use yii\web\UploadedFile;

trait UploadPdfTrait {
    public function getUploadPath()
    {
        $path1 = Yii::getAlias('@uploads') . DIRECTORY_SEPARATOR . date('Y') . DIRECTORY_SEPARATOR . date('m');

        if (!is_dir($path1)) {
            mkdir($path1, '0777', true);
        }

        return $path1;
    }

    /**
     * 
     * @return array
     */
    public function uploadImage($beforeSave = true)
    {
        $result = ['success' => false, 'message' => ''];
        $this->pdfFile = UploadedFile::getInstanceByName('pdfFile');
        if ($this->pdfFile === null) {
            $result['message'] = 'File cannot blank';
            return $result;
        }
        $filename = str_replace('.', '_', microtime(true)) . '.' . $this->pdfFile->extension;
        $this->_pathFile = $this->getUploadPath() . DIRECTORY_SEPARATOR . $filename;
        $this->filename = date('Y') . DIRECTORY_SEPARATOR . date('m') . DIRECTORY_SEPARATOR . $filename;

        if($beforeSave){
            $result['success'] = true;
            $result['message'] = 'Upload before save.';
            return $result;
        } else {
            $this->pdfFile->saveAs($this->_pathFile, false);
            $result['success'] = true;
            $result['message'] = 'Upload item picture is success.';
            return $result;
        }
    }
}