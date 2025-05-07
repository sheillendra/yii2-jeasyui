<?php

namespace sheillendra\jeasyui\components\helpers;

use Yii;
use yii\web\UploadedFile;

trait UploadTrait
{
    public function getUploadPath()
    {
        $path1 = Yii::getAlias('@uploads') . DIRECTORY_SEPARATOR . date('Y') . DIRECTORY_SEPARATOR . date('m');

        if (!is_dir($path1)) {
            mkdir($path1, 0777, true);
        }

        return $path1;
    }

    /**
     * 
     * @return array
     */
    public function upload($filePath = 'filepath', $fileName = 'filename', $input = 'inputFile', $beforeSave = true)
    {
        $this->{$input} = UploadedFile::getInstanceByName($input);
        if ($this->{$input} === null) {
            if ($this->isAttributeRequired($filePath) && $this->{$filePath} === '') {
                $this->addError($input, 'File cannot blank');
            }
            return;
        }
        $newFilename = str_replace('.', '_', microtime(true)) . '.' . $this->{$input}->extension;
        $this->_pathFile = $this->getUploadPath() . DIRECTORY_SEPARATOR . $newFilename;
        $this->{$filePath} = date('Y') . DIRECTORY_SEPARATOR . date('m') . DIRECTORY_SEPARATOR . $newFilename;
        $this->{$fileName} = $this->{$input}->name;

        if ($beforeSave === false) {
            $this->{$input}->saveAs($this->_pathFile, false);
        }
    }
}
