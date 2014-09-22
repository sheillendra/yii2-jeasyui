<?php
namespace sheillendra\jeasyui;

class LayoutRegion extends Widget
{
    /**
     * Initializes the widget.
     */
    public function init()
    {
        parent::init();
        
    }
    
    public function run()
    {
        $this->registerAsset('layout',$this->method);
    }
}