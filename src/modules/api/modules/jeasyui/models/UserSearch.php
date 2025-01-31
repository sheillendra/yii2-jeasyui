<?php

namespace sheillendra\jeasyui\modules\api\modules\jeasyui\models;

use sheillendra\jeasyui\components\helpers\DataFilterHelper;

class UserSearch extends \sheillendra\jeasyui\modules\api\models\UserSearch {

    public function search($params)
    {
        return DataFilterHelper::prepareDataProvider(parent::search($params), $params);
    }
}