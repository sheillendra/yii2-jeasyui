<?php
namespace sheillendra\jeasyui;

use yii\helpers\Json;

class Widget extends \yii\base\Widget
{
    /**
     * @var string
     */
    public $target;
    /**
     * @var string
     */
    public $tagName;
    public $theme;
    public $method;
    public $options = [];
    public $clientOptions = [];
    public $beforeRegister='';
    public $afterRegister='';
    
    public function init()
    {
        parent::init();
        if (!isset($this->clientOptions['id'])) {
            $this->clientOptions['id'] = $this->getId();
        }
    }
    
    protected function registerScript($plugin)
    {
        $view = $this->getView();
        $view->params['jEasyUI']['theme']=$this->theme?$this->theme:'default';
        $view->params['jEasyUI']['plugin'][]=$plugin;
        $view->params['jEasyUI']['command'][]=
            $this->beforeRegister
            .'$("'.$this->target.'").'.$plugin
            .($this->method?'("'.$this->method.'",'.Json::encode($this->clientOptions).');':
                '('.Json::encode($this->clientOptions).');')
            . $this->afterRegister;
    }
    
    public function pluginMap($key){
        $pluginArr=[
            'accordion'=>'Accordion'
            ,'datagrid'=>'DataGrid'
            ,'linkbutton'=>'LinkButton'
            ,'tabs'=>'Tabs'];
        return 'sheillendra\jeasyui\\'.$pluginArr[strtolower($key)];
    }
}