<?php

namespace sheillendra\jeasyui\modules\api\modules\jeasyui\controllers;

use sheillendra\jeasyui\modules\api\modules\jeasyui\models\UserSearch;

class UserController extends \sheillendra\jeasyui\modules\api\controllers\UserController {

    public $searchModelClass = UserSearch::class;
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'rows'
    ];

}
