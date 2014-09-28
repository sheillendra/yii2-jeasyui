<?php
namespace sheillendra\jeasyui;

class LayoutRegion extends Widget
{
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
    }
    
    public function run()
    {
        $this->registerScript('layout');
        foreach($this->content as $name=>$clientOptions){
            if($name==='tabs'){
                Tabs::widget(['parent'=>$this->clientOptions['id'],'clientOptions'=>$clientOptions]);
            }
        }
    }
}