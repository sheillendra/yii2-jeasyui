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
    public function init()
    {
        parent::init();
        
    }
    
    protected function registerAsset($name)
    {
        $view = $this->getView();
        JEasyUIAsset::register($view);
        $view->registerJs(
            ($this->theme?'easyloader.theme="'.$this->theme.'";':'')
            . 'using("'.$name.'",function(){'
            .'$("'.$this->target.'").'.$name
            .($this->method?'("'.$this->method.'",'.Json::encode($this->clientOptions).');':
                '('.Json::encode($this->clientOptions).');')
            . '});'
        );
    }
}