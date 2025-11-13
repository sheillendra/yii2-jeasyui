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
                        $autoInfix = false;
                        if ($rule['percent'] === 'suffix') {
                            $value = $value . '%';
                        } elseif ($rule['percent'] === 'prefix') {
                            $value = '%' . $value;
                        }
                    }
                    //some database like oracle in "like" operator cannot insensitive 
                    if (isset($rule['insensitive']) && $rule['insensitive']) {
                        $value = strtolower($value);
                        $field = 'lower(' . $field . ')';
                    }

                    if ($autoInfix) {
                        $provider->query->andFilterWhere([self::OP_ADAPTER['contains'], $field, $value]);
                    } else {
                        $provider->query->andFilterWhere([self::OP_ADAPTER['contains'], $field, $value, $autoInfix]);
                    }
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

        /**
         * dalam treegrid, ketika filter, setelah data didapat maka data parent juga harus ikut
         * kalau tidak data tidak akan muncul
         */
        if (isset($params['treegrid'])) {
            /** @var \yii\db\ActiveQuery $query */
            $query = $provider->query;
            $filteredSql = $query->createCommand()->getRawSql();

            $provider->query = $provider->query->modelClass::findBySql(<<<SQL
WITH RECURSIVE 
	filtered AS (
		$filteredSql
	),
	parents AS (
       	SELECT pc.*
	   	FROM {$params['treegrid']} pc
      	JOIN filtered f ON f."_parentId" = pc.id
        
		UNION ALL
		
      	SELECT pc.*
      	FROM {$params['treegrid']} pc
       	JOIN parents p ON p."_parentId" = pc.id
  	)
	  
SELECT * FROM (
	SELECT DISTINCT filtered.* FROM filtered
   	UNION
	SELECT parents.* FROM parents
)
ORDER BY id
SQL);
        }
        return $provider;
    }
}
