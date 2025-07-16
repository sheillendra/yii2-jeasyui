<?php

namespace sheillendra\jeasyui\components\helpers;

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
}
