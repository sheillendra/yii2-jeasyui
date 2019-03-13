<?php


use yii\helpers\StringHelper;

$controllerClass = StringHelper::basename($generator->controllerClass);
$modelClass = StringHelper::basename($generator->modelClass);

$class = $generator->modelClass;
$pks = $class::primaryKey();
$actionParams = $generator->generateActionParams();
$actionParamComments = $generator->generateActionParamComments();
echo "<?php\n";
?>

namespace <?= StringHelper::dirname(ltrim($generator->controllerClass, '\\')) ?>;

use Yii;
use <?= ltrim($generator->modelClass, '\\') ?>;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * <?=$controllerClass?> implements the CRUD actions for <?=$modelClass?> model.
 */
class <?=$controllerClass?> extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Workorder models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(Yii::$app->request->isAjax){
            return $this->renderAjax('_index');
        }else{
            return $this->render('index');
        }
    }
 
    public function actionGetListData($page = 1, $rows = 10) {
        if (Yii::$app->request->isAjax) {
            $result = [];
            $query = <?=$modelClass?>::find();
            $query->select('COUNT(1)');
            $result['total'] = $query->scalar();
            
            $query->select('*');
            $query->offset(($page * $rows) - $rows);
            $query->limit($rows);
            $result['rows'] = $query->All();
            return $this->asJson($result);
        }
    }

    public function actionNew() {
        $model = new <?=$modelClass?>();

        if ($model->load(Yii::$app->request->post())) {
            $result=[];
            if ($model->save(true)) {
                $result['status'] = 'success';
            } else {
                $result['status'] = 'error';
                $result['error'] =  $model->getErrors();
            }
            return $this->asJson($result);
        } else {
            if (Yii::$app->request->isAjax) {
                return  $this->renderAjax('_new', ['model' => $model]);
            } else {
                return $this->render('new');
            }
        }
    }
    
    /**
     * Deletes an existing <?= $modelClass ?> model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return mixed
     */
    public function actionDelete()
    {
        <?php 
            $tempPk = [];
            foreach ($pks as $pk){
                $tempPk[] = '$ids["'.$pk.'"]';
            }
        ?>
        $arrPost = Yii::$app->request->post('ids');
        foreach($arrPost as $ids){
            $this->findModel(<?=implode(', ',$tempPk)?>)->delete();
        }

        return $this->asJson(['status' => 'success']);
    }
  
    /**
     * Finds the <?= $modelClass ?> model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return <?=                   $modelClass ?> the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(<?= $actionParams ?>)
    {
<?php
if (count($pks) === 1) {
    $condition = '$id';
} else {
    $condition = [];
    foreach ($pks as $pk) {
        $condition[] = "'$pk' => \$$pk";
    }
    $condition = '[' . implode(', ', $condition) . ']';
}
?>
        if (($model = <?= $modelClass ?>::findOne(<?= $condition ?>)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
