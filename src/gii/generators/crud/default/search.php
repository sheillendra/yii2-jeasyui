<?php
/**
 * This is the template for generating CRUD search class of the specified model.
 */

use yii\helpers\StringHelper;


/** @var yii\web\View $this */
/** @var sheillendra\jeasyui\gii\generators\crud\Generator $generator */

$modelClass = StringHelper::basename($generator->modelClass);
$searchModelClass = StringHelper::basename($generator->searchModelClass);
if ($modelClass === $searchModelClass) {
    $modelAlias = $modelClass . 'Model';
}
$rules = $generator->generateSearchRules();
$labels = $generator->generateSearchLabels();
$searchAttributes = $generator->getSearchAttributes();
$searchConditions = $generator->generateSearchConditions();

$model = new ($generator->modelClass);
$labels = $model->attributeLabels();
if(method_exists($model, 'getEasyuiAttributes')){
    $easyuiAttributes = $model->easyuiAttributes;
} else {
    $easyuiAttributes = [];
}

$varText='';
$attributeMap = '';
$noPagination = '';
$joinWith = '';
if(isset($easyuiAttributes['_'])){
    if(isset($easyuiAttributes['_']['noPagination']) && $easyuiAttributes['_']['noPagination']){
        $noPagination = '        if(isset($params[\'per-page\']) && $params[\'per-page\'] === \'false\'){' . "\n";
        $noPagination .= '            $dataProvider->pagination = false;' . "\n";
        $noPagination .= "        }\n";
    }

    if(isset($easyuiAttributes['_']['filterRelations'])){
        $attributeMap .= "            'attributeMap' => [\n";
        $relationRules = [];
        foreach($easyuiAttributes['_']['filterRelations'] as $k=>$v){
            $joinWith .= "->joinWith('{$k}')"; 
            foreach($v as $kk => $vv){
                $relationRules[] = $vv;
                $varText .= "    public \${$vv};\n";
                $attributeMap .= "                '{$vv}' => '{{{$kk}}}.$vv',\n";
            }
        }
        $rules[] = '[[\''. implode('\', \'', $relationRules). "'], 'safe']";
        $attributeMap .= "            ],\n";
    }
}
$noPagination .= '        $this->fromSearch = true;' . "\n";

echo "<?php\n";
?>

namespace <?= StringHelper::dirname(ltrim($generator->searchModelClass, '\\')) ?>;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataFilter;
use yii\data\ActiveDataProvider;

/**
 * <?= $searchModelClass ?> represents the model behind the search form of `<?= $generator->modelClass ?>`.
 */
class <?= $searchModelClass ?> extends <?= isset($modelAlias) ? $modelAlias : $modelClass ?>

{
<?=$varText?>
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            <?= implode(",\n            ", $rules) ?>,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {

        $filter = new ActiveDataFilter([
            'searchModel' => $this,
<?=$attributeMap?>
        ]);

        $filterCondition = null;

        if ($filter->load(Yii::$app->request->get())) {
            $filterCondition = $filter->build();
            if ($filterCondition === false) {
                return $filter;
            }
        }

        $query = <?= isset($modelAlias) ? $modelAlias : $modelClass ?>::find()<?=$joinWith?>;

        if ($filterCondition !== null) {
            $query->andWhere($filterCondition);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

<?=$noPagination?>

        return $dataProvider;
    }
}
