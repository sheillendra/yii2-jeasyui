<?php
/**
 * Implement jQuery EasyUI v.1.4 GPL Edition on Yii2
 * version  : v.0.0.1
 * author   : sheillendra
 * date     : 2014-10-04
 * website  : demo.dodeso.com
 */

namespace sheillendra\jeasyui;

use yii\helpers\Json;
use yii\helpers\ArrayHelper;
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
    public $depedencyPlugin=[];
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
        $this->eventNormalizer();
    }

    protected function eventNormalizer(){
        foreach($this->clientOptions as $k=>$v){
            if(gettype($v)==='string' && strpos($v, 'function')===0){
                $this->clientOptions[$k]=new JsExpression($v);
            }
        }
    }
    
    protected function registerScript() {
        $view = $this->getView();
        $this->depedencyPlugin[]=$this->plugin;
        $view->params['jEasyUI']['plugin'] = ArrayHelper::merge(
                isset($view->params['jEasyUI']['plugin'])?
                    $view->params['jEasyUI']['plugin']:[],
                $this->depedencyPlugin);
        $view->params['jEasyUI']['command'][] = $this->beforeRegister
                . '$("' . $this->target . '").' . $this->plugin
                . ($this->method ? '("' . $this->method . '",' . Json::encode($this->clientOptions) . ');' :
                        '(' . Json::encode($this->clientOptions) . ');')
                . $this->afterRegister;
    }

    public function appendDivToParent(){return $this->appendTagToParent();}
    public function appendUlToParent(){return $this->appendTagToParent('ul');}
    public function appendTableToParent(){return $this->appendTagToParent('table');}
    public function appendFormToParent(){return $this->appendTagToParent('form');}
    public function appendTagToParent($tag='div',$id=null){
        if(isset($this->parent)){
            $id=$id?id:$this->clientOptions['id'];
            $this->target='#'.$this->clientOptions['id'];
            $this->beforeRegister='$("#'.$this->parent.'").append(\'<'.$tag.' id="'.$id.'"></'.$tag.'>\');';
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
    
    public function appendLinkDivToParent() {
        $this->appendLinkToParent();
        $this->appendTagToParent('div',$this->clientOptions['menu']);
    }
    public function appendInputToParent() {
        if (isset($this->parent)) {
            $this->target = '#' . $this->clientOptions['id'];
            $this->beforeRegister = '$("#' . $this->parent . '").append(\'<input id="'
                    . $this->clientOptions['id']
                    . '" />\');';
        }
    }
}
