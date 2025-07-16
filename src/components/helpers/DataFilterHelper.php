<?php

namespace sheillendra\jeasyui\components\helpers;

use yii\helpers\Json;

class DataFilterHelper
{
    const OP_ADAPTER = [
        'equal' => '=',
        'contains' => 'like',
        'greater' => '>',
        'greaterorequal' => '>=',
        'less' => '<',
        'lessorequal' => '<=',
        'notequal' => '<>'
    ];

    /**
     * @param \yii\data\ActiveDataProvider $provider
     */
    static public function prepareDataProvider($provider, $params)
    {
        if (isset($params['page'])) {
            $provider->pagination = [
                'pageSizeParam' => 'rows',
                'params' => $params
            ];
        }

        $enableMultiSort = false;
        if (isset($params['sort']) && isset($params['order'])) {
            $sorts = explode(',', $params['sort']);
            $orders = explode(',', $params['order']);
            foreach ($sorts as $k => &$sort) {
                if ($orders[$k] === 'desc') {
                    $sort = '-' . $sort;
                }
            }
            $params['sort'] = implode(',', $sorts);
            if ($k > 0) {
                $enableMultiSort = true;
            }

            $provider->sort->params = $params;
            $provider->sort->enableMultiSort = $enableMultiSort;
        }

        if (!isset($params['filterRules'])) {
            return $provider;
        }

        $rules = Json::decode($params['filterRules']);
        foreach ($rules as $rule) {
            if ($rule['value'] !== '') {
                if ($rule['op'] === 'contains') {
                    $autoInfix = true;
                    $value = $rule['value'];
                    $field = $rule['field'];

                    if (isset($rule['percent'])) {
                        if ($rule['percent'] === 'suffix') {
                            $value = $value . '%';
                            $autoInfix = false;
                        }elseif ($rule['percent'] === 'prefix') {
                            $value = '%' . $value;
                            $autoInfix = false;
                        }
                    }
                    //some database like oracle in "like" operator cannot insensitive 
                    if (isset($rule['insensitive']) && $rule['insensitive']) {
                        $value = strtolower($value);
                        $field = 'lower(' . $field . ')';
                    }

                    $provider->query->andFilterWhere([self::OP_ADAPTER['contains'], $field, $value, $autoInfix]);
                } elseif (isset($rule['is_date']) && $rule['is_date']) {
                    $provider->query->andFilterWhere([self::OP_ADAPTER[$rule['op']], 'TRUNC(' . $rule['field'] . ')', $rule['value']]);
                } elseif (isset($rule['is_datetime']) && $rule['is_datetime']) {
                    //jika date time harus di cari dulu tanggal itu baru kemudian di filter time nya
                    //biar gak berat
                    $provider->query->andFilterWhere([self::OP_ADAPTER['equal'], 'TRUNC(' . $rule['field'] . ')', date('Y-m-d', strtotime($rule['value']))]);
                } else {
                    $provider->query->andFilterWhere([self::OP_ADAPTER[$rule['op']], $rule['field'], $rule['value']]);
                }
            }
        }

        return $provider;
    }
}
