<?php

namespace sheillendra\jeasyui\components\helpers;

use Yii;

trait ExtModelTrait
{

    /**
     * This is flag this model call from SearchModel, 
     * Sometime we create beforeValidation but only for model as form
     */
    public $fromSearch = false;

    /**
     * 
     * @return type
     */
    public function behaviors()
    {
        return [
            'yii\behaviors\TimestampBehavior',
            'yii\behaviors\BlameableBehavior',
        ];
    }

    public function pdfId($data, $paramsKey = 'jwtKey')
    {
        return Yii::$app->security->hashData(
            implode('|', $data),
            Yii::$app->params[$paramsKey]
        );
    }

    /**
     * 
     * @param int $id
     * @return Array
     */
    public static function pdfIdExtract($id, $paramsKey = 'jwtKey')
    {
        return explode('|', Yii::$app->security->validateData(
            $id,
            Yii::$app->params[$paramsKey]
        ));
    }

    public function getUploadPath()
    {
        $path1 = Yii::getAlias('@uploads') . DIRECTORY_SEPARATOR . date('Y') . DIRECTORY_SEPARATOR . date('m');

        if (!is_dir($path1)) {
            mkdir($path1, 0777, true);
        }

        return $path1;
    }
}
