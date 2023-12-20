<?php

/**
 * @link https://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license https://www.yiiframework.com/license/
 */

namespace sheillendra\jeasyui\gii\generators\model;

use Yii;
use yii\base\InvalidConfigException;
use yii\base\NotSupportedException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Connection;
use yii\db\Exception;
use yii\db\Schema;
use yii\db\TableSchema;
use yii\gii\CodeFile;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/**
 * This generator will generate one or multiple ActiveRecord classes for the specified database table.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class Generator extends \yii\gii\generators\model\Generator
{

    public $ns = 'common\models';
    public $ans = 'api\modules\v1';

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'jEsyUI Model Generator';
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return 'This generator generates an ActiveRecord class for the specified database table, with extends class which you can custom here.';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['db', 'ns', 'ans', 'tableName', 'baseClass', 'queryNs', 'queryBaseClass'], 'required'],
            [
                ['ns', 'ans', 'queryNs'],
                'filter',
                'filter' => static function ($value) {
                    return $value === null ? null : trim($value, ' \\');
                }
            ],
            [['ns', 'ans', 'queryNs'], 'validateNamespace'],
            [['ns', 'ans', 'baseClass', 'queryNs', 'queryBaseClass'], 'match', 'pattern' => '/^[\w\\\\]+$/', 'message' => 'Only word characters and backslashes are allowed.'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'ans' => 'Api Namespace',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function stickyAttributes()
    {
        return array_merge(
            parent::stickyAttributes(),
            [
                'ans',
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function hints()
    {
        return array_merge(parent::hints(), [
            'ans' => 'This is the namespace of the ActiveRecord class to be generated, e.g., <code>app\models</code>',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function requiredTemplates()
    {
        $templates = ['model.php', 'extended.php'];
        if ($this->queryClass !== null) {
            $templates[] = 'query.php';
        }

        return $templates;
    }

    /**
     * @inheritdoc
     */
    public function generate()
    {
        $files = [];
        $relations = $this->generateRelations();
        $relationExts = $this->generateRelations('Ext');
        $db = $this->getDbConnection();
        foreach ($this->getTableNames() as $tableName) {
            // model:
            $modelClassName = $this->generateClassName($tableName);
            $queryClassName = $this->generateQuery ? $this->generateQueryClassName($modelClassName) : false;
            $tableRelations = isset($relations[$tableName]) ? $relations[$tableName] : [];
            $tableRelationExts = isset($relations[$tableName]) ? $relationExts[$tableName] : [];
            $tableSchema = $db->getTableSchema($tableName);
            $params = [
                'tableName' => $tableName,
                'className' => $modelClassName,
                'queryClassName' => $queryClassName,
                'tableSchema' => $tableSchema,
                'properties' => $this->generateProperties($tableSchema),
                'labels' => $this->generateLabels($tableSchema),
                'rules' => $this->generateRules($tableSchema),
                'relations' => $tableRelations,
                'relationExts' => $tableRelationExts,
                'relationsClassHints' => $this->generateRelationsClassHints($tableRelations, $this->generateQuery),
            ];
            $files[] = new CodeFile(
                Yii::getAlias('@' . str_replace('\\', '/', $this->ns)) . '/' . $modelClassName . '.php',
                $this->render('model.php', $params)
            );

            $files[] = new CodeFile(
                Yii::getAlias('@' . str_replace('\\', '/', $this->ns)) . '/' . $modelClassName . 'Ext.php',
                $this->render('extended.php', $params)
            );

            $files[] = new CodeFile(
                Yii::getAlias('@' . str_replace('\\', '/', $this->ans)) . '/models/' . $modelClassName . 'Ext.php',
                $this->render('extended-api.php', $params)
            );

            // query:
            if ($queryClassName) {
                $params['className'] = $queryClassName;
                $params['modelClassName'] = $modelClassName;
                $files[] = new CodeFile(
                    Yii::getAlias('@' . str_replace('\\', '/', $this->queryNs)) . '/' . $queryClassName . '.php',
                    $this->render('query.php', $params)
                );
            }
        }

        return $files;
    }

    private function generateManyManyRelations($table, $fks, $relations, $ext)
    {
        $db = $this->getDbConnection();


        foreach ($fks as $pair) {
            list($firstKey, $secondKey) = $pair;
            $table0 = $firstKey[0][0];
            $table1 = $secondKey[0][0];
            unset($firstKey[0][0], $secondKey[0][0]);
            $className0 = $this->generateClassName($table0) . $ext;
            $className1 = $this->generateClassName($table1) . $ext;
            $className0Resolution = $this->generateClassNameResolution($className0) . $ext;
            $className1Resolution = $this->generateClassNameResolution($className1) . $ext;
            $table0Schema = $db->getTableSchema($table0);
            $table1Schema = $db->getTableSchema($table1);

            // @see https://github.com/yiisoft/yii2-gii/issues/166
            if ($table0Schema === null || $table1Schema === null) {
                continue;
            }

            $link = $this->generateRelationLink(array_flip($secondKey[0]));
            $relationName = $this->generateRelationName($relations, $table0Schema, key($secondKey[0]), true);
            if ($this->generateJunctionRelationMode === self::JUNCTION_RELATION_VIA_TABLE) {
                $relations[$table0Schema->fullName][$relationName] = [
                    "return \$this->hasMany($className1Resolution, $link)->viaTable('"
                        . $this->generateTableName($table->name) . "', " . $this->generateRelationLink($firstKey[0]) . ');',
                    $className1,
                    true,
                ];
            } elseif ($this->generateJunctionRelationMode === self::JUNCTION_RELATION_VIA_MODEL) {
                $foreignRelationName = null;
                foreach ($relations[$table0Schema->fullName] as $key => $foreignRelationConfig) {
                    if ($foreignRelationConfig[3] == $firstKey[1]) {
                        $foreignRelationName = $key;
                        break;
                    }
                }
                if (empty($foreignRelationName)) {
                    throw new Exception('Foreign key for junction table not found.');
                }
                $relations[$table0Schema->fullName][$relationName] = [
                    "return \$this->hasMany($className1Resolution, $link)->via('"
                        . lcfirst($foreignRelationName) . "');",
                    $className1,
                    true,
                ];
            }

            $link = $this->generateRelationLink(array_flip($firstKey[0]));
            $relationName = $this->generateRelationName($relations, $table1Schema, key($firstKey[0]), true);
            if ($this->generateJunctionRelationMode === self::JUNCTION_RELATION_VIA_TABLE) {
                $relations[$table1Schema->fullName][$relationName] = [
                    "return \$this->hasMany($className0Resolution, $link)->viaTable('"
                        . $this->generateTableName($table->name) . "', " . $this->generateRelationLink($secondKey[0]) . ');',
                    $className0,
                    true,
                ];
            } elseif ($this->generateJunctionRelationMode === self::JUNCTION_RELATION_VIA_MODEL) {
                $foreignRelationName = null;
                foreach ($relations[$table1Schema->fullName] as $key => $foreignRelationConfig) {
                    if ($foreignRelationConfig[3] == $secondKey[1]) {
                        $foreignRelationName = $key;
                        break;
                    }
                }
                if (empty($foreignRelationName)) {
                    throw new Exception('Foreign key for junction table not found.');
                }
                $relations[$table1Schema->fullName][$relationName] = [
                    "return \$this->hasMany($className0Resolution, $link)->via('"
                        . lcfirst($foreignRelationName) . "');",
                    $className0,
                    true,
                ];
            } else {
                throw new InvalidConfigException('Unknown generateViaRelationMode ' . $this->generateJunctionRelationMode);
            }
        }

        return $relations;
    }

    protected function generateRelations($ext = '')
    {
        if ($this->generateRelations === self::RELATIONS_NONE) {
            return [];
        }

        $db = $this->getDbConnection();
        $relations = [];
        $schemaNames = $this->getSchemaNames();
        foreach ($schemaNames as $schemaName) {
            foreach ($db->getSchema()->getTableSchemas($schemaName) as $table) {
                $className = $this->generateClassName($table->fullName);
                $classNameResolution = $this->generateClassNameResolution($className);
                foreach ($table->foreignKeys as $foreignKey => $refs) {
                    $refTable = $refs[0];
                    $refTableSchema = $db->getTableSchema($refTable);
                    if ($refTableSchema === null) {
                        // Foreign key could point to non-existing table: https://github.com/yiisoft/yii2-gii/issues/34
                        continue;
                    }
                    unset($refs[0]);
                    $fks = array_keys($refs);
                    $refClassName = $this->generateClassName($refTable) . $ext;
                    $refClassNameResolution = $this->generateClassNameResolution($refClassName);

                    // Add relation for this table
                    $link = $this->generateRelationLink(array_flip($refs));
                    $relationName = $this->generateRelationName($relations, $table, $fks[0], false);
                    $relations[$table->fullName][$relationName] = [
                        "return \$this->hasOne($refClassNameResolution, $link);",
                        $refClassName,
                        false,
                        $table->fullName . '.' . $foreignKey
                    ];

                    // Add relation for the referenced table
                    $hasMany = $this->isHasManyRelation($table, $fks);
                    $link = $this->generateRelationLink($refs);
                    $relationName = $this->generateRelationName($relations, $refTableSchema, $className, $hasMany);
                    $relations[$refTableSchema->fullName][$relationName] = [
                        "return \$this->" . ($hasMany ? 'hasMany' : 'hasOne') . "($classNameResolution, $link);",
                        $className,
                        $hasMany,
                        $table->fullName . '.' . $foreignKey
                    ];
                }
            }

            foreach ($db->getSchema()->getTableSchemas($schemaName) as $table) {
                if (($junctionFks = $this->checkJunctionTable($table)) === false) {
                    continue;
                }

                $relations = $this->generateManyManyRelations($table, $junctionFks, $relations, $ext);
            }
        }

        if ($this->generateRelations === self::RELATIONS_ALL_INVERSE) {
            $relations =  $this->addInverseRelations($relations);
        }

        foreach ($relations as &$relation) {
            ksort($relation);
        }

        return $relations;
    }
}
