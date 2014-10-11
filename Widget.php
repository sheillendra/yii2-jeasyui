<?php

namespace sheillendra\jeasyui;

use yii\helpers\Json;
use yii\web\JsExpression;

class Widget extends \yii\base\Widget {

    /**
     * @var string
     */
    public $contents=[];
    public $target;
    public $parent;
    /**
     *
     * @var string plugin name of jeasyui,
     * if for contents, if plugin not set, automatically same with parent plugin 
     */
    public $plugin;
    /**
     * @var string
     */
    public $method;
    /**
     * clientOptions akan menjadi parameter plugin jeasyui
     * @var array 
     */
    public $clientOptions = [];
    /**
     * Script yang akan ditempatkan sebelum pemanggilan fungsi plugin jEasyUI
     * @var string 
     */
    public $beforeRegister = '';
    /**
     * Script yang akan ditempatkan setelah pemanggilan fungsi plugin jEasyUI
     * @var string 
     */
    public $afterRegister = '';
    
    public $url= 'javascript:void(0)';

    public function init() {
        parent::init();
        if (!isset($this->clientOptions['id'])&&$this->target!=='body') {
            $this->clientOptions['id'] = $this->getId();
        }
        $this->eventNormalize();
    }

    protected function registerScript() {
        $view = $this->getView();
        $view->params['jEasyUI']['plugin'][] = $this->plugin;
        $view->params['jEasyUI']['command'][] = $this->beforeRegister
                . '$("' . $this->target . '").' . $this->plugin
                . ($this->method ? '("' . $this->method . '",' . Json::encode($this->clientOptions) . ');' :
                        '(' . Json::encode($this->clientOptions) . ');')
                . $this->afterRegister;
    }

    public function appendTargetToParent(){
        if(isset($this->parent)){
            $this->target='#'.$this->clientOptions['id'];
            $this->beforeRegister='$("#'.$this->parent.'").append(\'<div id="'.$this->clientOptions['id'].'"></div>\');';
        }
    }

    public function appendLinkToParent() {
        if (isset($this->parent)) {
            $this->target = '#' . $this->clientOptions['id'];
            $this->beforeRegister = '$("#' . $this->parent . '").append(\'<a id="'
                    . $this->clientOptions['id']
                    . '" href="' . $this->url . '">'
                    . $this->clientOptions['title']
                    . '</a>\');';
        }
    }
    
    protected function eventNormalize(){
        foreach($this->clientOptions as $k=>$v){
            if(gettype($v)==='string' && strpos($v, 'function')===0){
                $this->clientOptions[$k]=new JsExpression($v);
            }
        }
    }

}
