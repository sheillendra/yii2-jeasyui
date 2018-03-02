<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace sheillendra\jeasyui\components\rest;

use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;

/**
 * 
 */
class IndexAction extends \yii\rest\IndexAction {

    const OP_ADAPTER = [
        'equal' => 'eq',
        'contains' => 'like'
    ];

    /**
     * @inheritdoc
     */
    protected function prepareDataProvider() {
        $requestParams = Yii::$app->getRequest()->getBodyParams();
        if (empty($requestParams)) {
            $requestParams = Yii::$app->getRequest()->getQueryParams();
        }

        if (isset($requestParams['filterRules'])) {
            $rules = Json::decode($requestParams['filterRules']);
            $tmp = [];
            foreach ($rules as $rule) {
                if ($rule['value'] !== '') {
                    //some database like oracle in "like" operator cannot insensitive 
                    if (isset($rule['insensitive']) && $rule['insensitive']) {
                        $tmp[] = [
                            'lower(' . $rule['field'] . ')' => [
                                self::OP_ADAPTER[$rule['op']] => strtolower($rule['value'])
                            ]
                        ];
                    } else {
                        $tmp[] = [
                            $rule['field'] => [
                                self::OP_ADAPTER[$rule['op']] => $rule['value']
                            ]
                        ];
                    }
                }
            }
            if (count($tmp) > 1) {
                $requestParams['filter']['and'] = $tmp;
            } else if ($tmp) {
                $requestParams['filter'] = $tmp[0];
            }
        }

        $filter = null;
        if ($this->dataFilter !== null) {
            $this->dataFilter = Yii::createObject($this->dataFilter);
            if ($this->dataFilter->load($requestParams)) {
                $filter = $this->dataFilter->build();
                if ($filter === false) {
                    return $this->dataFilter;
                }
            }
        }

        if ($this->prepareDataProvider !== null) {
            return call_user_func($this->prepareDataProvider, $this, $filter);
        }

        /* @var $modelClass \yii\db\BaseActiveRecord */
        $modelClass = $this->modelClass;

        $query = $modelClass::find();
        if (!empty($filter)) {
            $query->andWhere($filter);
        }

        if (isset($requestParams['page'])) {
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'params' => $requestParams,
                    'pageSizeParam' => 'rows'
                ],
                'sort' => [
                    'params' => $requestParams,
                ],
            ]);
            return [
                'total' => $dataProvider->getTotalCount(),
                'rows' => $dataProvider->getModels()
            ];
        }
        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
            'sort' => [
                'params' => $requestParams,
            ],
        ]);
    }

}
