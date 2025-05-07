<?php

namespace sheillendra\jeasyui\modules\api\modules\jeasyui\controllers;

use sheillendra\jeasyui\components\rest\Serializer;
use sheillendra\jeasyui\modules\api\modules\jeasyui\models\UserSearch;

class UserController extends \sheillendra\jeasyui\modules\api\controllers\UserController {

    public $searchModelClass = UserSearch::class;
    public $serializer = [
        'class' => Serializer::class,
        'collectionEnvelope' => 'rows'
    ];

}
