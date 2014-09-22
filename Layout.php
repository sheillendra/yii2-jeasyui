<?php
namespace sheillendra\jeasyui;

class Layout extends Widget
{
    public $regions=[];
    /**
     * Initializes the widget.
     */
    public function init()
    {
        parent::init();
        
    }
    
    public function run()
    {
        $this->registerAsset('layout');
        foreach($this->regions as $region=>$clientOptions){
            $clientOptions['region']=$region;
            LayoutRegion::widget(['target'=>$this->target,'method'=>'add','clientOptions'=>$clientOptions]);
        }
    }
}