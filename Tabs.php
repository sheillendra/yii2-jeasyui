<?php
namespace sheillendra\jeasyui;

class Tabs extends Widget
{
    public $parent;
    private $content=[];
    /**
     * Initializes the widget.
     */
    public function init()
    {
        parent::init();
        if(isset($this->clientOptions['content']) && is_array($this->clientOptions['content'])){
            $this->content = $this->clientOptions['content'];
            $this->clientOptions['content']=null;
        }
        if(isset($this->clientOptions['id'])){
            $this->target='#'.$this->clientOptions['id'];
            $this->beforeRegister='$("#'.$this->parent.'").append(\'<div id="'.$this->clientOptions['id'].'"></div>\');';
            
            unset($this->clientOptions['id']);
        }
    }
    
    public function run()
    {
        $this->registerScript('tabs');
        foreach($this->content as $clientOptions){
           $this->method='add';
           $this->clientOptions=$clientOptions;
           $this->registerScript('tabs');
        }
    }
}