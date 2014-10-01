<?php
namespace sheillendra\jeasyui;

class Accordion extends Widget
{
    public $parent;
    private $items=[];
    /**
     * Initializes the widget.
     */
    public function init()
    {
        parent::init();
        if(isset($this->clientOptions['items']) && is_array($this->clientOptions['items'])){
            $this->items = $this->clientOptions['items'];
            $this->clientOptions['items']=null;
        }
        if(isset($this->clientOptions['id'])){
            $this->target='#'.$this->clientOptions['id'];
            $this->beforeRegister='$("#'.$this->parent.'").append(\'<div id="'.$this->clientOptions['id'].'"></div>\');';
            
            unset($this->clientOptions['id']);
        }
    }
    
    public function run()
    {
        $this->registerScript('accordion');
        foreach($this->items as $clientOptions){
            AccordionItem::widget(['target'=>$this->target,'method'=>'add','clientOptions'=>$clientOptions]);
        }
    }
}