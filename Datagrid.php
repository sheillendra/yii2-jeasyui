<?php
namespace sheillendra\jeasyui;

class Datagrid extends Widget
{
    public $parent;
    /**
     * Initializes the widget.
     */
    public function init()
    {
        parent::init();
        
        if(isset($this->clientOptions['id'])){
            $this->target='#'.$this->clientOptions['id'];
            $this->beforeRegister='$("#'.$this->parent.'").append(\'<div id="'.$this->clientOptions['id'].'"></div>\');';
            unset($this->clientOptions['id']);
        }
    }
    
    public function run()
    {
        $this->registerScript('datagrid');
    }
}